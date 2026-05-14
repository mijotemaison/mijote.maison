<?php render_flash(); ?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <p class="text-primary fw-bold mb-1">Audit applicatif</p>
        <h1 class="display-font display-6 fw-bold mb-1">Journal sécurité</h1>
        <p class="text-muted mb-0">Cette page centralise les événements importants : connexions, blocages brute force, modération, duplication et suppressions de recettes.</p>
    </div>
    <a class="btn btn-outline-secondary" href="/admin/dashboard">Retour dashboard</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-warning rounded-4"><?= e($error) ?></div>
<?php else: ?>
    <section class="admin-card p-4 mb-4">
        <form class="row g-3 align-items-end" method="get" action="/admin/journal-securite">
            <div class="col-lg-3">
                <label class="form-label fw-bold">Type d'événement</label>
                <select class="form-select" name="event_type">
                    <option value="">Tous les événements</option>
                    <?php foreach ($eventTypes as $eventType): ?>
                        <option value="<?= e($eventType) ?>" <?= $filters['event_type'] === $eventType ? 'selected' : '' ?>><?= e($eventType) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3">
                <label class="form-label fw-bold">Recherche</label>
                <input class="form-control" type="search" name="q" value="<?= e($filters['q']) ?>" placeholder="Email, IP, navigateur, détails...">
            </div>
            <div class="col-lg-2">
                <label class="form-label fw-bold">Depuis</label>
                <input class="form-control" type="date" name="date_from" value="<?= e($filters['date_from']) ?>">
            </div>
            <div class="col-lg-2">
                <label class="form-label fw-bold">Jusqu'au</label>
                <input class="form-control" type="date" name="date_to" value="<?= e($filters['date_to']) ?>">
            </div>
            <div class="col-lg-2 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Filtrer</button>
                <a class="btn btn-outline-secondary" href="/admin/journal-securite">Reset</a>
            </div>
        </form>
    </section>

    <div class="admin-card p-3 mb-3 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <p class="small text-muted mb-0">
            <?= e((string) $totalLogs) ?> événement<?= $totalLogs > 1 ? 's' : '' ?> trouvé<?= $totalLogs > 1 ? 's' : '' ?>.
            Page <?= e((string) $page) ?> / <?= e((string) $totalPages) ?>.
        </p>
        <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="<?= e('/admin/journal-securite?' . http_build_query($queryParams + ['export' => 'csv'])) ?>">Exporter CSV</a>
            <form method="post" action="/admin/journal-securite" data-confirm="Nettoyer les logs et tentatives de connexion de plus de 90 jours ?">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="cleanup">
                <input type="hidden" name="days" value="90">
                <button class="btn btn-outline-secondary btn-sm" type="submit">Nettoyer +90 jours</button>
            </form>
        </div>
    </div>

    <div class="table-responsive admin-table">
        <table class="table table-striped align-top mb-0">
            <thead>
                <tr>
                    <th>Événement</th>
                    <th>Acteur</th>
                    <th>IP</th>
                    <th>Détails</th>
                    <th>User agent</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$logs): ?>
                    <tr>
                        <td class="text-center text-muted py-4" colspan="6">Aucun événement ne correspond aux filtres.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><span class="badge rounded-pill text-bg-primary"><?= e($log['event_type']) ?></span></td>
                        <td><?= e($log['actor_email'] ?: 'visiteur') ?></td>
                        <td class="font-monospace small"><?= e($log['ip_address']) ?></td>
                        <td class="text-muted table-cell-details"><?= e($log['details'] ?? '') ?></td>
                        <td class="font-monospace small text-muted table-cell-agent"><?= e($log['user_agent'] ?? '') ?></td>
                        <td class="text-muted small text-nowrap"><?= e($log['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="mt-4 d-flex flex-wrap align-items-center justify-content-center gap-2" aria-label="Pagination du journal sécurité">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php $url = '/admin/journal-securite?' . http_build_query($queryParams + ['page' => $i]); ?>
                <a class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-outline-secondary' ?>" href="<?= e($url) ?>"><?= e((string) $i) ?></a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>
