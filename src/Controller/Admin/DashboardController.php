<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Repository\AdminRepository;
use App\Repository\LoginAttemptRepository;
use App\Repository\RecipeInteractionRepository;
use App\Repository\RecipeRepository;
use App\Repository\SecurityLogRepository;
use Throwable;

final class DashboardController extends AbstractController
{
    public function index(): void
    {
        \require_admin();

        $recipeCount = 0;
        $adminCount = 0;
        $pendingCommentCount = 0;
        $latestRecipes = [];
        $latestFailures = [];
        $latestSecurityLogs = [];
        $error = null;

        try {
            $pdo = \db();
            $recipeRepo = new RecipeRepository($pdo);
            $adminRepo = new AdminRepository($pdo);
            $attemptRepo = new LoginAttemptRepository($pdo);
            $interactionRepo = new RecipeInteractionRepository($pdo);
            $securityLogRepo = new SecurityLogRepository($pdo);
            $recipeCount = $recipeRepo->count();
            $adminCount = $adminRepo->count();
            $pendingCommentCount = $interactionRepo->countPendingComments();
            $latestRecipes = $recipeRepo->latest(5);
            $latestFailures = $attemptRepo->latestFailures(6);
            $latestSecurityLogs = $securityLogRepo->latest(6);
        } catch (Throwable) {
            $error = 'Base de donnees indisponible.';
        }

        \admin_header('Dashboard');
        $this->render('admin/dashboard', compact(
            'recipeCount',
            'adminCount',
            'pendingCommentCount',
            'latestRecipes',
            'latestFailures',
            'latestSecurityLogs',
            'error'
        ));
        \admin_footer();
    }
}
