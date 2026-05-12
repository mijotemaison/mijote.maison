<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Recipe;
use App\Model\RecipeInteraction;
use Throwable;

final class RecipeController extends AbstractController
{
    public function index(): void
    {
        $recipes = [];
        $ratingSummaries = [];
        $dbError = null;
        $query = trim((string) ($_GET['q'] ?? ''));
        $category = (string) ($_GET['category'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 6;
        $totalRecipes = 0;
        $totalPages = 1;

        try {
            $pdo = \db();
            $recipeModel = new Recipe($pdo);
            $interactionModel = new RecipeInteraction($pdo);
            $totalRecipes = $recipeModel->countPublishedWithFilters($query, $category);
            $totalPages = max(1, (int) ceil($totalRecipes / $perPage));
            $page = min($page, $totalPages);
            $recipes = $recipeModel->published($perPage, ($page - 1) * $perPage, $query, $category);
            $ratingSummaries = $interactionModel->ratingSummariesForRecipeIds(array_column($recipes, 'id'));
        } catch (Throwable) {
            $dbError = 'Impossible de charger les recettes pour le moment.';
        }

        $baseParams = [];
        if ($query !== '') {
            $baseParams['q'] = $query;
        }
        if ($category !== '' && array_key_exists($category, \recipe_categories(), true)) {
            $baseParams['category'] = $category;
        }

        \public_header('Recettes');
        $this->render('recipes', compact(
            'recipes',
            'ratingSummaries',
            'dbError',
            'query',
            'category',
            'page',
            'perPage',
            'totalRecipes',
            'totalPages',
            'baseParams'
        ));
    }

    public function show(?string $slug = null): void
    {
        $recipe = null;
        $error = null;
        $ratingSummary = ['average' => 0.0, 'count' => 0];
        $userRating = null;
        $comments = [];

        try {
            $pdo = \db();
            $recipeModel = new Recipe($pdo);
            $interactionModel = new RecipeInteraction($pdo);
            $id = isset($_GET['id']) ? (int) $_GET['id'] : null;
            $recipe = $recipeModel->find($id, $slug ?? ($_GET['slug'] ?? null));

            if ($recipe && \is_post()) {
                \require_valid_csrf();
                $this->handlePublicRecipeAction($pdo, $interactionModel, $recipe);
            }

            if ($recipe) {
                if (!\is_post()) {
                    $recipeModel->incrementViewCount((int) $recipe['id']);
                    $recipe['view_count'] = (int) ($recipe['view_count'] ?? 0) + 1;
                }
                $ratingSummary = $interactionModel->ratingSummary((int) $recipe['id']);
                $userRating = $interactionModel->userRating((int) $recipe['id'], \public_actor_hash());
                $comments = $interactionModel->approvedComments((int) $recipe['id']);
            }
        } catch (Throwable) {
            $error = 'Impossible de charger la recette pour le moment.';
        }

        $meta = $recipe ? \recipe_public_meta($recipe['slug'] ?? null) : null;
        [$og, $jsonLd] = $this->recipeSeoData($recipe, $meta, $ratingSummary);

        \public_header($recipe['title'] ?? 'Recette', $og);
        if ($jsonLd) {
            echo '<script type="application/ld+json" nonce="' . \e(\csp_nonce()) . '">' . json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
        }
        $this->render('recipe', compact('recipe', 'error', 'ratingSummary', 'userRating', 'comments', 'meta'));
    }

    public function printable(?string $slug = null): void
    {
        $recipe = null;
        $error = null;

        try {
            $pdo = \db();
            $recipeModel = new Recipe($pdo);
            $recipe = $recipeModel->find(null, $slug ?? ($_GET['slug'] ?? null));
        } catch (Throwable) {
            $error = 'Impossible de charger la recette pour le moment.';
        }

        $meta = $recipe ? \recipe_public_meta($recipe['slug'] ?? null) : null;
        $this->render('recipe_print', compact('recipe', 'error', 'meta'));
    }

    private function handlePublicRecipeAction(\PDO $pdo, RecipeInteraction $interactionModel, array $recipe): void
    {
        $action = (string) ($_POST['action'] ?? '');

        if ($action === 'rate') {
            $rating = (int) ($_POST['rating'] ?? 0);
            if ($rating < 1 || $rating > 5) {
                \flash('error', 'La note doit etre comprise entre 1 et 5.');
            } else {
                $interactionModel->rate((int) $recipe['id'], $rating, \public_actor_hash());
                \flash('success', 'Merci pour votre note.');
            }
            \redirect(\recipe_url((string) $recipe['slug']) . '#avis');
        }

        if ($action === 'comment') {
            $authorName = trim((string) ($_POST['author_name'] ?? ''));
            $content = trim((string) ($_POST['content'] ?? ''));
            $honeypot = trim((string) ($_POST['website'] ?? ''));

            if ($honeypot !== '') {
                \redirect(\recipe_url((string) $recipe['slug']) . '#avis');
            }
            if ($authorName === '' || mb_strlen($authorName) > 80) {
                \flash('error', 'Le nom est obligatoire et limite a 80 caracteres.');
            } elseif (mb_strlen($content) < 5 || mb_strlen($content) > 800) {
                \flash('error', 'Le commentaire doit contenir entre 5 et 800 caracteres.');
            } else {
                $interactionModel->createComment((int) $recipe['id'], $authorName, $content, \public_actor_hash());
                \record_security_event($pdo, 'public_comment_pending', 'Commentaire public en attente sur recette #' . (int) $recipe['id'] . '.', $authorName);
                \flash('success', 'Commentaire envoye. Il apparaitra apres validation.');
            }
            \redirect(\recipe_url((string) $recipe['slug']) . '#avis');
        }
    }

    private function recipeSeoData(?array $recipe, ?array $meta, array $ratingSummary): array
    {
        if (!$recipe) {
            return [null, null];
        }

        $og = [
            'type' => 'article',
            'title' => $recipe['title'] . ' — Mijoté Maison',
            'description' => $recipe['short_description'],
            'image' => \recipe_image_url($recipe['image_path']),
        ];
        $ingredients = preg_split('/\r?\n/', (string) ($recipe['ingredients'] ?? ''));
        $ingredients = array_values(array_filter(array_map('trim', $ingredients ?: []), 'strlen'));
        $steps = preg_split('/\r?\n/', (string) ($recipe['preparation_steps'] ?? ''));
        $steps = array_values(array_filter(array_map('trim', $steps ?: []), 'strlen'));
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'Recipe',
            'name' => $recipe['title'],
            'image' => [\recipe_image_url($recipe['image_path'])],
            'description' => $recipe['short_description'],
            'author' => ['@type' => 'Organization', 'name' => 'Mijoté Maison'],
            'recipeCategory' => \recipe_category_label($recipe['category'] ?? null),
            'recipeYield' => $meta['servings'] ?? null,
            'totalTime' => isset($meta['time']) ? 'PT' . preg_replace('/\D/', '', $meta['time']) . 'M' : null,
            'recipeIngredient' => $ingredients,
            'recipeInstructions' => array_map(static fn($step) => ['@type' => 'HowToStep', 'text' => $step], $steps),
        ];

        if (($ratingSummary['count'] ?? 0) > 0) {
            $jsonLd['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string) $ratingSummary['average'],
                'ratingCount' => (string) $ratingSummary['count'],
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        return [$og, array_filter($jsonLd, static fn($value) => $value !== null && $value !== '')];
    }
}
