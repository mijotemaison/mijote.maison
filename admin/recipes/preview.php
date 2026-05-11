<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';

require_admin();

$repo = new RecipeRepository(db());
$recipe = $repo->find((int) ($_GET['id'] ?? 0));

if (!$recipe) {
    flash('error', 'Recette introuvable.');
    redirect('/admin/recipes/index.php');
}

$meta = recipe_public_meta($recipe['slug'] ?? null);

admin_header('Apercu recette');
?>
<?php render_flash(); ?>
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-medium text-cyan-200">Apercu avant publication</p>
        <h1 class="mt-2 text-3xl font-bold text-white"><?= e($recipe['title']) ?></h1>
    </div>
    <div class="flex flex-wrap gap-2">
        <a class="btn-secondary" href="/admin/recipes/edit.php?id=<?= e($recipe['id']) ?>">Modifier</a>
        <a class="btn-secondary" href="/admin/recipes/index.php">Retour</a>
    </div>
</div>

<article class="overflow-hidden rounded-[2rem] border border-white/10 bg-white text-stone-950 shadow-xl">
    <div class="grid gap-0 lg:grid-cols-[1.05fr_.95fr]">
        <div class="p-6 sm:p-8">
            <div class="flex flex-wrap gap-2 text-sm font-extrabold">
                <span class="rounded-full bg-orange-50 px-4 py-2 text-tomato"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></span>
                <span class="rounded-full bg-emerald-50 px-4 py-2 text-herb"><?= e($meta['time']) ?></span>
                <span class="rounded-full bg-amber-50 px-4 py-2 text-amber-700"><?= e(recipe_statuses()[$recipe['status'] ?? 'draft'] ?? 'Brouillon') ?></span>
            </div>
            <h2 class="mt-6 font-serif text-5xl font-bold leading-tight text-stone-950"><?= e($recipe['title']) ?></h2>
            <p class="mt-5 text-xl leading-9 text-stone-700"><?= e($recipe['description']) ?></p>
        </div>
        <img class="h-full min-h-80 w-full object-cover" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
    </div>
    <div class="grid gap-6 border-t border-orange-100 bg-[#fffaf3] p-6 sm:p-8 lg:grid-cols-2">
        <section>
            <h3 class="font-serif text-3xl font-bold">Ingrédients</h3>
            <div class="mt-4 whitespace-pre-line rounded-3xl bg-white p-5 leading-8 text-stone-700"><?= e($recipe['ingredients']) ?></div>
        </section>
        <section>
            <h3 class="font-serif text-3xl font-bold">Préparation</h3>
            <div class="mt-4 space-y-3">
                <?php foreach (preg_split('/\R+/', (string) $recipe['preparation_steps']) as $index => $step): ?>
                    <?php if (trim($step) === '') { continue; } ?>
                    <div class="grid gap-3 rounded-3xl bg-white p-4 sm:grid-cols-[2.5rem_1fr]">
                        <span class="grid h-10 w-10 place-items-center rounded-full bg-tomato font-extrabold text-white"><?= e($index + 1) ?></span>
                        <p class="leading-7 text-stone-700"><?= e($step) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</article>
<?php admin_footer(); ?>
