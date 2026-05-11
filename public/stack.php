<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$stackItems = [
    ['PHP natif', 'Construit les pages, traite les formulaires, démarre les sessions et appelle les fonctions de sécurité.'],
    ['MySQL', 'Stocke les administrateurs, les recettes, leurs categories/statuts, les notes, les commentaires et les tentatives de connexion.'],
    ['PDO', 'Fait le lien sécurisé entre PHP et MySQL avec des requêtes préparées.'],
    ['HTML', 'Structure les pages publiques, les formulaires et les tableaux du back-office.'],
    ['Tailwind CSS', 'Gère l’identité visuelle, les grilles responsive, les cartes et les formulaires.'],
    ['JavaScript vanilla', 'Ajoute la recherche de recettes, les filtres, le carrousel et la copie des extraits de code.'],
    ['Sessions PHP', 'Gardent l’état de connexion admin avec cookies HttpOnly et SameSite.'],
    ['Front controller', 'Centralise les URLs propres : /recettes, /recette/slug, /connexion, /presentation et /stack.'],
    ['Apache / .htaccess', 'Permet sous MAMP de rediriger les URLs propres vers le routeur quand le fichier demandé n’existe pas.'],
    ['app/security', 'Regroupe CSRF, authentification, brute force, upload sécurisé et headers HTTP.'],
];

$responsibilities = [
    ['Front-office', 'Pages publiques pour consulter les recettes : accueil, liste, détail et présentation.'],
    ['Back-office', 'Zone admin protégée pour créer, modifier et supprimer recettes et administrateurs.'],
    ['Router', 'public/router.php joue le rôle de front controller léger et envoie chaque URL vers la bonne page PHP, par exemple /recettes ou /recette/{slug}.'],
    ['Base de données', 'Tables admins, recipes, recipe_ratings, recipe_comments et login_attempts importées depuis database.sql.'],
    ['Repositories', 'Classes PHP qui exécutent les requêtes SQL préparées avec PDO.'],
    ['Validation', 'Contrôles serveur sur les champs recettes et administrateurs avant écriture en base.'],
    ['Sécurité', 'Protection XSS, SQLi, CSRF, brute force, sessions et upload image.'],
    ['Assets', 'Images WebP, logo SVG, CSS généré et JavaScript local.'],
    ['Documentation', 'README, PLAN et rapport sécurité avec extraits réels du projet.'],
];

