<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/SecurityLogRepository.php';
require_once BASE_PATH . '/app/repositories/LoginAttemptRepository.php';

require_admin();

$pdo = db();
$securityLogRepo = new SecurityLogRepository($pdo);
$loginAttemptRepo = new LoginAttemptRepository($pdo);

if (is_post()) {
    require_valid_csrf();
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'cleanup') {
        $days = max(30, min(365, (int) ($_POST['days'] ?? 90)));
        $deletedLogs = $securityLogRepo->deleteOlderThanDays($days);
        $deletedAttempts = $loginAttemptRepo->deleteOlderThanDays($days);
        record_security_event(
            $pdo,
            'security_cleanup',
            'Nettoyage des evenements de plus de ' . $days . ' jours : ' . $deletedLogs . ' logs securite et ' . $deletedAttempts . ' tentatives login supprimes.',
            current_admin_email()
        );
        flash('success', 'Nettoyage termine : ' . $deletedLogs . ' logs securite et ' . $deletedAttempts . ' tentatives login supprimes.');
    } else {
        flash('error', 'Action inconnue.');
    }

    redirect('/admin/security-logs/index.php');
}

$filters = [
    'event_type' => trim((string) ($_GET['event_type'] ?? '')),
    'q' => trim((string) ($_GET['q'] ?? '')),
    'date_from' => trim((string) ($_GET['date_from'] ?? '')),
    'date_to' => trim((string) ($_GET['date_to'] ?? '')),
];
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$logs = [];
$eventTypes = [];
$totalLogs = 0;
$error = null;

try {
    $eventTypes = $securityLogRepo->eventTypes();
    $totalLogs = $securityLogRepo->countFiltered($filters);
    $totalPages = max(1, (int) ceil($totalLogs / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
        $offset = ($page - 1) * $perPage;
    }
    $logs = $securityLogRepo->filtered($filters, $perPage, $offset);
} catch (Throwable) {
    $error = 'Base de donnees indisponible.';
}

$queryParams = array_filter([
    'event_type' => $filters['event_type'],
    'q' => $filters['q'],
    'date_from' => $filters['date_from'],
    'date_to' => $filters['date_to'],
], static fn (string $value): bool => $value !== '');

if (!$error && (string) ($_GET['export'] ?? '') === 'csv') {
    $exportLogs = $securityLogRepo->filtered($filters, 5000, 0);
    $filename = 'journal-securite-' . date('Ymd-His') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    if ($output !== false) {
        fwrite($output, "\xEF\xBB\xBF");
        fputcsv($output, ['id', 'event_type', 'actor_email', 'ip_address', 'user_agent', 'details', 'created_at'], ',', '"', '', "\n");
        foreach ($exportLogs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['event_type'],
                $log['actor_email'],
                $log['ip_address'],
                $log['user_agent'],
                $log['details'],
                $log['created_at'],
            ], ',', '"', '', "\n");
        }
        fclose($output);
    }
    exit;
}

$totalPages = max(1, (int) ceil($totalLogs / $perPage));

admin_header('Journal securite');
?>
<?php render_flash(); ?>
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-medium text-cyan-200">Audit applicatif</p>
        <h1 class="mt-2 text-3xl font-bold text-white">Journal securite</h1>
        <p class="mt-2 max-w-3xl text-slate-400">Cette page centralise les evenements importants : connexions, blocages brute force, moderation, duplication et suppressions de recettes.</p>
    </div>
    <a class="btn-secondary" href="/admin/dashboard.php">Retour dashboard</a>
</div>

<?php if ($error): ?>
    <div class="panel-card p-5 text-amber-100"><?= e($error) ?></div>
