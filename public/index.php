<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/RecipeRepository.php';

$recipes = [];
$dbError = null;

try {
    $recipes = (new RecipeRepository(db()))->all();
} catch (Throwable $exception) {
    $dbError = 'Base de donnees indisponible. Verifiez la configuration MySQL et importez database.sql.';
}

public_header('Accueil');
?>
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid gap-10 lg:grid-cols-[1.1fr_.9fr] lg:items-center">
        <div>
            <span class="inline-flex rounded-full border border-cyan-300/30 bg-cyan-300/10 px-3 py-1 text-sm font-medium text-cyan-100">Projet final cybersécurité GRETA 92</span>
            <h1 class="mt-6 max-w-4xl text-4xl font-bold tracking-normal text-white sm:text-6xl">Recettes de cuisine, back-office verrouille, securite visible.</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">Secure Recipes GRETA 92 presente un site public de recettes avec une administration protegee et des controles concrets contre XSS, SQLi, CSRF, brute force et uploads dangereux.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a class="btn-primary" href="#recettes">Voir les recettes</a>
                <a class="btn-secondary" href="/presentation.php">Ouvrir la presentation</a>
            </div>
        </div>
        <div class="panel-card p-6">
            <div class="grid grid-cols-2 gap-3 text-sm">
                <?php foreach (['XSS echappe', 'PDO prepare', 'CSRF token', 'CSP active', 'Brute force', 'Upload filtre'] as $badge): ?>
                    <div class="rounded-lg border border-white/10 bg-slate-950/60 p-4 text-cyan-100"><?= e($badge) ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section id="recettes" class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-3xl font-bold text-white">Recettes publiques</h2>
            <p class="mt-2 text-slate-400">Consultation libre, aucune action sensible exposee au public.</p>
        </div>
        <a class="text-sm font-medium text-cyan-200 hover:text-cyan-100" href="/login.php">Connexion admin</a>
    </div>

    <?php if ($dbError): ?>
        <div class="panel-card p-5 text-amber-100"><?= e($dbError) ?></div>
    <?php elseif (!$recipes): ?>
        <div class="panel-card p-5 text-slate-300">Aucune recette publiee pour le moment.</div>
    <?php else: ?>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($recipes as $recipe): ?>
                <article class="panel-card overflow-hidden">
                    <img class="h-52 w-full object-cover" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
                    <div class="p-5">
                        <h3 class="text-xl font-semibold text-white"><?= e($recipe['title']) ?></h3>
                        <p class="mt-3 min-h-16 text-sm leading-6 text-slate-300"><?= e($recipe['short_description']) ?></p>
                        <a class="btn-primary mt-5 w-full" href="/recipe.php?slug=<?= e($recipe['slug']) ?>">Voir la recette</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php public_footer(); ?>
