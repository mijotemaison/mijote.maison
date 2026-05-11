<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/') ?: '/';
$publicPath = __DIR__ . $path;

if (PHP_SAPI === 'cli-server' && $path !== '/' && is_file($publicPath)) {
    return false;
}

$routes = [
    '/' => 'index.php',
    '/recettes' => 'recipes.php',
    '/connexion' => 'login.php',
    '/deconnexion' => 'logout.php',
    '/a-propos' => 'about.php',
    '/presentation' => 'presentation.php',
    '/stack' => 'stack.php',
];

if (isset($routes[$path])) {
    require __DIR__ . '/' . $routes[$path];
    return;
}

if (preg_match('#^/recette/([a-z0-9-]+)$#', $path, $matches) === 1) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/recipe.php';
    return;
}

http_response_code(404);
require __DIR__ . '/index.php';
