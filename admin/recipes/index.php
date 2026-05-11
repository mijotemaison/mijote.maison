<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';

require_admin();

$recipes = [];
$error = null;

try {
    $recipes = (new RecipeRepository(db()))->all();
} catch (Throwable $exception) {
    $error = 'Base de donnees indisponible.';
}

admin_header('Recettes');
?>
<?php render_flash(); ?>
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white">Gestion des recettes</h1>
        <p class="mt-2 text-slate-400">Creation, modification et suppression protegees par CSRF.</p>
    </div>
    <a class="btn-primary" href="/admin/recipes/create.php">Creer</a>
</div>

<?php if ($error): ?>
    <div class="panel-card p-5 text-amber-100"><?= e($error) ?></div>
<?php else: ?>
    <div class="mb-4 flex flex-wrap items-center gap-3 rounded-lg border border-white/10 bg-slate-950/40 p-3" data-table-toolbar="recipes">
        <label class="relative flex-1 min-w-[220px]">
            <span class="sr-only">Rechercher une recette</span>
            <input class="field" type="search" placeholder="Rechercher (titre, slug)..." data-table-search>
        </label>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
            <button type="button" class="btn-secondary !px-4 !py-2 !text-xs" data-table-prev aria-label="Page précédente">‹ Précédent</button>
            <span data-table-indicator class="tabular px-2 text-slate-300">—</span>
            <button type="button" class="btn-secondary !px-4 !py-2 !text-xs" data-table-next aria-label="Page suivante">Suivant ›</button>
        </div>
    </div>
    <div class="overflow-hidden rounded-lg border border-white/10">
        <table class="w-full min-w-[760px] divide-y divide-white/10 text-left text-sm" data-table="recipes" data-page-size="10">
            <thead class="bg-white/5 text-slate-300">
                <tr><th class="px-4 py-3">Titre</th><th class="px-4 py-3">Categorie</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3">Mis a jour</th><th class="px-4 py-3">Actions</th></tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php foreach ($recipes as $recipe): ?>
                    <tr class="bg-slate-950/40" data-search="<?= e($recipe['title'] . ' ' . $recipe['slug'] . ' ' . ($recipe['category'] ?? '') . ' ' . ($recipe['status'] ?? '')) ?>">
                        <td class="px-4 py-3 font-medium text-white"><?= e($recipe['title']) ?></td>
                        <td class="px-4 py-3 text-slate-400"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-3 py-1 text-xs font-bold <?= ($recipe['status'] ?? '') === 'published' ? 'bg-emerald-500/15 text-emerald-200' : (($recipe['status'] ?? '') === 'archived' ? 'bg-slate-500/20 text-slate-300' : 'bg-amber-500/15 text-amber-200') ?>">
                                <?= e(recipe_statuses()[$recipe['status'] ?? 'draft'] ?? 'Brouillon') ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-400"><?= e($recipe['updated_at']) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a class="btn-secondary" href="/admin/recipes/edit.php?id=<?= e($recipe['id']) ?>">Modifier</a>
                                <form method="post" action="/admin/recipes/delete.php" data-confirm="Êtes-vous sûr de vouloir supprimer définitivement la recette « <?= e($recipe['title']) ?> » ? Cette action est irréversible.">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($recipe['id']) ?>">
                                    <button class="btn-danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="hidden p-6 text-center text-sm text-slate-400" data-table-empty>Aucune recette ne correspond à votre recherche.</div>
    </div>
<?php endif; ?>
<?php admin_footer(); ?>
