<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title><?= e(($recipe['title'] ?? 'Recette') . ' - version imprimable - Mijoté Maison') ?></title>
    <link rel="stylesheet" href="<?= e(versioned_asset('assets/css/output.css')) ?>">
</head>
<body class="bg-white text-stone-950 antialiased">
<main class="mx-auto max-w-4xl px-6 py-8">
    <div class="print:hidden mb-8 flex flex-wrap items-center justify-between gap-3 border-b border-stone-200 pb-5">
        <a class="btn-secondary" href="<?= $recipe ? e(recipe_url((string) $recipe['slug'])) : '/recettes' ?>">Retour à la recette</a>
        <p class="text-sm font-bold text-stone-500">Utiliser Ctrl+P ou le menu du navigateur pour imprimer.</p>
    </div>

    <?php if ($error): ?>
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-amber-900"><?= e($error) ?></div>
    <?php elseif (!$recipe): ?>
        <section class="rounded-2xl border border-stone-200 p-8">
            <h1 class="font-serif text-4xl font-bold">Recette introuvable</h1>
            <p class="mt-3 text-stone-600">La recette demandée n’existe pas ou n’est plus disponible.</p>
        </section>
    <?php else: ?>
        <article>
            <header class="border-b border-stone-300 pb-6">
                <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-tomato">Mijoté Maison · Version imprimable</p>
                <h1 class="mt-3 font-serif text-5xl font-bold leading-tight"><?= e($recipe['title']) ?></h1>
                <p class="mt-4 text-lg leading-8 text-stone-700"><?= e($recipe['short_description']) ?></p>
                <dl class="mt-5 grid gap-3 text-sm font-bold sm:grid-cols-4">
                    <div class="rounded-xl border border-stone-200 p-3"><dt class="text-stone-500">Catégorie</dt><dd><?= e(recipe_category_label($recipe['category'] ?? null)) ?></dd></div>
                    <div class="rounded-xl border border-stone-200 p-3"><dt class="text-stone-500">Temps</dt><dd><?= e($meta['time'] ?? '30 min') ?></dd></div>
                    <div class="rounded-xl border border-stone-200 p-3"><dt class="text-stone-500">Difficulté</dt><dd><?= e($meta['level'] ?? 'Facile') ?></dd></div>
                    <div class="rounded-xl border border-stone-200 p-3"><dt class="text-stone-500">Portions</dt><dd><?= e($meta['servings'] ?? '4 personnes') ?></dd></div>
                </dl>
            </header>

            <section class="grid gap-8 py-8 sm:grid-cols-[.9fr_1.1fr]">
                <img class="aspect-[4/3] w-full rounded-2xl object-cover" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
                <div>
                    <h2 class="font-serif text-3xl font-bold">Description</h2>
                    <p class="mt-4 leading-8 text-stone-700"><?= e($recipe['description']) ?></p>
                </div>
            </section>

            <section class="grid gap-8 border-t border-stone-300 pt-8 sm:grid-cols-[.9fr_1.1fr]">
                <div>
                    <h2 class="font-serif text-3xl font-bold">Ingrédients</h2>
                    <ul class="mt-5 space-y-2">
                        <?php foreach (preg_split('/\R+/', (string) $recipe['ingredients']) as $ingredient): ?>
                            <?php if (trim($ingredient) === '') { continue; } ?>
                            <li class="leading-7">• <?= e($ingredient) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h2 class="font-serif text-3xl font-bold">Préparation</h2>
                    <ol class="mt-5 space-y-4">
                        <?php foreach (preg_split('/\R+/', (string) $recipe['preparation_steps']) as $index => $step): ?>
                            <?php if (trim($step) === '') { continue; } ?>
                            <li class="grid gap-3 sm:grid-cols-[2.5rem_1fr]">
                                <span class="grid h-10 w-10 place-items-center rounded-full border border-stone-300 font-extrabold"><?= e($index + 1) ?></span>
                                <p class="leading-7"><?= e($step) ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </section>

            <footer class="mt-10 border-t border-stone-300 pt-5 text-sm text-stone-500">
                <p>Recette consultée sur Mijoté Maison. Les données affichées sont échappées côté serveur avant rendu HTML.</p>
            </footer>
        </article>
    <?php endif; ?>
</main>
</body>
</html>
