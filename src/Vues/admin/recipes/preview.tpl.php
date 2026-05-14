<?php render_flash(); ?>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <p class="text-primary fw-bold mb-1">Aperçu avant publication</p>
        <h1 class="display-font display-6 fw-bold mb-0"><?= e($recipe['title']) ?></h1>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-secondary" href="/admin/recettes/<?= e($recipe['id']) ?>/modifier">Modifier</a>
        <a class="btn btn-outline-secondary" href="/admin/recettes">Retour</a>
    </div>
</div>

<article class="lux-card lux-card-lg overflow-hidden">
    <div class="row g-0 align-items-stretch">
        <div class="col-lg-6 p-4 p-lg-5">
            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="badge-soft badge-tomato"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></span>
                <span class="badge-soft badge-herb"><?= e($meta['time']) ?></span>
                <span class="badge-soft"><?= e(recipe_statuses()[$recipe['status'] ?? 'draft'] ?? 'Brouillon') ?></span>
            </div>
            <h2 class="display-font display-5 fw-bold"><?= e($recipe['title']) ?></h2>
            <p class="lead-luxe fs-5"><?= e($recipe['description']) ?></p>
        </div>
        <div class="col-lg-6">
            <img class="w-100 h-100 object-fit-cover min-h-preview-img" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
        </div>
    </div>
    <div class="row g-4 p-4 p-lg-5 bg-light border-top">
        <section class="col-lg-6">
            <h3 class="display-font fs-2 fw-bold">Ingrédients</h3>
            <div class="rounded-4 bg-white p-4 lh-lg pre-line"><?= e($recipe['ingredients']) ?></div>
        </section>
        <section class="col-lg-6">
            <h3 class="display-font fs-2 fw-bold">Préparation</h3>
            <div class="vstack gap-3">
                <?php foreach (preg_split('/\R+/', (string) $recipe['preparation_steps']) as $index => $step): ?>
                    <?php if (trim($step) === '') { continue; } ?>
                    <div class="d-flex gap-3 rounded-4 bg-white p-3">
                        <span class="step-number"><?= e($index + 1) ?></span>
                        <p class="lh-lg mb-0"><?= e($step) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</article>