public_header('Explication de la stack');
?>
<section class="bg-[#fff1dc] py-14">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:items-center lg:px-8">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Comprendre le projet</p>
            <h1 class="mt-3 font-serif text-5xl font-bold leading-tight text-stone-950 sm:text-6xl">Explication de la stack.</h1>
            <p class="mt-5 text-lg leading-8 text-stone-700">Une stack technique, c’est l’ensemble des technologies qui font tenir l’application : ce qui affiche les pages, ce qui stocke les données, ce qui sécurise les actions et ce qui rend l’interface agréable.</p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a class="btn-primary" href="/presentation">Voir la présentation</a>
                <a class="btn-secondary" href="/recettes">Voir les recettes</a>
            </div>
        </div>
        <div class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-xl shadow-orange-900/10">
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-herb">Schéma simple</p>
            <div class="mt-6 grid gap-3 text-center text-sm font-extrabold text-stone-700">
                <div class="rounded-2xl bg-orange-50 p-4">Visiteur ou admin</div>
                <div class="text-tomato">↓ requête HTTP</div>
                <div class="rounded-2xl bg-white p-4 shadow-sm">Apache/MAMP ou serveur PHP envoie vers public/router.php</div>
                <div class="text-tomato">↓ route propre</div>
                <div class="rounded-2xl bg-white p-4 shadow-sm">PHP traite la page ou le formulaire</div>
                <div class="text-tomato">↓ appels internes</div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl bg-emerald-50 p-4">Sécurité</div>
                    <div class="rounded-2xl bg-emerald-50 p-4">Validation</div>
                    <div class="rounded-2xl bg-emerald-50 p-4">Repositories</div>
                </div>
                <div class="text-tomato">↓ requêtes préparées PDO</div>
                <div class="rounded-2xl bg-orange-50 p-4">MySQL stocke et renvoie les données</div>
                <div class="text-tomato">↓ affichage</div>
                <div class="rounded-2xl bg-white p-4 shadow-sm">HTML + Tailwind + données échappées avec e()</div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[.9fr_1.1fr] lg:items-start">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Méthode du prof</p>
            <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">MAMP, Apache, front controller et MVC adapté.</h2>
            <p class="mt-4 leading-8 text-stone-700">Le cours montre une architecture avec un point d’entrée unique, une réécriture d’URL via Apache et une séparation Controller / Model / Vue. Le projet reprend cette logique sans casser le code déjà sécurisé.</p>
        </div>
        <div class="grid gap-4">
            <article class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <h3 class="font-serif text-2xl font-bold text-stone-950">Front controller</h3>
                <p class="mt-2 leading-7 text-stone-600"><code class="rounded bg-orange-50 px-2 py-1">public/router.php</code> reçoit les URLs propres et charge la page correspondante. Exemple : <code class="rounded bg-orange-50 px-2 py-1">/recette/veloute-de-potimarron</code> charge <code class="rounded bg-orange-50 px-2 py-1">public/recipe.php</code> avec le slug.</p>
            </article>
            <article class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <h3 class="font-serif text-2xl font-bold text-stone-950">Apache / MAMP</h3>
                <p class="mt-2 leading-7 text-stone-600"><code class="rounded bg-orange-50 px-2 py-1">public/.htaccess</code> active <code class="rounded bg-orange-50 px-2 py-1">mod_rewrite</code> : si l’URL ne correspond pas à un vrai fichier, Apache l’envoie au routeur. Sur MAMP, le DocumentRoot doit pointer vers <code class="rounded bg-orange-50 px-2 py-1">public/</code>.</p>
            </article>
            <article class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <h3 class="font-serif text-2xl font-bold text-stone-950">Équivalence MVC</h3>
                <p class="mt-2 leading-7 text-stone-600">Les repositories PDO jouent le rôle de Model, les pages PHP publiques/admin jouent le rôle de Controller léger et de Vue, et les fichiers <code class="rounded bg-orange-50 px-2 py-1">app/security</code> centralisent les protections transversales.</p>
            </article>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="mb-8 max-w-3xl">
        <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Rôle des technologies</p>
        <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">Qui fait quoi dans la stack ?</h2>
        <p class="mt-3 text-stone-600">Chaque technologie a une responsabilité précise. Le projet évite les mélanges : les pages affichent, les repositories interrogent MySQL, et les fichiers de sécurité protègent les actions sensibles.</p>
    </div>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <?php foreach ($stackItems as [$name, $description]): ?>
            <article class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <h3 class="font-serif text-2xl font-bold text-stone-950"><?= e($name) ?></h3>
                <p class="mt-3 text-sm leading-6 text-stone-600"><?= e($description) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-[#fff1dc] py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 max-w-3xl">
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-herb">Organisation du projet</p>
            <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">Qui fait quoi dans le code ?</h2>
            <p class="mt-3 text-stone-700">Cette lecture aide à expliquer le projet au jury sans entrer directement dans tous les fichiers.</p>
        </div>
        <div class="grid gap-5 md:grid-cols-2">
            <?php foreach ($responsibilities as [$name, $description]): ?>
                <article class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <span class="grid h-11 w-11 flex-none place-items-center rounded-2xl bg-orange-50 font-extrabold text-tomato"><?= e(mb_substr($name, 0, 1)) ?></span>
                        <div>
                            <h3 class="font-serif text-2xl font-bold text-stone-950"><?= e($name) ?></h3>
                            <p class="mt-2 leading-7 text-stone-600"><?= e($description) ?></p>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-xl shadow-orange-900/10 sm:p-8">
        <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Phrase simple pour l’oral</p>
        <blockquote class="mt-4 font-serif text-3xl font-bold leading-tight text-stone-950">PHP reçoit la demande, vérifie la sécurité, utilise PDO pour parler à MySQL, puis renvoie une page HTML stylée avec Tailwind et enrichie par JavaScript.</blockquote>
    </div>
</section>
<?php public_footer(); ?>
