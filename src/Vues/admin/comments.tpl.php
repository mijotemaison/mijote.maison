<?php render_flash(); ?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="display-font display-6 fw-bold mb-1">Modération des commentaires</h1>
        <p class="text-muted mb-0">Les commentaires publics restent en attente tant qu'un administrateur ne les approuve pas.</p>
    </div>
    <a class="btn btn-outline-secondary" href="/admin/recettes">Retour recettes</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-warning rounded-4"><?= e($error) ?></div>
<?php else: ?>
    <div class="admin-card p-3 mb-3" data-table-toolbar="comments">
        <div class="row g-3 align-items-center">
            <div class="col-lg">
                <label class="visually-hidden">Rechercher un commentaire</label>
                <input class="form-control" type="search" placeholder="Rechercher (recette, auteur, contenu, statut)..." data-table-search>
            </div>
            <div class="col-lg-auto d-flex align-items-center gap-2 small fw-bold text-muted">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-table-prev aria-label="Page précédente">‹ Précédent</button>
                <span data-table-indicator>—</span>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-table-next aria-label="Page suivante">Suivant ›</button>
            </div>
        </div>
    </div>

    <div class="table-responsive admin-table">
        <table class="table table-striped align-middle mb-0" data-table="comments" data-page-size="10">
            <thead>
                <tr>
                    <th>Recette</th>
                    <th>Auteur</th>
                    <th>Commentaire</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                    <?php
                    $status = (string) $comment['status'];
                    $statusLabel = ['pending' => 'En attente', 'approved' => 'Approuvé', 'rejected' => 'Refusé'][$status] ?? 'Inconnu';
                    $statusClass = $status === 'approved'
                        ? 'text-bg-success'
                        : ($status === 'rejected' ? 'text-bg-danger' : 'text-bg-warning');
                    ?>
                    <tr data-search="<?= e($comment['recipe_title'] . ' ' . $comment['author_name'] . ' ' . $comment['content'] . ' ' . $statusLabel) ?>">
                        <td><a class="fw-bold" href="<?= e(recipe_url((string) $comment['recipe_slug'])) ?>" target="_blank" rel="noopener"><?= e($comment['recipe_title']) ?></a></td>
                        <td><?= e($comment['author_name']) ?></td>
                        <td class="text-muted table-cell-wide"><?= e($comment['content']) ?></td>
                        <td><span class="badge rounded-pill <?= e($statusClass) ?>"><?= e($statusLabel) ?></span></td>
                        <td class="text-muted small"><?= e($comment['created_at']) ?></td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <?php if ($status !== 'approved'): ?>
                                    <form method="post" action="/admin/commentaires">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($comment['id']) ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button class="btn btn-outline-secondary btn-sm" type="submit">Approuver</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($status !== 'rejected'): ?>
                                    <form method="post" action="/admin/commentaires">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($comment['id']) ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button class="btn btn-outline-secondary btn-sm" type="submit">Refuser</button>
                                    </form>
                                <?php endif; ?>
                                <form method="post" action="/admin/commentaires" data-confirm="Supprimer définitivement ce commentaire ?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($comment['id']) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="btn btn-danger btn-sm" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-none p-4 text-center text-muted" data-table-empty>Aucun commentaire ne correspond à votre recherche.</div>
    </div>
<?php endif; ?>
