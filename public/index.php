<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';

$recipes = [];
$dbError = null;

$recipeMeta = [
    'veloute-de-potimarron-protege' => ['category' => 'entrees', 'label' => 'Entrée', 'time' => '35 min', 'level' => 'Très facile', 'tag' => 'Douceur automne'],
    'poulet-citron-et-herbes' => ['category' => 'plats', 'label' => 'Plat', 'time' => '30 min', 'level' => 'Facile', 'tag' => 'Familial'],
    'tarte-fine-aux-pommes' => ['category' => 'desserts', 'label' => 'Dessert', 'time' => '40 min', 'level' => 'Facile', 'tag' => 'Croustillant'],
    'pates-cremeuses-aux-champignons' => ['category' => 'plats', 'label' => 'Plat', 'time' => '25 min', 'level' => 'Facile', 'tag' => 'Réconfort'],
    'salade-mediterraneenne' => ['category' => 'entrees', 'label' => 'Entrée', 'time' => '15 min', 'level' => 'Très facile', 'tag' => 'Fraîcheur'],
    'saumon-au-four-et-legumes' => ['category' => 'plats', 'label' => 'Plat', 'time' => '35 min', 'level' => 'Facile', 'tag' => 'Équilibré'],
    'curry-de-legumes-coco' => ['category' => 'vegetarien', 'label' => 'Végétarien', 'time' => '35 min', 'level' => 'Facile', 'tag' => 'Parfumé'],
    'burger-maison-gourmand' => ['category' => 'plats', 'label' => 'Plat', 'time' => '45 min', 'level' => 'Moyen', 'tag' => 'Week-end'],
    'risotto-parmesan-et-champignons' => ['category' => 'plats', 'label' => 'Plat', 'time' => '35 min', 'level' => 'Moyen', 'tag' => 'Crémeux'],
    'fondant-au-chocolat' => ['category' => 'desserts', 'label' => 'Dessert', 'time' => '22 min', 'level' => 'Facile', 'tag' => 'Gourmand'],
];

try {
    $recipes = (new RecipeRepository(db()))->all();
} catch (Throwable $exception) {
    $dbError = 'Base de données indisponible. Vérifiez la configuration MySQL et importez database.sql.';
}

public_header('Accueil');
?>
<section class="relative overflow-hidden bg-[#fff1dc]">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[.95fr_1.05fr] lg:items-center lg:px-8 lg:py-14">
        <div class="relative z-10">
            <span class="inline-flex rounded-full border border-orange-200 bg-white px-4 py-2 text-sm font-extrabold text-tomato shadow-sm">Projet final GRETA 92 · Cuisine sécurisée</span>
            <h1 class="mt-6 max-w-3xl font-serif text-5xl font-bold leading-tight text-stone-950 sm:text-7xl">Des recettes maison, simples et bien gardées.</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-stone-700">Mijoté & Protégé rassemble des idées gourmandes pour tous les jours, avec un back-office sécurisé et des données affichées proprement.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a class="btn-primary" href="#recettes">Explorer les recettes</a>
                <a class="btn-secondary" href="/presentation.php">Voir la présentation</a>
            </div>
            <div class="mt-8 grid max-w-xl grid-cols-3 gap-3 text-center text-sm">
                <div class="rounded-2xl bg-white p-4 shadow-sm"><strong class="block text-2xl text-tomato">10</strong><span class="text-stone-600">recettes</span></div>
                <div class="rounded-2xl bg-white p-4 shadow-sm"><strong class="block text-2xl text-herb">100%</strong><span class="text-stone-600">PDO</span></div>
                <div class="rounded-2xl bg-white p-4 shadow-sm"><strong class="block text-2xl text-amber-600">CSRF</strong><span class="text-stone-600">protégé</span></div>
            </div>
        </div>
        <div class="relative">
            <img class="aspect-[16/10] w-full rounded-[2rem] object-cover shadow-2xl shadow-orange-900/20" src="/assets/img/recipes/hero-cuisine-familiale.webp" alt="Table conviviale avec plusieurs plats maison">
            <div class="absolute -bottom-6 left-6 max-w-xs rounded-3xl bg-white p-5 shadow-xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-herb">Recette du moment</p>
                <p class="mt-2 font-serif text-2xl font-bold text-stone-950">Fondant au chocolat</p>
                <p class="mt-1 text-sm text-stone-600">Un cœur coulant, un affichage échappé, zéro script indésirable.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-cream py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-4 rounded-[2rem] border border-orange-100 bg-white p-4 shadow-sm lg:grid-cols-[1fr_auto] lg:items-center">
            <label class="relative block">
                <span class="sr-only">Rechercher une recette</span>
                <input class="w-full rounded-full border border-orange-200 bg-orange-50 px-5 py-4 pr-12 text-stone-900 outline-none transition placeholder:text-stone-500 focus:border-tomato focus:ring-4 focus:ring-orange-200" data-recipe-search type="search" placeholder="Rechercher une recette, un ingrédient, une envie...">
                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-xl">⌕</span>
            </label>
            <div class="flex flex-wrap gap-2">
                <button class="rounded-full border border-tomato bg-tomato px-4 py-2 text-sm font-extrabold text-white transition" data-recipe-filter="all" type="button">Tout</button>
                <button class="rounded-full border border-orange-200 bg-white px-4 py-2 text-sm font-extrabold text-stone-700 transition" data-recipe-filter="entrees" type="button">Entrées</button>
                <button class="rounded-full border border-orange-200 bg-white px-4 py-2 text-sm font-extrabold text-stone-700 transition" data-recipe-filter="plats" type="button">Plats</button>
                <button class="rounded-full border border-orange-200 bg-white px-4 py-2 text-sm font-extrabold text-stone-700 transition" data-recipe-filter="desserts" type="button">Desserts</button>
                <button class="rounded-full border border-orange-200 bg-white px-4 py-2 text-sm font-extrabold text-stone-700 transition" data-recipe-filter="vegetarien" type="button">Végétarien</button>
            </div>
        </div>
    </div>
