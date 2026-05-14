<section class="section-soft py-4">
    <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3">
        <a class="btn btn-outline-secondary" href="/recettes">← Retour aux recettes</a>
        <?php if ($recipe): ?>
            <a class="btn btn-outline-secondary print-hidden" href="<?= e(recipe_print_url((string) $recipe['slug'])) ?>">Version imprimable</a>
        <?php endif; ?>
    </div>
</section>
<?php render_flash(); ?>

<?php if ($error): ?>
    <section class="py-5">
        <div class="container">
            <div class="alert alert-warning rounded-4"><?= e($error) ?></div>
        </div>
    </section>
<?php elseif (!$recipe): ?>
    <section class="py-5">
        <div class="container">
            <div class="lux-card lux-card-lg p-5">
                <h1 class="display-font display-6 fw-bold">Recette introuvable</h1>
                <p class="text-muted mb-0">La recette demandée n'existe pas ou n'est plus disponible.</p>
            </div>
        </div>
    </section>
<?php else: ?>
    <article>
        <section class="hero-section py-5">
            <div class="container">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="badge-soft badge-tomato"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></span>
                            <span class="badge-soft badge-herb"><?= e($meta['time']) ?></span>
                            <span class="badge-soft"><?= e($meta['level']) ?></span>
                            <span class="badge-soft"><?= e((string) ($recipe['view_count'] ?? 0)) ?> vues</span>
                        </div>
                        <h1 class="display-3 fw-bold mb-4"><?= e($recipe['title']) ?></h1>
                        <p class="lead lead-luxe"><?= e($recipe['description']) ?></p>
                    </div>
                    <div class="col-lg-6">
                        <img class="recipe-detail-img" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="row g-4">
                    <aside class="col-lg-4">
                        <div class="lux-card p-4 mb-4">
                            <h2 class="display-font fs-2 fw-bold">En bref</h2>
                            <dl class="vstack gap-3 small mb-0 mt-4">
                                <div class="d-flex justify-content-between rounded-4 bg-light p-3"><dt>Temps</dt><dd class="fw-bold text-primary mb-0"><?= e($meta['time']) ?></dd></div>
                                <div class="d-flex justify-content-between rounded-4 bg-light p-3"><dt>Difficulté</dt><dd class="fw-bold text-success mb-0"><?= e($meta['level']) ?></dd></div>
                                <div class="d-flex justify-content-between rounded-4 bg-light p-3"><dt>Portions</dt><dd class="fw-bold mb-0"><?= e($meta['servings']) ?></dd></div>
                                <div class="d-flex justify-content-between rounded-4 bg-light p-3"><dt>Ambiance</dt><dd class="fw-bold text-warning mb-0"><?= e($meta['season']) ?></dd></div>
                                <div class="d-flex justify-content-between rounded-4 bg-light p-3"><dt>Vues</dt><dd class="fw-bold mb-0"><?= e((string) ($recipe['view_count'] ?? 0)) ?></dd></div>
                            </dl>
                            <div class="border rounded-4 p-3 mt-4 bg-white">
                                <p class="kicker mb-2">Note des lecteurs</p>
                                <div class="d-flex align-items-center gap-3">
                                    <?= render_stars((float) $ratingSummary['average'], 'stars stars-lg') ?>
                                    <span class="fw-bold"><?= e($ratingSummary['count'] > 0 ? number_format((float) $ratingSummary['average'], 1, ',', ' ') . '/5' : 'Aucune note') ?></span>
                                </div>
                                <p class="small text-muted mb-0"><?= e((string) $ratingSummary['count']) ?> avis enregistré(s)</p>
                            </div>
                        </div>

                        <div class="lux-card p-4 bg-success-subtle border-success-subtle">
                            <p class="kicker mb-2">Astuce maison</p>
                            <p class="mb-0">Préparez les ingrédients avant de commencer : la recette devient plus fluide et la cuisson plus régulière.</p>
                        </div>
                    </aside>

                    <div class="col-lg-8">
                        <section class="lux-card p-4 p-lg-5 mb-4">
                            <h2 class="display-font fs-2 fw-bold">Ingrédients</h2>
                            <div class="rounded-4 bg-light p-4 fs-5 lh-lg mt-4 pre-line"><?= e($recipe['ingredients']) ?></div>
                        </section>
                        <section class="lux-card p-4 p-lg-5">
                            <h2 class="display-font fs-2 fw-bold">Préparation</h2>
                            <div class="vstack gap-3 mt-4">
                                <?php foreach (preg_split('/\R+/', (string) $recipe['preparation_steps']) as $index => $step): ?>
                                    <?php if (trim($step) === '') { continue; } ?>
                                    <div class="d-flex gap-3 rounded-4 bg-light p-4">
                                        <span class="step-number"><?= e($index + 1) ?></span>
                                        <p class="fs-5 lh-lg mb-0"><?= e($step) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>

        <section id="avis" class="section-blue-soft py-5">
            <div class="container">
                <div class="row g-4">
                    <aside class="col-lg-5">
                        <div class="lux-card lux-card-lg p-4 p-lg-5">
                            <span class="kicker">Avis lecteurs</span>
                            <h2 class="display-font display-6 fw-bold mt-3">Donner une note</h2>
                            <p class="text-muted">Votre note aide les prochains visiteurs à choisir une recette. Elle peut être modifiée si vous votez à nouveau.</p>
                            <form class="vstack gap-3" method="post" action="<?= e(recipe_url((string) $recipe['slug'])) ?>#avis">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="rate">
                                <div class="d-flex flex-wrap gap-2" role="group" aria-label="Noter la recette">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <button class="btn <?= $userRating === $i ? 'btn-warning' : 'btn-outline-warning' ?>" type="submit" name="rating" value="<?= e($i) ?>" aria-label="Noter <?= e($i) ?> sur 5">
                                            <?= str_repeat('★', $i) ?>
                                        </button>
                                    <?php endfor; ?>
                                </div>
                                <?php if ($userRating): ?>
                                    <p class="small fw-bold text-success mb-0">Votre note actuelle : <?= e($userRating) ?>/5.</p>
                                <?php endif; ?>
                            </form>

                            <hr class="my-4">

                            <h3 class="display-font fs-2 fw-bold">Laisser un commentaire</h3>
                            <p class="small text-muted">Les commentaires sont relus avant publication pour garder une page utile et agréable.</p>
                            <form class="vstack gap-3" method="post" action="<?= e(recipe_url((string) $recipe['slug'])) ?>#avis">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="comment">
                                <label class="visually-hidden" aria-hidden="true">Site web
                                    <input name="website" tabindex="-1" autocomplete="off">
                                </label>
                                <div>
                                    <label class="form-label fw-bold" for="author_name">Nom</label>
                                    <input class="form-control" id="author_name" name="author_name" maxlength="80" required>
                                </div>
                                <div>
                                    <label class="form-label fw-bold" for="content">Commentaire</label>
                                    <textarea class="form-control" id="content" name="content" rows="5" maxlength="800" required></textarea>
                                </div>
                                <button class="btn btn-primary" type="submit">Envoyer le commentaire</button>
                            </form>
                        </div>
                    </aside>

                    <div class="col-lg-7">
                        <div class="lux-card lux-card-lg p-4 p-lg-5">
                            <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4">
                                <div>
                                    <span class="kicker">Commentaires</span>
                                    <h2 class="display-font display-6 fw-bold mt-2">Ce qu’en pensent les lecteurs</h2>
                                </div>
                                <span class="badge-soft badge-tomato"><?= e((string) count($comments)) ?> publié(s)</span>
                            </div>
                            <div class="vstack gap-3">
                                <?php if (!$comments): ?>
                                    <p class="alert alert-light border rounded-4 mb-0">Aucun commentaire publié pour le moment.</p>
                                <?php endif; ?>
                                <?php foreach ($comments as $comment): ?>
                                    <article class="border rounded-4 bg-white p-4">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                            <h3 class="display-font fs-3 fw-bold mb-0"><?= e($comment['author_name']) ?></h3>
                                            <time class="small fw-bold text-muted" datetime="<?= e($comment['created_at']) ?>"><?= e(date('d/m/Y', strtotime((string) $comment['created_at']))) ?></time>
                                        </div>
                                        <p class="mt-3 mb-0 lh-lg pre-line"><?= e($comment['content']) ?></p>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
<?php endif; ?>
<?php public_footer(); ?>
