<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Recipe;
use App\Model\RecipeInteraction;
use Throwable;

final class SiteController extends AbstractController
{
    public function home(): void
    {
        $recipes = [];
        $popularRecipes = [];
        $ratingSummaries = [];
        $totalRecipes = 0;
        $dbError = null;

        try {
            $pdo = \db();
            $recipeModel = new Recipe($pdo);
            $interactionModel = new RecipeInteraction($pdo);
            $recipes = $recipeModel->latest(6);
            $popularRecipes = $recipeModel->popular(4);
            $totalRecipes = $recipeModel->countPublished();
            $ratingSummaries = $interactionModel->ratingSummariesForRecipeIds(array_merge(
                array_column($recipes, 'id'),
                array_column($popularRecipes, 'id')
            ));
        } catch (Throwable) {
            $dbError = 'Impossible de charger les recettes pour le moment.';
        }

        \public_header('Accueil');
        $this->render('home', compact('recipes', 'popularRecipes', 'ratingSummaries', 'totalRecipes', 'dbError'));
    }

    public function about(): void
    {
        \public_header('À propos');
        $this->render('about');
    }

    public function presentation(): void
    {
        $this->render('presentation');
    }

    public function conformite(): void
    {
        $this->render('conformite');
    }

    public function stack(): void
    {
        $this->render('stack');
    }

    public function legalNotice(): void
    {
        \public_header('Mentions légales');
        $this->render('legal_notice');
        \public_footer();
    }

    public function privacyPolicy(): void
    {
        \public_header('Politique de confidentialité');
        $this->render('privacy_policy');
        \public_footer();
    }
}
