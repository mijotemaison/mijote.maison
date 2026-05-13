<?php render_flash(); ?>
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-medium text-cyan-200">Audit applicatif</p>
        <h1 class="mt-2 text-3xl font-bold text-white">Journal securite</h1>
        <p class="mt-2 max-w-3xl text-slate-400">Cette page centralise les evenements importants : connexions, blocages brute force, moderation, duplication et suppressions de recettes.</p>
    </div>
    <a class="btn-secondary" href="/admin/dashboard">Retour dashboard</a>
</div>

<?php if ($error): ?>
    <div class="panel-card p-5 text-amber-100"><?= e($error) ?></div>
<?php else: ?>
    <section class="panel-card mb-5 p-5">
        <form class="grid gap-3 lg:grid-cols-[1fr_1fr_.75fr_.75fr_auto]" method="get" action="/admin/journal-securite">
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
                <a class="btn-secondary" href="/admin/journal-securite">Reset</a>
            </div>
        </form>
    </section>

    <div class="mb-4 flex flex-col gap-3 rounded-lg border border-white/10 bg-slate-950/40 p-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-300">
            <?= e((string) $totalLogs) ?> evenement<?= $totalLogs > 1 ? 's' : '' ?> trouve<?= $totalLogs > 1 ? 's' : '' ?>.
            Page <?= e((string) $page) ?> / <?= e((string) $totalPages) ?>.
        </p>
        <div class="flex flex-wrap gap-2">
            <a class="btn-secondary !px-4 !py-2 !text-xs" href="<?= e('/admin/journal-securite?' . http_build_query($queryParams + ['export' => 'csv'])) ?>">Exporter CSV</a>
            <form method="post" action="/admin/journal-securite" data-confirm="Nettoyer les logs et tentatives de connexion de plus de 90 jours ?">
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
                <?php $url = '/admin/journal-securite?' . http_build_query($queryParams + ['page' => $i]); ?>
                <a class="<?= $i === $page ? 'bg-cyan-300 text-slate-950' : 'bg-white/10 text-slate-200 hover:bg-white/20' ?> rounded-full px-4 py-2 text-sm font-bold" href="<?= e($url) ?>"><?= e((string) $i) ?></a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
