<?php

declare(strict_types=1);

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
$router->map('GET|POST', '/recette/[*:slug]', [RecipeController::class, 'show'], 'recipe_show');
$router->map('GET|POST', '/connexion', [AuthController::class, 'login'], 'login');
$router->map('GET', '/deconnexion', [AuthController::class, 'logout'], 'logout');
$router->map('GET', '/a-propos', [SiteController::class, 'about'], 'about');
$router->map('GET', '/presentation', [SiteController::class, 'presentation'], 'presentation');
$router->map('GET', '/conformite', [SiteController::class, 'conformite'], 'conformite');
$router->map('GET', '/stack', [SiteController::class, 'stack'], 'stack');

$match = $router->match();

if ($match) {
    [$class, $method] = $match['target'];
    $controller = new $class();
    call_user_func_array([$controller, $method], $match['params']);
    return;
}

(new ErreurController())->notFound();
