<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use Throwable;

require_once BASE_PATH . '/app/repositories/RecipeRepository.php';
require_once BASE_PATH . '/app/security/upload.php';
require_once BASE_PATH . '/app/validation/recipe_validation.php';

final class RecipeAdminController extends AbstractController
{
    public function index(): void
    {
        \require_admin();

        $recipes = [];
        $error = null;

        try {
            $recipes = (new \RecipeRepository(\db()))->all();
        } catch (Throwable) {
            $error = 'Base de donnees indisponible.';
        }

        \admin_header('Recettes');
        $this->render('admin/recipes/index', compact('recipes', 'error'));
        \admin_footer();
    }

    public function create(): void
    {
        \require_admin();

        $errors = [];
        $recipe = null;

        if (\is_post()) {
            \require_valid_csrf();
            $data = \clean_recipe_input($_POST);
            $errors = \validate_recipe_input($data);
            $upload = \upload_recipe_image($_FILES['image'] ?? []);
            if ($upload['error']) {
                $errors['image'] = $upload['error'];
            }

            if (!$errors) {
                try {
                    $repo = new \RecipeRepository(\db());
                    $data['slug'] = $repo->uniqueSlug(\make_slug($data['title']));
                    $data['image_path'] = $upload['path'];
                    $repo->create($data);
                    \flash('success', 'Recette creee avec succes.');
                    \redirect('/admin/recettes');
                } catch (Throwable) {
                    \delete_recipe_image($upload['path']);
                    $errors['global'] = 'Creation impossible. Verifiez les donnees et la base.';
                }
            }
        }

        \admin_header('Creer une recette');
        $this->render('admin/recipes/create', compact('errors', 'recipe'));
        \admin_footer();
    }

    public function edit(string|int $id): void
    {
        \require_admin();

        $repo = new \RecipeRepository(\db());
        $recipe = $repo->find((int) $id);
        if (!$recipe) {
            \flash('error', 'Recette introuvable.');
            \redirect('/admin/recettes');
        }

        $errors = [];

        if (\is_post()) {
            \require_valid_csrf();
            $data = \clean_recipe_input($_POST);
            $errors = \validate_recipe_input($data);
            $upload = \upload_recipe_image($_FILES['image'] ?? []);
            if ($upload['error']) {
                $errors['image'] = $upload['error'];
            }

            if (!$errors) {
                $newImage = $upload['path'] ?: $recipe['image_path'];
                try {
                    $data['slug'] = $repo->uniqueSlug(\make_slug($data['title']), (int) $recipe['id']);
                    $data['image_path'] = $newImage;
                    $repo->update((int) $recipe['id'], $data);
                    if ($upload['path']) {
                        \delete_recipe_image($recipe['image_path']);
                    }
                    \flash('success', 'Recette modifiee avec succes.');
                    \redirect('/admin/recettes');
                } catch (Throwable) {
                    \delete_recipe_image($upload['path']);
                    $errors['global'] = 'Modification impossible.';
                }
            }
        }

        \admin_header('Modifier une recette');
        $this->render('admin/recipes/edit', compact('errors', 'recipe'));
        \admin_footer();
    }

    public function preview(string|int $id): void
    {
        \require_admin();

        $repo = new \RecipeRepository(\db());
        $recipe = $repo->find((int) $id);

        if (!$recipe) {
            \flash('error', 'Recette introuvable.');
            \redirect('/admin/recettes');
        }

        $meta = \recipe_public_meta($recipe['slug'] ?? null);

        \admin_header('Apercu recette');
        $this->render('admin/recipes/preview', compact('recipe', 'meta'));
        \admin_footer();
    }

    public function delete(string|int $id): void
    {
        \require_admin();
        \require_valid_csrf();

        $pdo = \db();
        $repo = new \RecipeRepository($pdo);
        $recipe = $repo->find((int) $id);

        if (!$recipe) {
            \flash('error', 'Recette introuvable.');
            \redirect('/admin/recettes');
        }

        $repo->delete((int) $id);
        if ($repo->imagePathUsageCount($recipe['image_path']) === 0) {
            \delete_recipe_image($recipe['image_path']);
        }
        \record_security_event($pdo, 'recipe_deleted', 'Recette #' . (int) $id . ' supprimee : ' . (string) $recipe['title'], \current_admin_email());
        \flash('success', 'Recette supprimee.');
        \redirect('/admin/recettes');
    }

    public function duplicate(string|int $id): void
    {
        \require_admin();
        \require_valid_csrf();

        $pdo = \db();
        $repo = new \RecipeRepository($pdo);
        $newId = $repo->duplicateAsDraft((int) $id);

        if (!$newId) {
            \flash('error', 'Recette introuvable.');
            \redirect('/admin/recettes');
        }

        \record_security_event($pdo, 'recipe_duplicated', 'Recette dupliquee en brouillon depuis id #' . (int) $id . ' vers id #' . $newId . '.', \current_admin_email());
        \flash('success', 'Recette dupliquee en brouillon. Vous pouvez la modifier avant publication.');
        \redirect('/admin/recettes/' . $newId . '/modifier');
    }
}
