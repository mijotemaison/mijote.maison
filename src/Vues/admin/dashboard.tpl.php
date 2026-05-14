<?php render_flash(); ?>
<div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
    <div>
        <p class="text-primary fw-bold mb-1">Connecté : <?= e(current_admin_email()) ?></p>
        <h1 class="display-font display-6 fw-bold mb-0">Dashboard sécurité</h1>
    </div>
    <a class="btn btn-primary" href="/admin/recettes/creer">Nouvelle recette</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-warning rounded-4"><?= e($error) ?></div>
<?php else: ?>
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3"><div class="admin-card p-4"><p class="text-muted mb-1">Recettes</p><p class="display-5 fw-bold mb-0"><?= e($recipeCount) ?></p></div></div>
        <div class="col-sm-6 col-xl-3"><div class="admin-card p-4"><p class="text-muted mb-1">Administrateurs</p><p class="display-5 fw-bold mb-0"><?= e($adminCount) ?></p></div></div>
        <div class="col-sm-6 col-xl-3"><div class="admin-card p-4"><p class="text-muted mb-1">Commentaires en attente</p><p class="display-5 fw-bold mb-0"><?= e($pendingCommentCount) ?></p></div></div>
        <div class="col-sm-6 col-xl-3"><div class="admin-card p-4"><p class="text-muted mb-1">Protections</p><p class="fw-bold text-primary mb-0">CSRF · CSP · PDO · Upload</p></div></div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <section class="admin-card p-4 h-100">
                <h2 class="fs-4 fw-bold">Dernières recettes</h2>
                <div class="vstack gap-3 mt-3">
                    <?php foreach ($latestRecipes as $recipe): ?>
                        <div class="border rounded-4 bg-light p-3">
                            <p class="fw-bold mb-1"><?= e($recipe['title']) ?></p>
                            <p class="small text-muted mb-0"><?= e($recipe['created_at']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="admin-card p-4 h-100">
                <h2 class="fs-4 fw-bold">Tentatives échouées récentes</h2>
                <div class="vstack gap-3 mt-3">
                    <?php if (!$latestFailures): ?>
                        <p class="text-muted mb-0">Aucune tentative suspecte récente.</p>
                    <?php endif; ?>
                    <?php foreach ($latestFailures as $attempt): ?>
                        <div class="border rounded-4 bg-light p-3 small">
                            <p class="fw-bold mb-1"><?= e($attempt['email'] ?: 'email vide') ?> · <?= e($attempt['ip_address']) ?></p>
                            <p class="text-muted mb-0"><?= e($attempt['created_at']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
        <div class="col-12">
            <section class="admin-card p-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                    <h2 class="fs-4 fw-bold mb-0">Journal sécurité récent</h2>
                    <a class="fw-bold" href="/admin/journal-securite">Voir tout le journal</a>
                </div>
                <div class="row g-3 mt-2">
                    <?php if (!$latestSecurityLogs): ?>
                        <p class="text-muted mb-0">Aucun événement sécurité enregistré.</p>
                    <?php endif; ?>
                    <?php foreach ($latestSecurityLogs as $log): ?>
                        <div class="col-lg-6">
                            <div class="border rounded-4 bg-light p-3 small h-100">
                                <p class="fw-bold mb-1"><?= e($log['event_type']) ?> · <?= e($log['actor_email'] ?: 'visiteur') ?></p>
                                <p class="text-muted mb-2"><?= e($log['details'] ?? '') ?></p>
                                <p class="text-muted small mb-0"><?= e($log['ip_address']) ?> · <?= e($log['created_at']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </div>
<?php endif; ?>
