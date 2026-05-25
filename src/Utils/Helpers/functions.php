<?php

declare(strict_types=1);

use App\Repository\SecurityLogRepository;

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
    $publicPath = BASE_PATH . '/public/' . ltrim($path, '/');
    $url = asset($path);

    return is_file($publicPath) ? $url . '?v=' . filemtime($publicPath) : $url;
}

function recipe_image_url(?string $imagePath): string
{
    $fallback = asset('assets/img/recipe-placeholder.svg');
    $imagePath = trim((string) $imagePath);

    if ($imagePath === '') {
        return $fallback;
    }

    $path = parse_url($imagePath, PHP_URL_PATH);
    if (!is_string($path) || $path === '') {
        return $fallback;
    }

    $relativePath = ltrim($path, '/');
    if (str_contains($relativePath, '..') || str_contains($relativePath, "\0")) {
        return $fallback;
    }

    return is_file(PUBLIC_PATH . '/' . $relativePath) ? asset($relativePath) : $fallback;
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
        'couscous-aux-legumes' => ['category' => 'vegetarien', 'label' => 'Végétarien', 'time' => '55 min', 'level' => 'Facile', 'tag' => 'Soleil', 'servings' => '6 personnes', 'season' => 'Convivial'],
        'crepes-sucrees' => ['category' => 'desserts', 'label' => 'Dessert', 'time' => '35 min', 'level' => 'Très facile', 'tag' => 'Goûter', 'servings' => '12 crêpes', 'season' => 'Goûter'],
        'gaufres-maison' => ['category' => 'desserts', 'label' => 'Dessert', 'time' => '30 min', 'level' => 'Facile', 'tag' => 'Brunch', 'servings' => '8 gaufres', 'season' => 'Brunch'],
        'lasagnes-bolognaise' => ['category' => 'plats', 'label' => 'Plat', 'time' => '1 h 15', 'level' => 'Moyen', 'tag' => 'Familial', 'servings' => '6 parts', 'season' => 'Réconfort'],
        'quiche-lorraine-maison' => ['category' => 'plats', 'label' => 'Plat', 'time' => '50 min', 'level' => 'Facile', 'tag' => 'Classique', 'servings' => '6 parts', 'season' => 'Toute saison'],
        'ratatouille-provencale' => ['category' => 'vegetarien', 'label' => 'Végétarien', 'time' => '50 min', 'level' => 'Facile', 'tag' => 'Provence', 'servings' => '4 assiettes', 'season' => 'Été'],
        'salade-nicoise' => ['category' => 'entrees', 'label' => 'Entrée', 'time' => '25 min', 'level' => 'Facile', 'tag' => 'Fraîcheur', 'servings' => '4 assiettes', 'season' => 'Été'],
        'soupe-lentilles-corail' => ['category' => 'entrees', 'label' => 'Entrée', 'time' => '30 min', 'level' => 'Très facile', 'tag' => 'Velouté', 'servings' => '4 bols', 'season' => 'Réconfort'],
        'tajine-poulet-olives-citron' => ['category' => 'plats', 'label' => 'Plat', 'time' => '1 h', 'level' => 'Moyen', 'tag' => 'Mijoté', 'servings' => '4 personnes', 'season' => 'Partage'],
        'tiramisu-classique' => ['category' => 'desserts', 'label' => 'Dessert', 'time' => '25 min + repos', 'level' => 'Facile', 'tag' => 'Italien', 'servings' => '6 parts', 'season' => 'Gourmand'],
    ];

    return array_merge($default, $meta[$slug ?? ''] ?? []);
}

function recipe_category_label(?string $category): string
{
    $categories = recipe_categories();

    return $categories[$category ?? ''] ?? 'Recette';
}
