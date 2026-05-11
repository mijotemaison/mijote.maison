<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';

require_admin();

if (!is_post()) {
    flash('error', 'Duplication refusee : methode POST obligatoire.');
    redirect('/admin/recipes/index.php');
}

require_valid_csrf();

$pdo = db();
$repo = new RecipeRepository($pdo);
$newId = $repo->duplicateAsDraft((int) ($_POST['id'] ?? 0));

if (!$newId) {
    flash('error', 'Recette introuvable.');
    redirect('/admin/recipes/index.php');
}

record_security_event($pdo, 'recipe_duplicated', 'Recette dupliquee en brouillon depuis id #' . (int) ($_POST['id'] ?? 0) . ' vers id #' . $newId . '.', current_admin_email());
flash('success', 'Recette dupliquee en brouillon. Vous pouvez la modifier avant publication.');
redirect('/admin/recipes/edit.php?id=' . $newId);
