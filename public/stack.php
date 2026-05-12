<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$stackItems = [
    ['PHP natif', 'Construit les pages, traite les formulaires, démarre les sessions et appelle les fonctions de sécurité.'],
    ['MySQL', 'Stocke les administrateurs, les recettes, categories/statuts, notes, commentaires, vues, logs et tentatives de connexion.'],
    ['PDO', 'Fait le lien sécurisé entre PHP et MySQL avec des requêtes préparées.'],
    ['HTML', 'Structure les pages publiques, les formulaires et les tableaux du back-office.'],
    ['Tailwind CSS', 'Gère l’identité visuelle, les grilles responsive, les cartes et les formulaires.'],
    ['JavaScript vanilla', 'Ajoute la recherche de recettes, les filtres, le carrousel, les confirmations admin et la copie des extraits de code.'],
    ['Sessions PHP', 'Gardent l’état de connexion admin avec cookies HttpOnly, SameSite et Secure si HTTPS.'],
    ['Front controller', 'public/index.php centralise les URLs propres avec AltoRouter : /recettes, /recette/slug, /connexion, /presentation, /conformite et /stack.'],
    ['AltoRouter', 'Associe une URL et une méthode HTTP à une méthode de contrôleur, comme dans le support du prof.'],
    ['Apache / .htaccess', 'Permet sous Apache/MAMP, XAMPP ou LAMP de rediriger les URLs propres vers public/index.php quand le fichier demandé n’existe pas.'],
    ['MAMP / XAMPP / LAMP', 'Environnement local recommandé par le cours : MAMP sur Mac, WAMP/XAMPP/Laragon sur Windows, LAMP sur Linux.'],
    ['src/Controller', 'Prépare les données et choisit la vue à afficher pour les pages publiques.'],
    ['src/Model', 'Fournit des modèles métier simples qui appellent les repositories PDO existants.'],
    ['src/Vues', 'Contient les templates PHP affichant le HTML public.'],
    ['app/security', 'Regroupe CSRF, authentification, brute force, upload sécurisé et headers HTTP.'],
];

