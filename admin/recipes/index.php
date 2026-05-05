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
    <div class="overflow-hidden rounded-lg border border-white/10">
        <table class="w-full min-w-[760px] divide-y divide-white/10 text-left text-sm">
            <thead class="bg-white/5 text-slate-300">
                <tr><th class="px-4 py-3">Titre</th><th class="px-4 py-3">Slug</th><th class="px-4 py-3">Mis a jour</th><th class="px-4 py-3">Actions</th></tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php foreach ($recipes as $recipe): ?>
                    <tr class="bg-slate-950/40">
                        <td class="px-4 py-3 font-medium text-white"><?= e($recipe['title']) ?></td>
                        <td class="px-4 py-3 text-slate-400"><?= e($recipe['slug']) ?></td>
                        <td class="px-4 py-3 text-slate-400"><?= e($recipe['updated_at']) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a class="btn-secondary" href="/admin/recipes/edit.php?id=<?= e($recipe['id']) ?>">Modifier</a>
                                <form method="post" action="/admin/recipes/delete.php" onsubmit="return confirm('Supprimer cette recette ?');">
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
    </div>
<?php endif; ?>
<?php admin_footer(); ?>
