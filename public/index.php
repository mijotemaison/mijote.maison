<?php

declare(strict_types=1);

use App\Controller\Admin\AdminUserController;
use App\Controller\Admin\CommentAdminController;
use App\Controller\Admin\DashboardController;
use App\Controller\Admin\RecipeAdminController;
use App\Controller\Admin\SecurityLogAdminController;
use App\Controller\AuthController;
use App\Controller\ErreurController;
use App\Controller\RecipeController;
use App\Controller\SiteController;

require_once __DIR__ . '/../app/bootstrap.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/') ?: '/';
$publicPath = __DIR__ . $path;

if (PHP_SAPI === 'cli-server' && $path !== '/' && $path !== '/index.php' && is_file($publicPath)) {
    return false;
}

$router = new AltoRouter();
$router->setBasePath($_SERVER['BASE_URI'] ?? '');

$router->map('GET', '/', [SiteController::class, 'home'], 'home');
$router->map('GET', '/index.php', [SiteController::class, 'home'], 'legacy_home');
$router->map('GET', '/recettes', [RecipeController::class, 'index'], 'recipes');
$router->map('GET', '/recette/[*:slug]/impression', [RecipeController::class, 'printable'], 'recipe_print');
$router->map('GET|POST', '/recette/[*:slug]', [RecipeController::class, 'show'], 'recipe_show');
$router->map('GET|POST', '/connexion', [AuthController::class, 'login'], 'login');
$router->map('GET', '/deconnexion', [AuthController::class, 'logout'], 'logout');
$router->map('GET', '/a-propos', [SiteController::class, 'about'], 'about');
$router->map('GET', '/presentation', [SiteController::class, 'presentation'], 'presentation');
$router->map('GET', '/conformite', [SiteController::class, 'conformite'], 'conformite');
$router->map('GET', '/stack', [SiteController::class, 'stack'], 'stack');
$router->map('GET', '/mentions-legales', [SiteController::class, 'legalNotice'], 'legal_notice');
$router->map('GET', '/politique-confidentialite', [SiteController::class, 'privacyPolicy'], 'privacy_policy');

$router->map('GET', '/admin', [DashboardController::class, 'index'], 'admin_home');
$router->map('GET', '/admin/dashboard', [DashboardController::class, 'index'], 'admin_dashboard');
$router->map('GET', '/admin/recettes', [RecipeAdminController::class, 'index'], 'admin_recipes');
$router->map('GET|POST', '/admin/recettes/creer', [RecipeAdminController::class, 'create'], 'admin_recipe_create');
$router->map('GET|POST', '/admin/recettes/[i:id]/modifier', [RecipeAdminController::class, 'edit'], 'admin_recipe_edit');
$router->map('GET', '/admin/recettes/[i:id]/apercu', [RecipeAdminController::class, 'preview'], 'admin_recipe_preview');
$router->map('POST', '/admin/recettes/[i:id]/supprimer', [RecipeAdminController::class, 'delete'], 'admin_recipe_delete');
$router->map('POST', '/admin/recettes/[i:id]/dupliquer', [RecipeAdminController::class, 'duplicate'], 'admin_recipe_duplicate');
$router->map('GET', '/admin/administrateurs', [AdminUserController::class, 'index'], 'admin_users');
$router->map('GET|POST', '/admin/administrateurs/creer', [AdminUserController::class, 'create'], 'admin_user_create');
$router->map('GET|POST', '/admin/administrateurs/[i:id]/modifier', [AdminUserController::class, 'edit'], 'admin_user_edit');
$router->map('POST', '/admin/administrateurs/[i:id]/supprimer', [AdminUserController::class, 'delete'], 'admin_user_delete');
$router->map('GET|POST', '/admin/commentaires', [CommentAdminController::class, 'index'], 'admin_comments');
$router->map('GET|POST', '/admin/journal-securite', [SecurityLogAdminController::class, 'index'], 'admin_security_logs');

$match = $router->match();

if ($match) {
    [$class, $method] = $match['target'];
    $controller = new $class();
    call_user_func_array([$controller, $method], $match['params']);
    return;
}

(new ErreurController())->notFound();
