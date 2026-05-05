<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';
require_once BASE_PATH . '/app/security/upload.php';
require_once BASE_PATH . '/app/validation/recipe_validation.php';

require_admin();

$repo = new RecipeRepository(db());
$recipe = $repo->find((int) ($_GET['id'] ?? $_POST['id'] ?? 0));
if (!$recipe) {
    flash('error', 'Recette introuvable.');
    redirect('/admin/recipes/index.php');
}

$errors = [];

if (is_post()) {
    require_valid_csrf();
    $data = clean_recipe_input($_POST);
    $errors = validate_recipe_input($data);
    $upload = upload_recipe_image($_FILES['image'] ?? []);
    if ($upload['error']) {
        $errors['image'] = $upload['error'];
    }

    if (!$errors) {
        $newImage = $upload['path'] ?: $recipe['image_path'];
        try {
            $data['slug'] = $repo->uniqueSlug(make_slug($data['title']), (int) $recipe['id']);
            $data['image_path'] = $newImage;
            $repo->update((int) $recipe['id'], $data);
            if ($upload['path']) {
                delete_recipe_image($recipe['image_path']);
            }
            flash('success', 'Recette modifiee avec succes.');
            redirect('/admin/recipes/index.php');
        } catch (Throwable $exception) {
            delete_recipe_image($upload['path']);
            $errors['global'] = 'Modification impossible.';
        }
    }
}

admin_header('Modifier une recette');
?>
<?php render_flash(); ?>
<h1 class="mb-6 text-3xl font-bold text-white">Modifier une recette</h1>
<?php if (isset($errors['global'])): ?><div class="mb-4 rounded-lg border border-rose-400/40 bg-rose-500/10 p-4 text-rose-100"><?= e($errors['global']) ?></div><?php endif; ?>
<form class="panel-card grid gap-5 p-6" method="post" enctype="multipart/form-data" action="/admin/recipes/edit.php?id=<?= e($recipe['id']) ?>" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= e($recipe['id']) ?>">
    <?php require __DIR__ . '/form.php'; ?>
    <?php if ($recipe['image_path']): ?>
        <p class="text-sm text-slate-400">Image actuelle : <?= e($recipe['image_path']) ?></p>
    <?php endif; ?>
    <div class="flex gap-3">
        <button class="btn-primary" type="submit">Enregistrer</button>
        <a class="btn-secondary" href="/admin/recipes/index.php">Annuler</a>
    </div>
</form>
<?php admin_footer(); ?>
