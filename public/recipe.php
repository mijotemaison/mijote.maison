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
    $error = 'Base de donnees indisponible.';
}

public_header($recipe['title'] ?? 'Recette');
?>
<section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
    <a class="text-sm font-medium text-cyan-200 hover:text-cyan-100" href="/">Retour aux recettes</a>

    <?php if ($error): ?>
        <div class="panel-card mt-6 p-5 text-amber-100"><?= e($error) ?></div>
    <?php elseif (!$recipe): ?>
        <div class="panel-card mt-6 p-8">
            <h1 class="text-3xl font-bold text-white">Recette introuvable</h1>
            <p class="mt-3 text-slate-300">La recette demandee n'existe pas ou n'est plus disponible.</p>
        </div>
    <?php else: ?>
        <article class="mt-6 overflow-hidden rounded-lg border border-white/10 bg-white/[0.06]">
            <img class="h-72 w-full object-cover sm:h-96" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
            <div class="p-6 sm:p-8">
                <span class="inline-flex rounded-full border border-cyan-300/30 bg-cyan-300/10 px-3 py-1 text-sm text-cyan-100">Donnees echappees contre XSS</span>
                <h1 class="mt-5 text-4xl font-bold text-white"><?= e($recipe['title']) ?></h1>
                <p class="mt-4 text-lg leading-8 text-slate-300"><?= e($recipe['description']) ?></p>
                <div class="mt-8 grid gap-6 lg:grid-cols-2">
                    <section class="rounded-lg border border-white/10 bg-slate-950/50 p-5">
                        <h2 class="text-xl font-semibold text-white">Ingredients</h2>
                        <p class="mt-4 whitespace-pre-line leading-7 text-slate-300"><?= e($recipe['ingredients']) ?></p>
                    </section>
                    <section class="rounded-lg border border-white/10 bg-slate-950/50 p-5">
                        <h2 class="text-xl font-semibold text-white">Preparation</h2>
                        <p class="mt-4 whitespace-pre-line leading-7 text-slate-300"><?= e($recipe['preparation_steps']) ?></p>
                    </section>
                </div>
            </div>
        </article>
    <?php endif; ?>
</section>
<?php public_footer(); ?>
