<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';
require_once BASE_PATH . '/app/repositories/RecipeInteractionRepository.php';

$recipes = [];
$popularRecipes = [];
$ratingSummaries = [];
$totalRecipes = 0;
$dbError = null;

try {
    $pdo = db();
    $repo = new RecipeRepository($pdo);
    $recipes = $repo->latest(6);
    $popularRecipes = $repo->popular(4);
    $totalRecipes = $repo->count();
    $ratingSummaries = (new RecipeInteractionRepository($pdo))->ratingSummariesForRecipeIds(array_merge(array_column($recipes, 'id'), array_column($popularRecipes, 'id')));
} catch (Throwable $exception) {
    $dbError = 'Impossible de charger les recettes pour le moment.';
}

public_header('Accueil');
?>
<section class="relative overflow-hidden bg-[#fff1dc]">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[.95fr_1.05fr] lg:items-center lg:px-8 lg:py-14">
        <div class="relative z-10">
            <span class="inline-flex rounded-full border border-orange-200 bg-white px-4 py-2 text-sm font-extrabold text-tomato shadow-sm">Cuisine familiale · Recettes de saison</span>
            <h1 class="mt-6 max-w-3xl font-serif text-5xl font-bold leading-tight text-stone-950 sm:text-7xl">Des recettes maison, simples et généreuses.</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-stone-700">Mijoté Maison rassemble des idées gourmandes pour tous les jours : entrées fraîches, plats réconfortants, desserts à partager et recettes faciles à refaire à la maison.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a class="btn-primary" href="/recettes">Voir toutes les recettes</a>
                <a class="btn-secondary" href="#apercu-recettes">Découvrir la sélection</a>
            </div>
            <div class="mt-8 grid max-w-xl grid-cols-3 gap-3 text-center text-sm">
                <div class="rounded-2xl bg-white p-4 shadow-sm"><strong class="block text-2xl text-tomato"><?= e($totalRecipes > 0 ? (string) $totalRecipes : '10') ?></strong><span class="text-stone-600">recettes</span></div>
                <div class="rounded-2xl bg-white p-4 shadow-sm"><strong class="block text-2xl text-herb"><?= e((string) count(recipe_categories())) ?></strong><span class="text-stone-600">catégories</span></div>
                <div class="rounded-2xl bg-white p-4 shadow-sm"><strong class="block text-2xl text-amber-600">15-45</strong><span class="text-stone-600">minutes</span></div>
            </div>
        </div>
        <div class="relative">
            <img class="aspect-[16/10] w-full rounded-[2rem] object-cover shadow-2xl shadow-orange-900/20" src="/assets/img/recipes/hero-cuisine-familiale.webp" alt="Table conviviale avec plusieurs plats maison">
            <div class="absolute -bottom-6 left-6 max-w-xs rounded-3xl bg-white p-5 shadow-xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-herb">Recette du moment</p>
                <p class="mt-2 font-serif text-2xl font-bold text-stone-950">Fondant au chocolat</p>
                <p class="mt-1 text-sm text-stone-600">Un cœur coulant, une boule de glace, et le dessert disparaît vite.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-cream py-16">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:items-center lg:px-8">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Bienvenue</p>
            <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">Une page d'accueil claire pour trouver vite une bonne idée.</h2>
            <p class="mt-4 text-lg leading-8 text-stone-700">Le site présente une sélection de recettes accessibles au grand public. Chaque carte affiche le titre, une image et une courte description, puis renvoie vers une page recette détaillée.</p>
        </div>
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <p class="text-3xl">🍲</p>
                <h3 class="mt-4 font-serif text-2xl font-bold text-stone-950">Entrées</h3>
                <p class="mt-2 text-sm leading-6 text-stone-600">Veloutés, salades et idées fraîches.</p>
            </div>
            <div class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <p class="text-3xl">🍝</p>
                <h3 class="mt-4 font-serif text-2xl font-bold text-stone-950">Plats</h3>
                <p class="mt-2 text-sm leading-6 text-stone-600">Recettes simples pour le quotidien.</p>
            </div>
            <div class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <p class="text-3xl">🍫</p>
                <h3 class="mt-4 font-serif text-2xl font-bold text-stone-950">Desserts</h3>
                <p class="mt-2 text-sm leading-6 text-stone-600">Douceurs familiales à partager.</p>
            </div>
        </div>
    </div>
