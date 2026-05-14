<section class="hero-section py-5">
    <div class="container py-lg-4">
        <div class="col-lg-8">
            <span class="kicker">Toutes les recettes</span>
            <h1 class="display-4 fw-bold mt-3 mb-3">La liste complète des recettes.</h1>
            <p class="lead lead-luxe mb-0">Retrouvez les recettes de cuisine avec leur titre, image, courte description et un lien vers la page détaillée.</p>
        </div>
    </div>
</section>

<section class="section-soft py-4">
    <div class="container">
        <form class="recipe-search-panel" action="/recettes" method="get">
            <?php if ($category !== ''): ?>
                <input type="hidden" name="category" value="<?= e($category) ?>">
            <?php endif; ?>
            <div class="row g-3 align-items-center">
                <div class="col-12 col-xl-6">
                    <label class="visually-hidden" for="recipe-search">Rechercher une recette</label>
                    <div class="input-group input-group-lg">
                        <input id="recipe-search" class="form-control" name="q" value="<?= e($query) ?>" data-recipe-search type="search" maxlength="100" placeholder="Rechercher une recette ou un ingrédient...">
                        <button class="btn btn-primary" type="submit">OK</button>
                    </div>
                </div>
                <div class="col-12 col-xl-6">
                    <div class="d-flex flex-wrap gap-2 justify-content-xl-end">
                        <a class="btn <?= $category === '' ? 'btn-primary active' : 'btn-outline-secondary' ?>" href="<?= e(recipes_page_url($query !== '' ? ['q' => $query] : [])) ?>" data-recipe-filter="all">Tout</a>
                        <?php foreach (recipe_categories() as $value => $label): ?>
                            <a class="btn <?= $category === $value ? 'btn-primary active' : 'btn-outline-secondary' ?>" href="<?= e(recipes_page_url(array_filter(['q' => $query, 'category' => $value], static fn($v) => $v !== ''))) ?>" data-recipe-filter="<?= e($value) ?>"><?= e($label) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
            <div>
                <span class="kicker">À table</span>
                <h2 class="display-font display-6 fw-bold mt-2">Recettes disponibles</h2>
                <p class="text-muted mb-0"><?= e((string) $totalRecipes) ?> recette(s) publiée(s), avec recherche serveur, filtre catégorie et pagination.</p>
            </div>
            <a class="btn btn-outline-secondary" href="/">Retour à l'accueil</a>
        </div>

        <?php if ($dbError): ?>
            <div class="alert alert-warning rounded-4"><?= e($dbError) ?></div>
        <?php elseif (!$recipes): ?>
            <div class="alert alert-light border rounded-4">
                <?= e(($query !== '' || $category !== '') ? 'Aucune recette ne correspond à votre recherche ou au filtre choisi.' : 'Aucune recette publiée pour le moment.') ?>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($recipes as $recipe): ?>
                    <?php $meta = recipe_public_meta($recipe['slug'] ?? null); ?>
                    <?php $rating = $ratingSummaries[(int) $recipe['id']] ?? ['average' => 0, 'count' => 0]; ?>
                    <div class="col-md-6 col-lg-4" data-recipe-card data-category="<?= e($recipe['category'] ?? $meta['category']) ?>" data-search="<?= e($recipe['title'] . ' ' . $recipe['short_description'] . ' ' . $recipe['ingredients'] . ' ' . recipe_category_label($recipe['category'] ?? null) . ' ' . $meta['tag']) ?>">
                        <article class="recipe-card">
                            <a href="<?= e(recipe_url((string) $recipe['slug'])) ?>" class="text-reset">
                                <div class="position-relative overflow-hidden">
                                    <img src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
                                    <span class="recipe-badge"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></span>
                                </div>
                                <div class="p-4">
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <span class="meta-pill"><?= e($meta['time']) ?></span>
                                        <span class="meta-pill badge-herb"><?= e($meta['level']) ?></span>
                                        <span class="meta-pill badge-tomato"><?= e($meta['tag']) ?></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 small text-muted mb-3">
                                        <?= render_stars((float) $rating['average'], 'stars stars-sm') ?>
                                        <span><?= e($rating['count'] > 0 ? number_format((float) $rating['average'], 1, ',', ' ') . '/5 · ' . $rating['count'] . ' avis' : 'Pas encore notée') ?></span>
                                    </div>
                                    <h3 class="display-font fs-3 fw-bold"><?= e($recipe['title']) ?></h3>
                                    <p class="text-muted"><?= e($recipe['short_description']) ?></p>
                                    <span class="fw-bold text-primary">Voir la recette →</span>
                                </div>
                            </a>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($totalPages > 1): ?>
                <nav class="mt-5 d-flex flex-wrap align-items-center justify-content-center gap-2" aria-label="Pagination recettes">
                    <?php if ($page > 1): ?>
                        <a class="btn btn-outline-secondary" href="<?= e(recipes_page_url($baseParams + ['page' => $page - 1])) ?>">← Précédent</a>
                    <?php endif; ?>
                    <span class="badge-soft">Page <?= e($page) ?> / <?= e($totalPages) ?></span>
                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-outline-secondary" href="<?= e(recipes_page_url($baseParams + ['page' => $page + 1])) ?>">Suivant →</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
            <div class="alert alert-light border rounded-4 mt-4 text-center d-none" data-recipe-empty>Aucune recette ne correspond à votre recherche.</div>
        <?php endif; ?>
    </div>
</section>
<?php public_footer(); ?>
