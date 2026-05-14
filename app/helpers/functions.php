<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function old(string $key, string $default = ''): string
{
    return e($_POST[$key] ?? $default);
}

function current_path(): string
{
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
}

function asset(string $path): string
{
    return '/' . ltrim($path, '/');
}

function versioned_asset(string $path): string
{
    $publicPath = dirname(__DIR__, 2) . '/public/' . ltrim($path, '/');
    $url = asset($path);

    return is_file($publicPath) ? $url . '?v=' . filemtime($publicPath) : $url;
}

function recipe_image_url(?string $imagePath): string
{
    if ($imagePath === null || $imagePath === '') {
        return asset('assets/img/recipe-placeholder.svg');
    }

    return asset($imagePath);
}

function recipe_url(string $slug): string
{
    return '/recette/' . rawurlencode($slug);
}

function recipe_print_url(string $slug): string
{
    return recipe_url($slug) . '/impression';
}

function recipes_page_url(array $params): string
{
    $queryString = http_build_query($params);

    return '/recettes' . ($queryString !== '' ? '?' . $queryString : '');
}

function recipe_categories(): array
{
    return [
        'entrees' => 'Entrées',
        'plats' => 'Plats',
        'desserts' => 'Desserts',
        'vegetarien' => 'Végétarien',
    ];
}

function recipe_statuses(): array
{
    return [
        'draft' => 'Brouillon',
        'published' => 'Publié',
        'archived' => 'Archivé',
    ];
}

