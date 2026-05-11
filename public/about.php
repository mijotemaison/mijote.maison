<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

public_header('À propos');
?>
<section class="bg-[#fff1dc] py-14">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:items-center lg:px-8">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Mijoté Maison</p>
            <h1 class="mt-3 font-serif text-5xl font-bold leading-tight text-stone-950 sm:text-6xl">Des recettes simples, familiales et faciles à refaire.</h1>
            <p class="mt-5 text-lg leading-8 text-stone-700">Mijoté Maison rassemble des idées de cuisine du quotidien : des plats généreux, des desserts réconfortants et des recettes de saison pensées pour être lisibles dès la première visite.</p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a class="btn-primary" href="/recettes">Voir les recettes</a>
                <a class="btn-secondary" href="/">Retour accueil</a>
            </div>
        </div>
        <img class="aspect-[4/3] w-full rounded-[2rem] object-cover shadow-2xl shadow-orange-900/20" src="/assets/img/recipes/ingredients-frais.webp" alt="Ingrédients frais préparés sur une table">
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-5 md:grid-cols-3">
        <article class="rounded-[1.5rem] border border-orange-100 bg-white p-6 shadow-sm">
            <p class="text-3xl">🍲</p>
            <h2 class="mt-4 font-serif text-3xl font-bold text-stone-950">Cuisine accessible</h2>
            <p class="mt-3 leading-7 text-stone-600">Chaque recette garde une structure claire : une description courte, des ingrédients lisibles et des étapes séparées.</p>
        </article>
        <article class="rounded-[1.5rem] border border-orange-100 bg-white p-6 shadow-sm">
            <p class="text-3xl">🧺</p>
            <h2 class="mt-4 font-serif text-3xl font-bold text-stone-950">Produits du quotidien</h2>
            <p class="mt-3 leading-7 text-stone-600">Les recettes privilégient des ingrédients faciles à trouver et des préparations réalistes pour la maison.</p>
        </article>
        <article class="rounded-[1.5rem] border border-orange-100 bg-white p-6 shadow-sm">
            <p class="text-3xl">⭐</p>
            <h2 class="mt-4 font-serif text-3xl font-bold text-stone-950">Avis lecteurs</h2>
            <p class="mt-3 leading-7 text-stone-600">Les visiteurs peuvent noter les recettes et proposer un commentaire, affiché après validation.</p>
        </article>
    </div>
</section>
<?php public_footer(); ?>
