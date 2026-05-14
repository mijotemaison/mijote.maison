<?php render_flash(); ?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="display-font display-6 fw-bold mb-1">Gestion des administrateurs</h1>
        <p class="text-muted mb-0">Aucun hash de mot de passe n'est affiché.</p>
    </div>
    <a class="btn btn-primary" href="/admin/administrateurs/creer">Ajouter</a>
</div>
<?php if ($error): ?>
    <div class="alert alert-warning rounded-4"><?= e($error) ?></div>
<?php else: ?>
    <div class="admin-card p-3 mb-3" data-table-toolbar="admins">
        <div class="row g-3 align-items-center">
            <div class="col-lg">
                <label class="visually-hidden">Rechercher un administrateur</label>
                <input class="form-control" type="search" placeholder="Rechercher (nom, email)..." data-table-search>
            </div>
            <div class="col-lg-auto d-flex align-items-center gap-2 small fw-bold text-muted">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-table-prev aria-label="Page précédente">‹ Précédent</button>
                <span data-table-indicator>—</span>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-table-next aria-label="Page suivante">Suivant ›</button>
            </div>
        </div>
    </div>
    <div class="table-responsive admin-table">
        <table class="table table-striped align-middle mb-0" data-table="admins" data-page-size="10">
            <thead>
                <tr><th>Nom</th><th>Email</th><th>Création</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr data-search="<?= e($admin['username'] . ' ' . $admin['email']) ?>">
                        <td class="fw-bold"><?= e($admin['username']) ?></td>
                        <td><?= e($admin['email']) ?></td>
                        <td class="text-muted small"><?= e($admin['created_at']) ?></td>
                        <td>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <a class="btn btn-outline-secondary btn-sm" href="/admin/administrateurs/<?= e($admin['id']) ?>/modifier">Modifier</a>
                                <?php if ((int) $admin['id'] === $currentAdminId): ?>
                                    <span class="badge rounded-pill text-bg-success">● Vous</span>
                                <?php else: ?>
                                    <form method="post" action="/admin/administrateurs/<?= e($admin['id']) ?>/supprimer" data-confirm="Êtes-vous sûr de vouloir supprimer définitivement l'administrateur « <?= e($admin['username']) ?> » (<?= e($admin['email']) ?>) ? Cette action est irréversible.">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-danger btn-sm" type="submit">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-none p-4 text-center text-muted" data-table-empty>Aucun administrateur ne correspond à votre recherche.</div>
    </div>
<?php endif; ?>