function public_actor_hash(): string
{
    $fingerprint = session_id()
        . '|' . substr((string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'), 0, 45)
        . '|' . substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'), 0, 255);

    return hash('sha256', $fingerprint);
}

function request_ip(): string
{
    return substr((string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'), 0, 45);
}

function request_user_agent(): string
{
    return substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'), 0, 255);
}

function record_security_event(PDO $pdo, string $eventType, string $details, ?string $actorEmail = null): void
{
    try {
        (new SecurityLogRepository($pdo))->create([
            'event_type' => substr($eventType, 0, 80),
            'actor_email' => $actorEmail ? substr($actorEmail, 0, 190) : null,
            'ip_address' => request_ip(),
            'user_agent' => request_user_agent(),
            'details' => substr($details, 0, 1000),
        ]);
    } catch (Throwable) {
        // Le journal ne doit jamais bloquer une action utilisateur.
    }
}

function render_stars(float $average, string $class = 'stars'): string
{
    $rounded = (int) round($average);
    $html = '<span class="' . e($class) . '" aria-hidden="true">';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= $rounded ? '★' : '☆';
    }
    $html .= '</span>';

    return $html;
}

function is_nav_active(string $href): bool
{
    $path = current_path();

    if ($path === $href) {
        return true;
    }

    return match ($href) {
        '/recettes' => str_starts_with($path, '/recette/'),
        '/admin/dashboard' => str_starts_with($path, '/admin'),
        default => false,
    };
}

function recipe_public_meta(?string $slug): array
{
    $default = [
        'category' => 'plats',
        'label' => 'Recette',
        'time' => '30 min',
        'level' => 'Facile',
        'tag' => 'Maison',
        'servings' => '4 personnes',
        'season' => 'Maison',
    ];

    $meta = [
        'veloute-de-potimarron' => ['category' => 'entrees', 'label' => 'Entrée', 'time' => '35 min', 'level' => 'Très facile', 'tag' => 'Douceur automne', 'servings' => '4 bols', 'season' => 'Automne'],
        'poulet-citron-et-herbes' => ['category' => 'plats', 'label' => 'Plat', 'time' => '30 min', 'level' => 'Facile', 'tag' => 'Familial', 'servings' => '4 personnes', 'season' => 'Toute saison'],
        'tarte-fine-aux-pommes' => ['category' => 'desserts', 'label' => 'Dessert', 'time' => '40 min', 'level' => 'Facile', 'tag' => 'Croustillant', 'servings' => '6 parts', 'season' => 'Goûter'],
        'pates-cremeuses-aux-champignons' => ['category' => 'plats', 'label' => 'Plat', 'time' => '25 min', 'level' => 'Facile', 'tag' => 'Réconfort', 'servings' => '4 assiettes', 'season' => 'Réconfort'],
        'salade-mediterraneenne' => ['category' => 'entrees', 'label' => 'Entrée', 'time' => '15 min', 'level' => 'Très facile', 'tag' => 'Fraîcheur', 'servings' => '4 assiettes', 'season' => 'Été'],
        'saumon-au-four-et-legumes' => ['category' => 'plats', 'label' => 'Plat', 'time' => '35 min', 'level' => 'Facile', 'tag' => 'Équilibré', 'servings' => '4 personnes', 'season' => 'Équilibré'],
        'curry-de-legumes-coco' => ['category' => 'vegetarien', 'label' => 'Végétarien', 'time' => '35 min', 'level' => 'Facile', 'tag' => 'Parfumé', 'servings' => '4 bols', 'season' => 'Parfumé'],
        'burger-maison-gourmand' => ['category' => 'plats', 'label' => 'Plat', 'time' => '45 min', 'level' => 'Moyen', 'tag' => 'Week-end', 'servings' => '4 burgers', 'season' => 'Week-end'],
        'risotto-parmesan-et-champignons' => ['category' => 'plats', 'label' => 'Plat', 'time' => '35 min', 'level' => 'Moyen', 'tag' => 'Crémeux', 'servings' => '4 assiettes', 'season' => 'Crémeux'],
        'fondant-au-chocolat' => ['category' => 'desserts', 'label' => 'Dessert', 'time' => '22 min', 'level' => 'Facile', 'tag' => 'Gourmand', 'servings' => '6 fondants', 'season' => 'Gourmand'],
    ];

    return array_merge($default, $meta[$slug ?? ''] ?? []);
}

function recipe_category_label(?string $category): string
{
    $categories = recipe_categories();

    return $categories[$category ?? ''] ?? 'Recette';
}

function nav_link(string $href, string $label): string
{
    $isActive = is_nav_active($href);
    $state = $isActive ? 'nav-link active' : 'nav-link';

    return '<a class="' . $state . '" href="' . e($href) . '">' . e($label) . '</a>';
}

function public_header(string $title, ?array $og = null): void
{
    $faviconUrl = e(versioned_asset('assets/img/favicon.svg'));
    $bootstrapCssUrl = e(versioned_asset('assets/vendor/bootstrap/css/bootstrap.min.css'));
    $cssUrl = e(versioned_asset('assets/css/app.css'));
    $bootstrapJsUrl = e(versioned_asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'));
    $presentationJsUrl = e(versioned_asset('assets/js/presentation.js'));
    $recipesJsUrl = e(versioned_asset('assets/js/recipes.js'));
    $toastsJsUrl = e(versioned_asset('assets/js/toasts.js'));
    $pageTitle = e($title . ' - Mijoté Maison');
    $ogType = e($og['type'] ?? 'website');
    $ogTitle = e($og['title'] ?? ($title . ' — Mijoté Maison'));
    $ogDesc = e($og['description'] ?? 'Mijoté Maison rassemble des recettes maison généreuses : entrées, plats, desserts et idées de saison.');
    $ogImage = e($og['image'] ?? '/assets/img/recipes/hero-cuisine-familiale.webp');
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');
    $reqUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
    $ogUrl = e($scheme . '://' . $host . $reqUri);
    if (str_starts_with($ogImage, '/')) {
        $ogImage = e($scheme . '://' . $host) . $ogImage;
    }
    echo <<<HTML
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8fbff">
    <meta name="description" content="{$ogDesc}">
    <title>{$pageTitle}</title>
    <meta property="og:type" content="{$ogType}">
    <meta property="og:title" content="{$ogTitle}">
    <meta property="og:description" content="{$ogDesc}">
    <meta property="og:image" content="{$ogImage}">
    <meta property="og:url" content="{$ogUrl}">
    <meta property="og:site_name" content="Mijoté Maison">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{$ogTitle}">
    <meta name="twitter:description" content="{$ogDesc}">
    <meta name="twitter:image" content="{$ogImage}">
    <link rel="icon" href="{$faviconUrl}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,800;1,9..144,400;1,9..144,600&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap">
    <link rel="stylesheet" href="{$bootstrapCssUrl}">
    <link rel="stylesheet" href="{$cssUrl}">
    <script src="{$bootstrapJsUrl}" defer></script>
    <script src="{$presentationJsUrl}" defer></script>
    <script src="{$recipesJsUrl}" defer></script>
    <script src="{$toastsJsUrl}" defer></script>
</head>
<body>
<a class="skip-link" href="#main">Aller au contenu</a>
<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-xl">
        <div class="container py-2">
            <a href="/" class="navbar-brand d-flex align-items-center gap-3">
                <img class="site-logo" src="/assets/img/logo-mijote-maison.svg" alt="">
                <span>
                    <span class="brand-title d-block fs-3">Mijoté Maison</span>
                    <span class="brand-subtitle d-block mt-1">Recettes de saison</span>
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Ouvrir la navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="mainNavigation">
                <div class="navbar-nav gap-1 rounded-pill bg-white p-1 shadow-sm mt-3 mt-xl-0">
HTML;
    echo nav_link('/', 'Accueil');
    echo nav_link('/recettes', 'Recettes');
    echo nav_link('/a-propos', 'À propos');
    echo nav_link('/presentation', 'Présentation');
    echo nav_link('/conformite', 'Conformité');
    echo nav_link('/stack', 'Stack');
    if (is_admin_authenticated()) {
        echo nav_link('/admin/dashboard', 'Back-office');
    } else {
        echo nav_link('/connexion', 'Connexion');
    }
    echo <<<HTML
                </div>
            </div>
        </div>
    </nav>
    <div class="formation-banner text-center py-2 px-3">
        Projet factice réalisé dans le cadre d’une formation.
    </div>
</header>
<main id="main">
HTML;
}

function public_footer(): void
{
    echo <<<HTML
</main>
<footer class="site-footer">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-6 d-flex align-items-center gap-3">
            <img class="site-logo" src="/assets/img/logo-mijote-maison.svg" alt="">
            <div>
                <p class="brand-title fs-5 mb-1">Mijoté Maison</p>
                <p class="text-muted small mb-0">Des recettes simples pour cuisiner avec plaisir.</p>
            </div>
        </div>
            <div class="col-lg-6 text-lg-end">
                <p class="text-uppercase small fw-bold text-muted mb-2">Recettes familiales · Plats maison · Desserts gourmands</p>
                <div class="d-flex flex-wrap gap-3 justify-content-lg-end small fw-bold">
                    <a href="/mentions-legales">Mentions légales</a>
                    <a href="/politique-confidentialite">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
HTML;
}

function admin_header(string $title): void
{
    $faviconUrl = e(versioned_asset('assets/img/favicon.svg'));
    $bootstrapCssUrl = e(versioned_asset('assets/vendor/bootstrap/css/bootstrap.min.css'));
    $cssUrl = e(versioned_asset('assets/css/app.css'));
    $bootstrapJsUrl = e(versioned_asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'));
    $toastsJsUrl = e(versioned_asset('assets/js/toasts.js'));
    $pageTitle = e($title . ' - Back-office Mijoté Maison');
    echo <<<HTML
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8fbff">
    <title>{$pageTitle}</title>
    <link rel="icon" href="{$faviconUrl}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap">
    <link rel="stylesheet" href="{$bootstrapCssUrl}">
    <link rel="stylesheet" href="{$cssUrl}">
    <script src="{$bootstrapJsUrl}" defer></script>
    <script src="{$toastsJsUrl}" defer></script>
</head>
<body class="admin-body">
<div class="admin-shell d-lg-flex">
    <aside class="admin-sidebar p-3">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a class="d-flex align-items-center gap-3 fw-bold text-white" href="/admin/dashboard">
                <img class="site-logo bg-white" src="/assets/img/logo-mijote-maison.svg" alt="">
                <span>Back-office</span>
            </a>
            <a class="d-lg-none small text-white-50" href="/deconnexion">Sortir</a>
        </div>
        <nav class="nav flex-column gap-1">
            <a class="nav-link" href="/admin/dashboard">Dashboard</a>
            <a class="nav-link" href="/admin/recettes">Recettes</a>
            <a class="nav-link" href="/admin/commentaires">Commentaires</a>
            <a class="nav-link" href="/admin/journal-securite">Journal</a>
            <a class="nav-link" href="/admin/administrateurs">Administrateurs</a>
            <a class="nav-link" href="/presentation">Présentation</a>
            <a class="nav-link d-none d-lg-block" href="/deconnexion">Déconnexion</a>
        </nav>
    </aside>
    <main class="admin-content flex-grow-1">
        <div class="container-fluid px-3 px-lg-4 py-4 py-lg-5">
HTML;
}

function admin_footer(): void
{
    $adminJsUrl = e(versioned_asset('assets/js/admin.js'));
    echo <<<HTML
        </div>
    </main>
</div>
<script src="{$adminJsUrl}" defer></script>
</body>
</html>
HTML;
}

function render_flash(): void
{
    $messages = flash_get_all();
    if (empty($messages)) {
        return;
    }
    echo '<div class="flash-stack" aria-live="polite" role="status">';
    foreach ($messages as $type => $items) {
        $variant = $type === 'success' ? 'flash-toast-success' : 'flash-toast-error';
        foreach ($items as $message) {
            echo '<div class="flash-toast ' . $variant . '" role="alert">'
                . '<span>' . e($message) . '</span>'
                . '<button type="button" class="flash-toast-close" aria-label="Fermer la notification">×</button>'
                . '</div>';
        }
    }
    echo '</div>';
}
