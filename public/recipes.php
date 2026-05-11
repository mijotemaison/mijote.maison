<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';
require_once BASE_PATH . '/app/repositories/RecipeInteractionRepository.php';

$recipes = [];
$ratingSummaries = [];
$dbError = null;
$query = trim((string) ($_GET['q'] ?? ''));
$category = (string) ($_GET['category'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 6;
$totalRecipes = 0;
$totalPages = 1;

try {
    $pdo = db();
    $repo = new RecipeRepository($pdo);
    $totalRecipes = $repo->countPublished($query, $category);
    $totalPages = max(1, (int) ceil($totalRecipes / $perPage));
    $page = min($page, $totalPages);
    $recipes = $repo->published($perPage, ($page - 1) * $perPage, $query, $category);
    $ratingSummaries = (new RecipeInteractionRepository($pdo))->ratingSummariesForRecipeIds(array_column($recipes, 'id'));
} catch (Throwable $exception) {
    $dbError = 'Impossible de charger les recettes pour le moment.';
}

$baseParams = [];
if ($query !== '') {
    $baseParams['q'] = $query;
}
if ($category !== '' && array_key_exists($category, recipe_categories())) {
    $baseParams['category'] = $category;
}

function recipes_page_url(array $params): string
{
    $queryString = http_build_query($params);

    return '/recettes' . ($queryString !== '' ? '?' . $queryString : '');
}

public_header('Recettes');
?>
<section class="bg-[#fff1dc] py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Toutes les recettes</p>
            <h1 class="mt-3 font-serif text-5xl font-bold leading-tight text-stone-950 sm:text-6xl">La liste complète des recettes.</h1>
            <p class="mt-4 text-lg leading-8 text-stone-700">Retrouvez les recettes de cuisine avec leur titre, image, courte description et un lien vers la page détaillée.</p>
        </div>
    </div>
</section>

<section class="bg-cream py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <form class="grid gap-4 rounded-[2rem] border border-orange-100 bg-white p-4 shadow-sm lg:grid-cols-[1fr_auto] lg:items-center" action="/recettes" method="get">
            <label class="relative block">
                <span class="sr-only">Rechercher une recette</span>
                <input class="w-full rounded-full border border-orange-200 bg-orange-50 px-5 py-4 pr-24 text-stone-900 outline-none transition placeholder:text-stone-500 focus:border-tomato focus:ring-4 focus:ring-orange-200" name="q" value="<?= e($query) ?>" data-recipe-search type="search" placeholder="Rechercher une recette, un ingrédient, une envie...">
                <button class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-tomato px-4 py-2 text-sm font-extrabold text-white" type="submit">OK</button>
            </label>
            <div class="flex flex-wrap gap-2">
                <a class="rounded-full border <?= $category === '' ? 'border-tomato bg-tomato text-white' : 'border-orange-200 bg-white text-stone-700' ?> px-4 py-2 text-sm font-extrabold transition" href="<?= e(recipes_page_url($query !== '' ? ['q' => $query] : [])) ?>" data-recipe-filter="all">Tout</a>
                <?php foreach (recipe_categories() as $value => $label): ?>
                    <a class="rounded-full border <?= $category === $value ? 'border-tomato bg-tomato text-white' : 'border-orange-200 bg-white text-stone-700' ?> px-4 py-2 text-sm font-extrabold transition" href="<?= e(recipes_page_url(array_filter(['q' => $query, 'category' => $value], static fn($v) => $v !== ''))) ?>" data-recipe-filter="<?= e($value) ?>"><?= e($label) ?></a>
                <?php endforeach; ?>
            </div>
        </form>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">À table</p>
            <h2 class="mt-2 font-serif text-4xl font-bold text-stone-950">Recettes disponibles</h2>
            <p class="mt-2 max-w-2xl text-stone-600"><?= e((string) $totalRecipes) ?> recette(s) publiee(s), avec recherche serveur, filtre categorie et pagination.</p>
        </div>
        <a class="btn-secondary" href="/">Retour à l'accueil</a>
    </div>

    <?php if ($dbError): ?>
        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-amber-900"><?= e($dbError) ?></div>
    <?php elseif (!$recipes): ?>
        <div class="rounded-3xl border border-orange-100 bg-white p-5 text-stone-600">Aucune recette publiée pour le moment.</div>
    <?php else: ?>
        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($recipes as $recipe): ?>
                <?php $meta = recipe_public_meta($recipe['slug'] ?? null); ?>
                <?php $rating = $ratingSummaries[(int) $recipe['id']] ?? ['average' => 0, 'count' => 0]; ?>
                <article class="group overflow-hidden rounded-[1.6rem] border border-orange-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-900/10" data-recipe-card data-category="<?= e($recipe['category'] ?? $meta['category']) ?>" data-search="<?= e($recipe['title'] . ' ' . $recipe['short_description'] . ' ' . $recipe['ingredients'] . ' ' . recipe_category_label($recipe['category'] ?? null) . ' ' . $meta['tag']) ?>">
                    <a href="<?= e(recipe_url((string) $recipe['slug'])) ?>" class="block">
                        <div class="relative">
                            <img class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
                            <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-extrabold text-tomato shadow-sm"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></span>
                        </div>
                        <div class="p-5">
                            <div class="mb-3 flex flex-wrap gap-2 text-xs font-extrabold text-stone-600">
                                <span class="rounded-full bg-orange-50 px-3 py-1"><?= e($meta['time']) ?></span>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-herb"><?= e($meta['level']) ?></span>
                                <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700"><?= e($meta['tag']) ?></span>
                            </div>
                            <div class="mb-3 flex items-center gap-2 text-xs font-bold text-stone-500">
                                <?= render_stars((float) $rating['average'], 'text-base') ?>
                                <span><?= e($rating['count'] > 0 ? number_format((float) $rating['average'], 1, ',', ' ') . '/5 · ' . $rating['count'] . ' avis' : 'Pas encore notée') ?></span>
                            </div>
                            <h3 class="font-serif text-2xl font-bold leading-tight text-stone-950"><?= e($recipe['title']) ?></h3>
                            <p class="mt-3 min-h-16 text-sm leading-6 text-stone-600"><?= e($recipe['short_description']) ?></p>
                            <span class="mt-5 inline-flex font-extrabold text-tomato">Voir la recette →</span>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if ($totalPages > 1): ?>
            <nav class="mt-10 flex flex-wrap items-center justify-center gap-3" aria-label="Pagination recettes">
                <?php if ($page > 1): ?>
                    <a class="btn-secondary" href="<?= e(recipes_page_url($baseParams + ['page' => $page - 1])) ?>">← Précédent</a>
                <?php endif; ?>
                <span class="rounded-full bg-white px-5 py-3 text-sm font-extrabold text-stone-700 shadow-sm">Page <?= e($page) ?> / <?= e($totalPages) ?></span>
                <?php if ($page < $totalPages): ?>
                    <a class="btn-secondary" href="<?= e(recipes_page_url($baseParams + ['page' => $page + 1])) ?>">Suivant →</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
        <div class="mt-8 hidden rounded-3xl border border-orange-100 bg-white p-6 text-center text-stone-600" data-recipe-empty>Aucune recette ne correspond à votre recherche.</div>
    <?php endif; ?>
</section>
<?php public_footer(); ?>