</section>

<section id="apercu-recettes" class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">À table</p>
            <h2 class="mt-2 font-serif text-4xl font-bold text-stone-950">Quelques recettes à découvrir</h2>
            <p class="mt-2 max-w-2xl text-stone-600">Un aperçu des recettes disponibles, avec navigation vers les pages détaillées.</p>
        </div>
        <a class="btn-secondary" href="/recettes">Voir la liste complète</a>
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
                <article class="group overflow-hidden rounded-[1.6rem] border border-orange-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-900/10">
                    <a href="<?= e(recipe_url((string) $recipe['slug'])) ?>" class="block">
                        <div class="relative">
                            <img class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
                            <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-extrabold text-tomato shadow-sm"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></span>
                        </div>
                        <div class="p-5">
                            <div class="mb-3 flex flex-wrap gap-2 text-xs font-extrabold text-stone-600">
                                <span class="rounded-full bg-orange-50 px-3 py-1"><?= e($meta['time']) ?></span>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-herb"><?= e($meta['level']) ?></span>
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
    <?php endif; ?>
</section>

<?php if (!$dbError && $popularRecipes): ?>
<section class="bg-[#fff1dc] py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Les plus consultées</p>
                <h2 class="mt-2 font-serif text-4xl font-bold text-stone-950">Recettes populaires</h2>
                <p class="mt-2 max-w-2xl text-stone-600">Un classement simple basé sur le nombre de vues enregistrées sur chaque page recette.</p>
            </div>
            <a class="btn-secondary" href="/recettes">Explorer toutes les recettes</a>
        </div>
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            <?php foreach ($popularRecipes as $rank => $recipe): ?>
                <?php $rating = $ratingSummaries[(int) $recipe['id']] ?? ['average' => 0, 'count' => 0]; ?>
                <a class="group rounded-[1.5rem] border border-orange-100 bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-900/10" href="<?= e(recipe_url((string) $recipe['slug'])) ?>">
                    <div class="relative overflow-hidden rounded-[1.1rem]">
                        <img class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
                        <span class="absolute left-3 top-3 grid h-10 w-10 place-items-center rounded-full bg-tomato text-sm font-black text-white shadow-lg">#<?= e((string) ($rank + 1)) ?></span>
                    </div>
                    <h3 class="mt-4 font-serif text-2xl font-bold leading-tight text-stone-950"><?= e($recipe['title']) ?></h3>
                    <div class="mt-3 flex items-center gap-2 text-xs font-bold text-stone-500">
                        <?= render_stars((float) $rating['average'], 'text-base') ?>
                        <span><?= e(number_format((float) $rating['average'], 1, ',', ' ')) ?>/5</span>
                    </div>
                    <p class="mt-2 text-sm font-bold text-tomato"><?= e((string) ($recipe['view_count'] ?? 0)) ?> vues</p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="bg-[#fff1dc] py-14">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:items-center lg:px-8">
        <img class="aspect-[16/9] w-full rounded-[2rem] object-cover shadow-xl shadow-orange-900/10" src="/assets/img/recipes/ingredients-frais.webp" alt="Ingrédients frais sur un plan de travail">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-herb">Dans le panier</p>
            <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">Des ingrédients frais, des recettes faciles à aimer.</h2>
            <p class="mt-4 text-lg leading-8 text-stone-700">Chaque recette est pensée pour être lisible et pratique : une belle photo, une liste d'ingrédients claire et des étapes simples.</p>
            <div class="mt-6 flex flex-wrap gap-2 text-sm font-extrabold">
                <span class="rounded-full bg-white px-4 py-2 text-herb shadow-sm">Produits frais</span>
                <span class="rounded-full bg-white px-4 py-2 text-tomato shadow-sm">Cuisine maison</span>
                <span class="rounded-full bg-white px-4 py-2 text-amber-700 shadow-sm">À partager</span>
            </div>
        </div>
    </div>
</section>
<?php public_footer(); ?>
