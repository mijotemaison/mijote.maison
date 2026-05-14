<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title><?= e(($recipe['title'] ?? 'Recette') . ' - version imprimable - Mijoté Maison') ?></title>
    <link rel="stylesheet" href="<?= e(versioned_asset('assets/vendor/bootstrap/css/bootstrap.min.css')) ?>">
    <link rel="stylesheet" href="<?= e(versioned_asset('assets/css/app.css')) ?>">
</head>
<body class="bg-white">
<main class="container container-print py-4">
    <div class="print-hidden d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom pb-4 mb-4">
        <a class="btn btn-outline-secondary" href="<?= $recipe ? e(recipe_url((string) $recipe['slug'])) : '/recettes' ?>">Retour à la recette</a>
        <p class="small fw-bold text-muted mb-0">Utiliser Ctrl+P ou le menu du navigateur pour imprimer.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-warning rounded-4"><?= e($error) ?></div>
    <?php elseif (!$recipe): ?>
        <section class="border rounded-4 p-5">
            <h1 class="display-font display-6 fw-bold">Recette introuvable</h1>
            <p class="text-muted mb-0">La recette demandée n’existe pas ou n’est plus disponible.</p>
        </section>
    <?php else: ?>
        <article>
            <header class="border-bottom pb-4">
                <p class="kicker">Mijoté Maison · Version imprimable</p>
                <h1 class="display-font display-4 fw-bold mt-3"><?= e($recipe['title']) ?></h1>
                <p class="lead-luxe"><?= e($recipe['short_description']) ?></p>
                <dl class="row g-3 small fw-bold">
                    <div class="col-sm-3"><div class="border rounded-4 p-3"><dt class="text-muted">Catégorie</dt><dd class="mb-0"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></dd></div></div>
                    <div class="col-sm-3"><div class="border rounded-4 p-3"><dt class="text-muted">Temps</dt><dd class="mb-0"><?= e($meta['time'] ?? '30 min') ?></dd></div></div>
                    <div class="col-sm-3"><div class="border rounded-4 p-3"><dt class="text-muted">Difficulté</dt><dd class="mb-0"><?= e($meta['level'] ?? 'Facile') ?></dd></div></div>
                    <div class="col-sm-3"><div class="border rounded-4 p-3"><dt class="text-muted">Portions</dt><dd class="mb-0"><?= e($meta['servings'] ?? '4 personnes') ?></dd></div></div>
                </dl>
            </header>

            <section class="row g-4 py-4">
                <div class="col-md-5"><img class="w-100 rounded-4 object-fit-cover ratio-recipe-print" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt=""></div>
                <div class="col-md-7">
                    <h2 class="display-font fs-2 fw-bold">Description</h2>
                    <p class="lh-lg text-muted"><?= e($recipe['description']) ?></p>
                </div>
            </section>

            <section class="row g-4 border-top pt-4">
                <div class="col-md-5">
                    <h2 class="display-font fs-2 fw-bold">Ingrédients</h2>
                    <ul class="mt-3 lh-lg">
                        <?php foreach (preg_split('/\R+/', (string) $recipe['ingredients']) as $ingredient): ?>
                            <?php if (trim($ingredient) === '') { continue; } ?>
                            <li><?= e($ingredient) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-7">
                    <h2 class="display-font fs-2 fw-bold">Préparation</h2>
                    <ol class="mt-3 vstack gap-3">
                        <?php foreach (preg_split('/\R+/', (string) $recipe['preparation_steps']) as $index => $step): ?>
                            <?php if (trim($step) === '') { continue; } ?>
                            <li class="d-flex gap-3">
                                <span class="step-number"><?= e($index + 1) ?></span>
                                <p class="lh-lg mb-0"><?= e($step) ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </section>

            <footer class="mt-5 border-top pt-3 small text-muted">
                <p>Recette consultée sur Mijoté Maison. Les données affichées sont échappées côté serveur avant rendu HTML.</p>
            </footer>
        </article>
    <?php endif; ?>
</main>
</body>
</html>