$responsibilities = [
    ['Front-office', 'Pages publiques pour consulter les recettes : accueil, liste, détail et présentation.'],
    ['Back-office', 'Zone admin protégée pour créer, modifier et supprimer recettes et administrateurs, modérer les commentaires et consulter le journal sécurité.'],
    ['Router', 'public/index.php utilise AltoRouter pour envoyer chaque URL vers un contrôleur, par exemple RecipeController::show pour /recette/{slug}.'],
    ['Base de données', 'Tables admins, recipes, recipe_ratings, recipe_comments, security_logs et login_attempts importées depuis database.sql.'],
    ['Audit', 'La table security_logs garde les connexions et actions sensibles; la page admin Journal permet filtrage, export CSV et nettoyage, et un script CLI automatise la retention.'],
    ['Repositories', 'Classes PHP qui exécutent les requêtes SQL préparées avec PDO.'],
    ['Validation', 'Contrôles serveur sur les champs recettes et administrateurs avant écriture en base.'],
    ['Sécurité', 'Protection XSS, SQLi, CSRF, brute force, sessions et upload image.'],
    ['Tests', 'PHPUnit vérifie les validations, protections, repositories de logs et nettoyage automatique.'],
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
                <a class="btn-secondary" href="/conformite">Voir la conformité</a>
                <a class="btn-secondary" href="/recettes">Voir les recettes</a>
            </div>
        </div>
        <div class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-xl shadow-orange-900/10">
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-herb">Schéma simple</p>
            <div class="mt-6 grid gap-3 text-center text-sm font-extrabold text-stone-700">
                <div class="rounded-2xl bg-orange-50 p-4">Visiteur ou admin</div>
                <div class="text-tomato">↓ requête HTTP</div>
                <div class="rounded-2xl bg-white p-4 shadow-sm">Apache/MAMP, XAMPP ou LAMP envoie vers public/index.php</div>
                <div class="text-tomato">↓ AltoRouter</div>
                <div class="rounded-2xl bg-white p-4 shadow-sm">Controller choisit la logique et charge la vue</div>
                <div class="text-tomato">↓ appels internes</div>
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl bg-emerald-50 p-4">Model / Repository PDO</div>
                    <div class="rounded-2xl bg-emerald-50 p-4">Sécurité</div>
                    <div class="rounded-2xl bg-emerald-50 p-4">Validation</div>
                </div>
                <div class="text-tomato">↓ requêtes préparées PDO</div>
                <div class="rounded-2xl bg-orange-50 p-4">MySQL stocke et renvoie les données</div>
                <div class="text-tomato">↓ affichage</div>
                <div class="rounded-2xl bg-white p-4 shadow-sm">Vue PHP + HTML/Tailwind + données échappées avec e()</div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-6 lg:grid-cols-[.9fr_1.1fr] lg:items-start">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Méthode du prof</p>
            <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">MAMP, Apache, front controller et MVC classique.</h2>
            <p class="mt-4 leading-8 text-stone-700">Le cours montre une architecture avec un point d’entrée unique, une réécriture d’URL via Apache et une séparation Controller / Model / Vue. Le projet suit maintenant cette logique sur le front-office public.</p>
            <p class="mt-4 leading-8 text-stone-700">Pour l’environnement, le prof cite MAMP sur macOS, WAMP/XAMPP/Laragon sur Windows, LAMP sur Linux, ou Docker. Le projet fonctionne avec Apache sous MAMP, XAMPP ou LAMP grâce à <code class="rounded bg-orange-50 px-2 py-1">public/.htaccess</code>, et aussi avec le serveur PHP intégré pour Railway.</p>
        </div>
        <div class="grid gap-4">
            <article class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <h3 class="font-serif text-2xl font-bold text-stone-950">Front controller</h3>
                <p class="mt-2 leading-7 text-stone-600"><code class="rounded bg-orange-50 px-2 py-1">public/index.php</code> reçoit les URLs propres et utilise <code class="rounded bg-orange-50 px-2 py-1">AltoRouter</code>. Exemple : <code class="rounded bg-orange-50 px-2 py-1">/recette/veloute-de-potimarron</code> appelle <code class="rounded bg-orange-50 px-2 py-1">RecipeController::show()</code>.</p>
            </article>
            <article class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <h3 class="font-serif text-2xl font-bold text-stone-950">Apache / MAMP / XAMPP / LAMP</h3>
                <p class="mt-2 leading-7 text-stone-600"><code class="rounded bg-orange-50 px-2 py-1">public/.htaccess</code> active <code class="rounded bg-orange-50 px-2 py-1">mod_rewrite</code> : si l’URL ne correspond pas à un vrai fichier, Apache l’envoie à <code class="rounded bg-orange-50 px-2 py-1">public/index.php</code>. Sur MAMP, XAMPP ou LAMP, le DocumentRoot doit pointer vers <code class="rounded bg-orange-50 px-2 py-1">public/</code>.</p>
            </article>
            <article class="rounded-[1.5rem] border border-orange-100 bg-white p-5 shadow-sm">
                <h3 class="font-serif text-2xl font-bold text-stone-950">MVC classique</h3>
                <p class="mt-2 leading-7 text-stone-600"><code class="rounded bg-orange-50 px-2 py-1">src/Controller</code> prépare les données, <code class="rounded bg-orange-50 px-2 py-1">src/Model</code> appelle les repositories PDO, et <code class="rounded bg-orange-50 px-2 py-1">src/Vues</code> affiche le HTML. Les protections transversales restent dans <code class="rounded bg-orange-50 px-2 py-1">app/security</code>.</p>
            </article>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="mb-8 max-w-3xl">
        <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Rôle des technologies</p>
        <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">Qui fait quoi dans la stack ?</h2>
        <p class="mt-3 text-stone-600">Chaque technologie a une responsabilité précise. Les contrôleurs préparent, les modèles demandent les données, les vues affichent, les repositories interrogent MySQL, et les fichiers de sécurité protègent les actions sensibles.</p>
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
            <p class="mt-3 text-stone-700">Cette lecture aide à expliquer le projet au jury sans entrer directement dans tous les fichiers. Pour la preuve critère par critère, la page Conformité reprend la grille officielle avec des extraits réels.</p>
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
