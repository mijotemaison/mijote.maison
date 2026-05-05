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
    $active = current_path() === $href ? 'text-tomato' : 'text-stone-700 hover:text-tomato';
    return '<a class="' . $active . ' font-extrabold transition" href="' . e($href) . '">' . e($label) . '</a>';
}

function public_header(string $title): void
{
    $pageTitle = e($title . ' - Mijoté Maison');
    echo <<<HTML
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$pageTitle}</title>
    <link rel="stylesheet" href="/assets/css/output.css">
    <script src="/assets/js/presentation.js" defer></script>
    <script src="/assets/js/recipes.js" defer></script>
</head>
<body class="min-h-screen bg-cream text-stone-900 antialiased">
<header class="sticky top-0 z-40 border-b border-orange-100 bg-cream/95 shadow-sm backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
        <a href="/" class="flex items-center gap-3 text-stone-950">
            <img class="h-12 w-12 rounded-2xl" src="/assets/img/logo-mijote-maison.svg" alt="">
            <span class="leading-tight">
                <span class="block font-serif text-xl font-bold">Mijoté Maison</span>
                <span class="block text-xs font-extrabold uppercase tracking-[0.18em] text-herb">Recettes de saison</span>
            </span>
        </a>
        <div class="flex items-center gap-5 text-sm">
HTML;
    echo nav_link('/', 'Accueil');
    echo nav_link('/recipes.php', 'Recettes');
    if (isset($_SESSION['admin_id'])) {
        echo nav_link('/admin/dashboard.php', 'Back-office');
    } else {
        echo nav_link('/login.php', 'Connexion');
    }
    echo <<<HTML
        </div>
    </nav>
</header>
<main>
HTML;
}

function public_footer(): void
{
    echo <<<HTML
</main>
<footer class="border-t border-orange-100 bg-[#fff1dc]">
    <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-8 text-sm text-stone-600 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <p><strong class="text-stone-900">Mijoté Maison</strong> - Des recettes simples pour cuisiner avec plaisir.</p>
        <p>Recettes familiales · Plats maison · Desserts gourmands</p>
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
    <title>{$pageTitle}</title>
    <link rel="stylesheet" href="/assets/css/output.css">
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
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
</body>
</html>
HTML;
}

function render_flash(): void
{
    $messages = flash_get_all();
    foreach ($messages as $type => $items) {
        $classes = $type === 'success'
            ? 'border-emerald-400/40 bg-emerald-400/10 text-emerald-100'
            : 'border-rose-400/40 bg-rose-400/10 text-rose-100';
        foreach ($items as $message) {
            echo '<div class="mb-4 rounded-lg border px-4 py-3 text-sm ' . $classes . '">' . e($message) . '</div>';
        }
    }
}
