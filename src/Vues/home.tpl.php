<section class="hero-section py-5">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="kicker">Cuisine familiale · Recettes de saison</span>
                <h1 class="display-3 fw-bold mt-4 mb-4">Des recettes maison, simples et généreuses.</h1>
                <p class="lead lead-luxe mb-4">Mijoté Maison rassemble des idées gourmandes pour tous les jours : entrées fraîches, plats réconfortants, desserts à partager et recettes faciles à refaire à la maison.</p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a class="btn btn-primary" href="/recettes">Voir toutes les recettes</a>
                    <a class="btn btn-outline-secondary" href="#apercu-recettes">Découvrir la sélection</a>
                </div>
                <div class="row g-3 text-center">
                    <div class="col-4"><div class="stat-card"><strong><?= e($totalRecipes > 0 ? (string) $totalRecipes : '10') ?></strong><span class="small text-muted">recettes</span></div></div>
                    <div class="col-4"><div class="stat-card"><strong><?= e((string) count(recipe_categories())) ?></strong><span class="small text-muted">catégories</span></div></div>
                    <div class="col-4"><div class="stat-card"><strong>15-45</strong><span class="small text-muted">minutes</span></div></div>
                </div>
            </div>
            <div class="col-lg-6 position-relative pb-4">
                <img class="hero-img" src="/assets/img/recipes/hero-cuisine-familiale.webp" alt="Table conviviale avec plusieurs plats maison">
                <div class="floating-note d-none d-md-block">
                    <p class="kicker mb-2">Recette du moment</p>
                    <p class="display-font fs-3 fw-bold mb-1">Fondant au chocolat</p>
                    <p class="small text-muted mb-0">Un cœur coulant, une boule de glace, et le dessert disparaît vite.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-soft py-5">
    <div class="container py-lg-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <span class="kicker">Bienvenue</span>
                <h2 class="display-font display-6 fw-bold mt-3">Une page d'accueil claire pour trouver vite une bonne idée.</h2>
                <p class="lead-luxe mb-0">Le site présente une sélection de recettes accessibles au grand public. Chaque carte affiche le titre, une image et une courte description, puis renvoie vers une page recette détaillée.</p>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-md-4">
                        <article class="lux-card h-100 p-4">
                            <p class="fs-1 mb-3">🍲</p>
                            <h3 class="display-font fs-3 fw-bold">Entrées</h3>
                            <p class="text-muted mb-0">Veloutés, salades et idées fraîches.</p>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="lux-card h-100 p-4">
                            <p class="fs-1 mb-3">🍝</p>
                            <h3 class="display-font fs-3 fw-bold">Plats</h3>
                            <p class="text-muted mb-0">Recettes simples pour le quotidien.</p>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="lux-card h-100 p-4">
                            <p class="fs-1 mb-3">🍫</p>
                            <h3 class="display-font fs-3 fw-bold">Desserts</h3>
                            <p class="text-muted mb-0">Douceurs familiales à partager.</p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="apercu-recettes" class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
            <div>
                <span class="kicker">À table</span>
                <h2 class="display-font display-6 fw-bold mt-2">Quelques recettes à découvrir</h2>
                <p class="text-muted mb-0">Un aperçu des recettes disponibles, avec navigation vers les pages détaillées.</p>
            </div>
            <a class="btn btn-outline-secondary" href="/recettes">Voir la liste complète</a>
        </div>

        <?php if ($dbError): ?>
            <div class="alert alert-warning rounded-4"><?= e($dbError) ?></div>
        <?php elseif (!$recipes): ?>
            <div class="alert alert-light border rounded-4">Aucune recette publiée pour le moment.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($recipes as $recipe): ?>
                    <?php $meta = recipe_public_meta($recipe['slug'] ?? null); ?>
                    <?php $rating = $ratingSummaries[(int) $recipe['id']] ?? ['average' => 0, 'count' => 0]; ?>
                    <div class="col-md-6 col-lg-4">
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
        <?php endif; ?>
    </div>
</section>

<?php if (!$dbError && $popularRecipes): ?>
<section class="section-blue-soft py-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
            <div>
                <span class="kicker">Les plus consultées</span>
                <h2 class="display-font display-6 fw-bold mt-2">Recettes populaires</h2>
                <p class="text-muted mb-0">Un classement simple basé sur le nombre de vues enregistrées sur chaque page recette.</p>
            </div>
            <a class="btn btn-outline-secondary" href="/recettes">Explorer toutes les recettes</a>
        </div>
        <div class="row g-4">
            <?php foreach ($popularRecipes as $rank => $recipe): ?>
                <?php $rating = $ratingSummaries[(int) $recipe['id']] ?? ['average' => 0, 'count' => 0]; ?>
                <div class="col-md-6 col-lg-3">
                    <a class="recipe-card d-block text-reset p-3" href="<?= e(recipe_url((string) $recipe['slug'])) ?>">
                        <div class="position-relative overflow-hidden rounded-4">
                            <img src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
                            <span class="position-absolute top-0 start-0 m-3 badge rounded-pill text-bg-primary">#<?= e((string) ($rank + 1)) ?></span>
                        </div>
                        <h3 class="display-font fs-4 fw-bold mt-3"><?= e($recipe['title']) ?></h3>
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <?= render_stars((float) $rating['average'], 'stars stars-sm') ?>
                            <span><?= e(number_format((float) $rating['average'], 1, ',', ' ')) ?>/5</span>
                        </div>
                        <p class="fw-bold text-primary mt-2 mb-0"><?= e((string) ($recipe['view_count'] ?? 0)) ?> vues</p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <img class="hero-img" src="/assets/img/recipes/ingredients-frais.webp" alt="Ingrédients frais sur un plan de travail">
            </div>
            <div class="col-lg-6">
                <span class="kicker">Dans le panier</span>
                <h2 class="display-font display-6 fw-bold mt-3">Des ingrédients frais, des recettes faciles à aimer.</h2>
                <p class="lead-luxe">Chaque recette est pensée pour être lisible et pratique : une belle photo, une liste d'ingrédients claire et des étapes simples.</p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge-soft badge-herb">Produits frais</span>
                    <span class="badge-soft badge-tomato">Cuisine maison</span>
                    <span class="badge-soft">À partager</span>
                </div>
            </div>
        </div>
    </div>
</section>
