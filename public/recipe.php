<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';

$recipe = null;
$error = null;

try {
    $repo = new RecipeRepository(db());
    if (isset($_GET['id'])) {
        $recipe = $repo->find((int) $_GET['id']);
    } elseif (isset($_GET['slug'])) {
        $recipe = $repo->findBySlug((string) $_GET['slug']);
    }
} catch (Throwable $exception) {
    $error = 'Impossible de charger la recette pour le moment.';
}

$meta = $recipe ? recipe_public_meta($recipe['slug'] ?? null) : null;

$og = null;
$jsonLd = null;
if ($recipe) {
    $og = [
        'type' => 'article',
        'title' => $recipe['title'] . ' — Mijoté Maison',
        'description' => $recipe['short_description'],
        'image' => recipe_image_url($recipe['image_path']),
    ];
    $ingredients = preg_split('/\r?\n/', (string) ($recipe['ingredients'] ?? ''));
    $ingredients = array_values(array_filter(array_map('trim', $ingredients), 'strlen'));
    $steps = preg_split('/\r?\n/', (string) ($recipe['preparation_steps'] ?? ''));
    $steps = array_values(array_filter(array_map('trim', $steps), 'strlen'));
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'Recipe',
        'name' => $recipe['title'],
        'image' => [recipe_image_url($recipe['image_path'])],
        'description' => $recipe['short_description'],
        'author' => ['@type' => 'Organization', 'name' => 'Mijoté Maison'],
        'recipeCategory' => recipe_category_label($recipe['category'] ?? null),
        'recipeYield' => $meta['servings'] ?? null,
        'totalTime' => isset($meta['time']) ? 'PT' . preg_replace('/\D/', '', $meta['time']) . 'M' : null,
        'recipeIngredient' => $ingredients,
        'recipeInstructions' => array_map(static function ($step) {
            return ['@type' => 'HowToStep', 'text' => $step];
        }, $steps),
    ];
    $jsonLd = array_filter($jsonLd, static function ($v) { return $v !== null && $v !== ''; });
}

public_header($recipe['title'] ?? 'Recette', $og);
if ($jsonLd) {
    echo '<script type="application/ld+json" nonce="' . e(csp_nonce()) . '">' . json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}
?>
<section class="bg-[#fff1dc]">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <a class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-extrabold text-herb shadow-sm hover:text-tomato" href="/recettes">← Retour aux recettes</a>
    </div>
</section>

<?php if ($error): ?>
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-amber-900"><?= e($error) ?></div>
    </section>
<?php elseif (!$recipe): ?>
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-orange-100 bg-white p-8 shadow-sm">
            <h1 class="font-serif text-4xl font-bold text-stone-950">Recette introuvable</h1>
            <p class="mt-3 text-stone-600">La recette demandée n'existe pas ou n'est plus disponible.</p>
        </div>
    </section>
<?php else: ?>
    <article>
        <section class="bg-[#fff1dc] pb-12">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[1.08fr_.92fr] lg:items-center lg:px-8">
                <div>
                    <div class="flex flex-wrap gap-2 text-sm font-extrabold">
                        <span class="rounded-full bg-white px-4 py-2 text-tomato shadow-sm"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></span>
                        <span class="rounded-full bg-white px-4 py-2 text-herb shadow-sm"><?= e($meta['time']) ?></span>
                        <span class="rounded-full bg-white px-4 py-2 text-amber-700 shadow-sm"><?= e($meta['level']) ?></span>
                    </div>
                    <h1 class="mt-6 max-w-4xl font-serif text-5xl font-bold leading-tight text-stone-950 sm:text-7xl"><?= e($recipe['title']) ?></h1>
                    <p class="mt-5 max-w-2xl text-xl leading-9 text-stone-700"><?= e($recipe['description']) ?></p>
                </div>
                <img class="aspect-[4/3] w-full rounded-[2rem] object-cover shadow-2xl shadow-orange-900/20" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[.82fr_1.18fr]">
                <aside class="space-y-6">
                    <div class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-sm">
                        <h2 class="font-serif text-3xl font-bold text-stone-950">En bref</h2>
                        <dl class="mt-5 grid gap-3 text-sm">
                            <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3"><dt class="font-extrabold text-stone-600">Temps</dt><dd class="font-extrabold text-tomato"><?= e($meta['time']) ?></dd></div>
                            <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3"><dt class="font-extrabold text-stone-600">Difficulté</dt><dd class="font-extrabold text-herb"><?= e($meta['level']) ?></dd></div>
                            <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3"><dt class="font-extrabold text-stone-600">Portions</dt><dd class="font-extrabold text-stone-900"><?= e($meta['servings']) ?></dd></div>
                            <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3"><dt class="font-extrabold text-stone-600">Ambiance</dt><dd class="font-extrabold text-amber-700"><?= e($meta['season']) ?></dd></div>
                        </dl>
                    </div>

                    <div class="rounded-[2rem] border border-emerald-100 bg-emerald-50 p-6 text-herb">
                        <p class="text-sm font-extrabold uppercase tracking-[0.16em]">Astuce maison</p>
                        <p class="mt-3 leading-7">Préparez les ingrédients avant de commencer : la recette devient plus fluide et la cuisson plus régulière.</p>
                    </div>
                </aside>

                <div class="space-y-8">
                    <section class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="font-serif text-3xl font-bold text-stone-950">Ingrédients</h2>
                        <div class="mt-6 whitespace-pre-line rounded-3xl bg-orange-50 p-6 text-lg leading-9 text-stone-700"><?= e($recipe['ingredients']) ?></div>
                    </section>
                    <section class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="font-serif text-3xl font-bold text-stone-950">Préparation</h2>
                        <div class="mt-6 space-y-4">
                            <?php foreach (preg_split('/\R+/', (string) $recipe['preparation_steps']) as $index => $step): ?>
                                <?php if (trim($step) === '') { continue; } ?>
                                <div class="grid gap-4 rounded-3xl bg-[#fff7ed] p-5 sm:grid-cols-[3rem_1fr]">
                                    <span class="grid h-12 w-12 place-items-center rounded-full bg-tomato text-lg font-extrabold text-white"><?= e($index + 1) ?></span>
                                    <p class="text-lg leading-8 text-stone-700"><?= e($step) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </article>
<?php endif; ?>
<?php public_footer(); ?>
