<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeInteractionRepository.php';

require_admin();

$pdo = db();
$repo = new RecipeInteractionRepository($pdo);

if (is_post()) {
    require_valid_csrf();
    $id = (int) ($_POST['id'] ?? 0);
    $action = (string) ($_POST['action'] ?? '');

    try {
        if ($action === 'approve') {
            $repo->updateCommentStatus($id, 'approved');
            record_security_event($pdo, 'comment_approved', 'Commentaire #' . $id . ' approuve.', current_admin_email());
            flash('success', 'Commentaire approuve.');
        } elseif ($action === 'reject') {
            $repo->updateCommentStatus($id, 'rejected');
            record_security_event($pdo, 'comment_rejected', 'Commentaire #' . $id . ' refuse.', current_admin_email());
            flash('success', 'Commentaire refuse.');
        } elseif ($action === 'delete') {
            $repo->deleteComment($id);
            record_security_event($pdo, 'comment_deleted', 'Commentaire #' . $id . ' supprime.', current_admin_email());
            flash('success', 'Commentaire supprime.');
        } else {
            flash('error', 'Action inconnue.');
        }
    } catch (Throwable $exception) {
        flash('error', 'Action impossible sur ce commentaire.');
    }

    redirect('/admin/comments/index.php');
}

$comments = [];
$error = null;

try {
    $comments = $repo->allComments();
} catch (Throwable $exception) {
    $error = 'Base de donnees indisponible.';
}

admin_header('Commentaires');
?>
<?php render_flash(); ?>
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white">Moderation des commentaires</h1>
        <p class="mt-2 text-slate-400">Les commentaires publics restent en attente tant qu'un administrateur ne les approuve pas.</p>
    </div>
    <a class="btn-secondary" href="/admin/recipes/index.php">Retour recettes</a>
</div>

<?php if ($error): ?>
    <div class="panel-card p-5 text-amber-100"><?= e($error) ?></div>
<?php else: ?>
    <div class="mb-4 flex flex-wrap items-center gap-3 rounded-lg border border-white/10 bg-slate-950/40 p-3" data-table-toolbar="comments">
        <label class="relative flex-1 min-w-[220px]">
            <span class="sr-only">Rechercher un commentaire</span>
            <input class="field" type="search" placeholder="Rechercher (recette, auteur, contenu, statut)..." data-table-search>
        </label>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
            <button type="button" class="btn-secondary !px-4 !py-2 !text-xs" data-table-prev aria-label="Page précédente">‹ Précédent</button>
            <span data-table-indicator class="tabular px-2 text-slate-300">—</span>
            <button type="button" class="btn-secondary !px-4 !py-2 !text-xs" data-table-next aria-label="Page suivante">Suivant ›</button>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-white/10">
        <table class="w-full min-w-[980px] divide-y divide-white/10 text-left text-sm" data-table="comments" data-page-size="10">
            <thead class="bg-white/5 text-slate-300">
                <tr>
                    <th class="px-4 py-3">Recette</th>
                    <th class="px-4 py-3">Auteur</th>
                    <th class="px-4 py-3">Commentaire</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php foreach ($comments as $comment): ?>
                    <?php
                    $status = (string) $comment['status'];
                    $statusLabel = ['pending' => 'En attente', 'approved' => 'Approuvé', 'rejected' => 'Refusé'][$status] ?? 'Inconnu';
                    $statusClass = $status === 'approved'
                        ? 'bg-emerald-500/15 text-emerald-200'
                        : ($status === 'rejected' ? 'bg-rose-500/15 text-rose-200' : 'bg-amber-500/15 text-amber-200');
                    ?>
                    <tr class="bg-slate-950/40" data-search="<?= e($comment['recipe_title'] . ' ' . $comment['author_name'] . ' ' . $comment['content'] . ' ' . $statusLabel) ?>">
                        <td class="px-4 py-3">
                            <a class="font-medium text-cyan-100 hover:text-white" href="<?= e(recipe_url((string) $comment['recipe_slug'])) ?>" target="_blank" rel="noopener"><?= e($comment['recipe_title']) ?></a>
                        </td>
                        <td class="px-4 py-3 text-white"><?= e($comment['author_name']) ?></td>
                        <td class="max-w-md px-4 py-3 leading-6 text-slate-300"><?= e($comment['content']) ?></td>
                        <td class="px-4 py-3"><span class="rounded-full px-3 py-1 text-xs font-bold <?= e($statusClass) ?>"><?= e($statusLabel) ?></span></td>
                        <td class="px-4 py-3 text-slate-400"><?= e($comment['created_at']) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <?php if ($status !== 'approved'): ?>
                                    <form method="post" action="/admin/comments/index.php">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($comment['id']) ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button class="btn-secondary" type="submit">Approuver</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($status !== 'rejected'): ?>
                                    <form method="post" action="/admin/comments/index.php">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($comment['id']) ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button class="btn-secondary" type="submit">Refuser</button>
                                    </form>
                                <?php endif; ?>
                                <form method="post" action="/admin/comments/index.php" data-confirm="Supprimer définitivement ce commentaire ?">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($comment['id']) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="btn-danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="hidden p-6 text-center text-sm text-slate-400" data-table-empty>Aucun commentaire ne correspond à votre recherche.</div>
    </div>
<?php endif; ?>
<?php admin_footer(); ?>
