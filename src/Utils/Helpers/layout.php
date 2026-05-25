<?php

declare(strict_types=1);

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

function header_public(string $title, ?array $og = null): void
{
    public_header($title, $og);
}

function footer_public(): void
{
    public_footer();
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
