<?php render_flash(); ?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="display-font display-6 fw-bold mb-1">Gestion des recettes</h1>
        <p class="text-muted mb-0">Création, modification et suppression protégées par CSRF.</p>
    </div>
    <a class="btn btn-primary" href="/admin/recettes/creer">Créer</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-warning rounded-4"><?= e($error) ?></div>
<?php else: ?>
    <div class="admin-card p-3 mb-3" data-table-toolbar="recipes">
        <div class="row g-3 align-items-center">
            <div class="col-lg">
                <label class="visually-hidden">Rechercher une recette</label>
                <input class="form-control" type="search" placeholder="Rechercher (titre, slug)..." data-table-search>
            </div>
            <div class="col-lg-auto d-flex align-items-center gap-2 small fw-bold text-muted">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-table-prev aria-label="Page précédente">‹ Précédent</button>
                <span data-table-indicator>—</span>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-table-next aria-label="Page suivante">Suivant ›</button>
            </div>
        </div>
    </div>
    <div class="table-responsive admin-table">
        <table class="table table-striped align-middle mb-0" data-table="recipes" data-page-size="10">
            <thead>
                <tr><th>Titre</th><th>Catégorie</th><th>Statut</th><th>Mis à jour</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($recipes as $recipe): ?>
                    <?php
                    $statusClass = ($recipe['status'] ?? '') === 'published' ? 'text-bg-success' : (($recipe['status'] ?? '') === 'archived' ? 'text-bg-secondary' : 'text-bg-warning');
                    ?>
                    <tr data-search="<?= e($recipe['title'] . ' ' . $recipe['slug'] . ' ' . ($recipe['category'] ?? '') . ' ' . ($recipe['status'] ?? '')) ?>">
                        <td class="fw-bold"><?= e($recipe['title']) ?></td>
                        <td><?= e(recipe_category_label($recipe['category'] ?? null)) ?></td>
                        <td><span class="badge rounded-pill <?= e($statusClass) ?>"><?= e(recipe_statuses()[$recipe['status'] ?? 'draft'] ?? 'Brouillon') ?></span></td>
                        <td class="text-muted small"><?= e($recipe['updated_at']) ?></td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-outline-secondary btn-sm" href="/admin/recettes/<?= e($recipe['id']) ?>/apercu">Aperçu</a>
                                <a class="btn btn-outline-secondary btn-sm" href="/admin/recettes/<?= e($recipe['id']) ?>/modifier">Modifier</a>
                                <form method="post" action="/admin/recettes/<?= e($recipe['id']) ?>/dupliquer">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-outline-secondary btn-sm" type="submit">Dupliquer</button>
                                </form>
                                <form method="post" action="/admin/recettes/<?= e($recipe['id']) ?>/supprimer" data-confirm="Êtes-vous sûr de vouloir supprimer définitivement la recette « <?= e($recipe['title']) ?> » ? Cette action est irréversible.">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-danger btn-sm" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-none p-4 text-center text-muted" data-table-empty>Aucune recette ne correspond à votre recherche.</div>
    </div>
<?php endif; ?>