<?php else: ?>
    <section class="panel-card mb-5 p-5">
        <form class="grid gap-3 lg:grid-cols-[1fr_1fr_.75fr_.75fr_auto]" method="get" action="/admin/security-logs/index.php">
            <label>
                <span class="mb-2 block text-sm font-semibold text-slate-300">Type d'evenement</span>
                <select class="field" name="event_type">
                    <option value="">Tous les evenements</option>
                    <?php foreach ($eventTypes as $eventType): ?>
                        <option value="<?= e($eventType) ?>" <?= $filters['event_type'] === $eventType ? 'selected' : '' ?>><?= e($eventType) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span class="mb-2 block text-sm font-semibold text-slate-300">Recherche</span>
                <input class="field" type="search" name="q" value="<?= e($filters['q']) ?>" placeholder="Email, IP, navigateur, details...">
            </label>
            <label>
                <span class="mb-2 block text-sm font-semibold text-slate-300">Depuis</span>
                <input class="field" type="date" name="date_from" value="<?= e($filters['date_from']) ?>">
            </label>
            <label>
                <span class="mb-2 block text-sm font-semibold text-slate-300">Jusqu'au</span>
                <input class="field" type="date" name="date_to" value="<?= e($filters['date_to']) ?>">
            </label>
            <div class="flex items-end gap-2">
                <button class="btn-primary" type="submit">Filtrer</button>
                <a class="btn-secondary" href="/admin/security-logs/index.php">Reset</a>
            </div>
        </form>
    </section>

    <div class="mb-4 flex flex-col gap-3 rounded-lg border border-white/10 bg-slate-950/40 p-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-300">
            <?= e((string) $totalLogs) ?> evenement<?= $totalLogs > 1 ? 's' : '' ?> trouve<?= $totalLogs > 1 ? 's' : '' ?>.
            Page <?= e((string) $page) ?> / <?= e((string) $totalPages) ?>.
        </p>
        <div class="flex flex-wrap gap-2">
            <a class="btn-secondary !px-4 !py-2 !text-xs" href="<?= e('/admin/security-logs/index.php?' . http_build_query($queryParams + ['export' => 'csv'])) ?>">Exporter CSV</a>
            <form method="post" action="/admin/security-logs/index.php" data-confirm="Nettoyer les logs et tentatives de connexion de plus de 90 jours ?">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="cleanup">
                <input type="hidden" name="days" value="90">
                <button class="btn-secondary !px-4 !py-2 !text-xs" type="submit">Nettoyer +90 jours</button>
            </form>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-white/10">
        <table class="w-full min-w-[1100px] divide-y divide-white/10 text-left text-sm">
            <thead class="bg-white/5 text-slate-300">
                <tr>
                    <th class="px-4 py-3">Evenement</th>
                    <th class="px-4 py-3">Acteur</th>
                    <th class="px-4 py-3">IP</th>
                    <th class="px-4 py-3">Details</th>
                    <th class="px-4 py-3">User agent</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php if (!$logs): ?>
                    <tr>
                        <td class="px-4 py-8 text-center text-slate-400" colspan="6">Aucun evenement ne correspond aux filtres.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($logs as $log): ?>
                    <tr class="bg-slate-950/40 align-top">
                        <td class="px-4 py-3"><span class="rounded-full bg-cyan-500/15 px-3 py-1 text-xs font-bold text-cyan-100"><?= e($log['event_type']) ?></span></td>
                        <td class="px-4 py-3 text-white"><?= e($log['actor_email'] ?: 'visiteur') ?></td>
                        <td class="px-4 py-3 font-mono text-xs text-slate-300"><?= e($log['ip_address']) ?></td>
                        <td class="max-w-md px-4 py-3 leading-6 text-slate-300"><?= e($log['details'] ?? '') ?></td>
                        <td class="max-w-sm px-4 py-3 font-mono text-xs leading-5 text-slate-500"><?= e($log['user_agent'] ?? '') ?></td>
                        <td class="px-4 py-3 whitespace-nowrap text-slate-400"><?= e($log['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="mt-5 flex flex-wrap items-center justify-center gap-2" aria-label="Pagination du journal securite">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php $url = '/admin/security-logs/index.php?' . http_build_query($queryParams + ['page' => $i]); ?>
                <a class="<?= $i === $page ? 'bg-cyan-300 text-slate-950' : 'bg-white/10 text-slate-200 hover:bg-white/20' ?> rounded-full px-4 py-2 text-sm font-bold" href="<?= e($url) ?>"><?= e((string) $i) ?></a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
<?php admin_footer(); ?>