</section>

<section id="recettes" class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">À table</p>
            <h2 class="mt-2 font-serif text-4xl font-bold text-stone-950">Les recettes du moment</h2>
            <p class="mt-2 max-w-2xl text-stone-600">Des cartes gourmandes, une lecture rapide, et des protections web discrètes mais bien présentes.</p>
        </div>
        <a class="text-sm font-extrabold text-herb hover:text-tomato" href="/login.php">Connexion admin</a>
    </div>

    <?php if ($dbError): ?>
        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-amber-900"><?= e($dbError) ?></div>
    <?php elseif (!$recipes): ?>
        <div class="rounded-3xl border border-orange-100 bg-white p-5 text-stone-600">Aucune recette publiée pour le moment.</div>
    <?php else: ?>
        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($recipes as $recipe): ?>
                <?php $meta = $recipeMeta[$recipe['slug']] ?? ['category' => 'plats', 'label' => 'Recette', 'time' => '30 min', 'level' => 'Facile', 'tag' => 'Maison']; ?>
                <article class="group overflow-hidden rounded-[1.6rem] border border-orange-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:shadow-orange-900/10" data-recipe-card data-category="<?= e($meta['category']) ?>" data-search="<?= e($recipe['title'] . ' ' . $recipe['short_description'] . ' ' . $meta['label'] . ' ' . $meta['tag']) ?>">
                    <a href="/recipe.php?slug=<?= e($recipe['slug']) ?>" class="block">
                        <div class="relative">
                            <img class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
                            <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-extrabold text-tomato shadow-sm"><?= e($meta['label']) ?></span>
                        </div>
                        <div class="p-5">
                            <div class="mb-3 flex flex-wrap gap-2 text-xs font-extrabold text-stone-600">
                                <span class="rounded-full bg-orange-50 px-3 py-1"><?= e($meta['time']) ?></span>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-herb"><?= e($meta['level']) ?></span>
                                <span class="rounded-full bg-amber-50 px-3 py-1 text-amber-700"><?= e($meta['tag']) ?></span>
                            </div>
                            <h3 class="font-serif text-2xl font-bold leading-tight text-stone-950"><?= e($recipe['title']) ?></h3>
                            <p class="mt-3 min-h-16 text-sm leading-6 text-stone-600"><?= e($recipe['short_description']) ?></p>
                            <span class="mt-5 inline-flex font-extrabold text-tomato">Voir la recette →</span>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="mt-8 hidden rounded-3xl border border-orange-100 bg-white p-6 text-center text-stone-600" data-recipe-empty>Aucune recette ne correspond à votre recherche.</div>
    <?php endif; ?>
</section>

<section class="bg-[#fff1dc] py-14">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:items-center lg:px-8">
        <img class="aspect-[16/9] w-full rounded-[2rem] object-cover shadow-xl shadow-orange-900/10" src="/assets/img/recipes/ingredients-frais.webp" alt="Ingrédients frais sur un plan de travail">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-herb">Cuisine & sécurité</p>
            <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">Des ingrédients propres, des données propres.</h2>
            <p class="mt-4 text-lg leading-8 text-stone-700">Le public profite d’un site chaleureux. Le jury peut vérifier que l’administration reste protégée par PDO, CSRF, sessions sécurisées, CSP et upload contrôlé.</p>
            <div class="mt-6 flex flex-wrap gap-2 text-sm font-extrabold">
                <span class="rounded-full bg-white px-4 py-2 text-herb shadow-sm">XSS échappé</span>
                <span class="rounded-full bg-white px-4 py-2 text-tomato shadow-sm">PDO préparé</span>
                <span class="rounded-full bg-white px-4 py-2 text-amber-700 shadow-sm">CSRF vérifié</span>
            </div>
        </div>
    </div>
</section>
<?php public_footer(); ?>
