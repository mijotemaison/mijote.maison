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

function recipe_image_url(?string $imagePath): string
{
    if ($imagePath === null || $imagePath === '') {
        return asset('assets/img/recipe-placeholder.svg');
    }

    return asset($imagePath);
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

function nav_link(string $href, string $label): string
{
    $isActive = current_path() === $href;
    $state = $isActive
        ? 'text-tomato nav-link-active'
        : 'text-ink/70 hover:text-tomato';
    return '<a class="' . $state . ' relative font-sans text-[0.95rem] font-bold tracking-wide transition-colors duration-200" href="' . e($href) . '">' . e($label) . '</a>';
}

function public_header(string $title, ?array $og = null): void
{
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
    <meta name="theme-color" content="#fbf3e3">
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,800;1,9..144,400;1,9..144,600&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap">
    <link rel="stylesheet" href="/assets/css/output.css">
    <script src="/assets/js/presentation.js" defer></script>
    <script src="/assets/js/recipes.js" defer></script>
    <script src="/assets/js/toasts.js" defer></script>
</head>
<body class="min-h-screen bg-parchment text-ink antialiased font-sans">
<a class="skip-link" href="#main">Aller au contenu</a>
<header class="sticky top-0 z-40 border-b border-orange-100/60 bg-parchment/80 backdrop-blur-xl supports-[backdrop-filter]:bg-parchment/70">
    <nav class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <a href="/" class="group flex items-center gap-3 text-ink">
            <img class="h-12 w-12 rounded-2xl shadow-soft-2 ring-1 ring-embers-100 transition-transform duration-300 ease-editorial group-hover:rotate-[-3deg]" src="/assets/img/logo-mijote-maison.svg" alt="">
            <span class="leading-snug">
                <span class="block font-display text-2xl font-bold tracking-tight text-ink">Mijoté Maison</span>
                <span class="mt-1 block text-[0.7rem] font-extrabold uppercase tracking-[0.22em] text-herb">Recettes de saison</span>
            </span>
        </a>
        <div class="flex flex-wrap items-center gap-x-7 gap-y-2 text-sm">
HTML;
    echo nav_link('/', 'Accueil');
    echo nav_link('/recipes.php', 'Recettes');
    echo nav_link('/presentation.php', 'Présentation');
    echo nav_link('/stack.php', 'Stack');
    if (isset($_SESSION['admin_id'])) {
        echo nav_link('/admin/dashboard.php', 'Back-office');
    } else {
        echo nav_link('/login.php', 'Connexion');
    }
    echo <<<HTML
        </div>
    </nav>
</header>
<main id="main">
HTML;
}

function public_footer(): void
{
    echo <<<HTML
</main>
<footer class="border-t border-orange-100/80 bg-gradient-to-b from-parchment to-fog">
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-12 text-sm sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <img class="h-10 w-10 rounded-xl ring-1 ring-embers-100 shadow-soft-1" src="/assets/img/logo-mijote-maison.svg" alt="">
            <div>
                <p class="font-display text-base font-bold text-ink">Mijoté Maison</p>
                <p class="text-xs text-ink/60">Des recettes simples pour cuisiner avec plaisir.</p>
            </div>
        </div>
        <p class="smallcaps text-xs font-semibold text-ink/55">Recettes familiales · Plats maison · Desserts gourmands</p>
    </div>
</footer>
</body>
</html>
HTML;
}

function admin_header(string $title): void
{
    $pageTitle = e($title . ' - Espace équipe');
    echo <<<HTML
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a12">
    <title>{$pageTitle}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap">
    <link rel="stylesheet" href="/assets/css/output.css">
    <script src="/assets/js/toasts.js" defer></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased font-sans">
<div class="min-h-screen lg:flex">
    <aside class="border-b border-white/10 bg-slate-950/95 lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r">
        <div class="flex items-center justify-between px-5 py-5 lg:block">
            <a class="flex items-center gap-3 font-semibold text-white" href="/admin/dashboard.php">
                <img class="h-10 w-10 rounded-xl bg-white" src="/assets/img/logo-mijote-maison.svg" alt="">
                <span>Espace équipe</span>
            </a>
            <a class="text-sm text-slate-400 hover:text-white lg:hidden" href="/logout.php">Sortir</a>
        </div>
        <nav class="grid gap-1 px-3 pb-5 text-sm">
            <a class="rounded-lg px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white" href="/admin/dashboard.php">Dashboard</a>
            <a class="rounded-lg px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white" href="/admin/recipes/index.php">Recettes</a>
            <a class="rounded-lg px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white" href="/admin/admins/index.php">Administrateurs</a>
            <a class="rounded-lg px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white" href="/presentation.php">Presentation</a>
            <a class="hidden rounded-lg px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white lg:block" href="/logout.php">Deconnexion</a>
        </nav>
    </aside>
    <main class="flex-1">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
HTML;
}

function admin_footer(): void
{
    echo <<<HTML
        </div>
    </main>
</div>
<script src="/assets/js/admin.js" defer></script>
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
