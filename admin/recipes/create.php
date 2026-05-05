<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';
require_once BASE_PATH . '/app/security/upload.php';
require_once BASE_PATH . '/app/validation/recipe_validation.php';

require_admin();

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
        try {
            $repo = new RecipeRepository(db());
            $data['slug'] = $repo->uniqueSlug(make_slug($data['title']));
            $data['image_path'] = $upload['path'];
            $repo->create($data);
            flash('success', 'Recette creee avec succes.');
            redirect('/admin/recipes/index.php');
        } catch (Throwable $exception) {
            delete_recipe_image($upload['path']);
            $errors['global'] = 'Creation impossible. Verifiez les donnees et la base.';
        }
    }
}

admin_header('Creer une recette');
?>
<?php render_flash(); ?>
<h1 class="mb-6 text-3xl font-bold text-white">Creer une recette</h1>
<?php if (isset($errors['global'])): ?><div class="mb-4 rounded-lg border border-rose-400/40 bg-rose-500/10 p-4 text-rose-100"><?= e($errors['global']) ?></div><?php endif; ?>
<form class="panel-card grid gap-5 p-6" method="post" enctype="multipart/form-data" action="/admin/recipes/create.php" novalidate>
    <?= csrf_field() ?>
    <?php require __DIR__ . '/form.php'; ?>
    <div class="flex gap-3">
        <button class="btn-primary" type="submit">Creer</button>
        <a class="btn-secondary" href="/admin/recipes/index.php">Annuler</a>
    </div>
</form>
<?php admin_footer(); ?>
