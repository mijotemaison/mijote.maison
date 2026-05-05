<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';
require_once BASE_PATH . '/app/security/upload.php';

require_admin();

if (!is_post()) {
    flash('error', 'Suppression refusee : methode POST obligatoire.');
    redirect('/admin/recipes/index.php');
}

require_valid_csrf();

$id = (int) ($_POST['id'] ?? 0);
$repo = new RecipeRepository(db());
$recipe = $repo->find($id);

if (!$recipe) {
    flash('error', 'Recette introuvable.');
    redirect('/admin/recipes/index.php');
}

$repo->delete($id);
delete_recipe_image($recipe['image_path']);
flash('success', 'Recette supprimee.');
redirect('/admin/recipes/index.php');
