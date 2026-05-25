<?php

declare(strict_types=1);

$stackItems = [
    ['PHP natif', 'Construit les pages, traite les formulaires, démarre les sessions et appelle les protections.'],
    ['MySQL', 'Stocke les administrateurs, recettes, catégories, notes, commentaires, vues, logs et tentatives de connexion.'],
    ['PDO', 'Relie PHP et MySQL avec des requêtes préparées pour éviter les injections SQL.'],
    ['HTML', 'Structure les pages publiques, les formulaires et les tableaux du back-office.'],
    ['Bootstrap', 'Apporte la grille responsive, les composants UI, les cartes, formulaires, tableaux, alertes et boutons.'],
    ['CSS applicatif', 'Personnalise Bootstrap pour obtenir une identité claire, premium et adaptée à un site de recettes.'],
    ['JavaScript vanilla', 'Ajoute la recherche, les filtres, le carrousel, les confirmations admin et la copie des extraits de code.'],
    ['Sessions PHP', 'Gardent l’état de connexion admin avec cookies HttpOnly, SameSite et Secure si HTTPS.'],
    ['Front controller', 'public/index.php centralise les URLs propres avec AltoRouter.'],
    ['AltoRouter', 'Associe une URL et une méthode HTTP à une méthode de contrôleur, comme dans la logique du cours.'],
    ['Apache / .htaccess', 'Sous MAMP, XAMPP ou LAMP, redirige les URLs propres vers public/index.php quand le fichier demandé n’existe pas.'],
    ['MAMP / XAMPP / LAMP', 'Environnements locaux cités dans le cours : MAMP sur Mac, XAMPP/WAMP/Laragon sur Windows, LAMP sur Linux.'],
    ['src/Controller', 'Prépare les données et choisit la vue à afficher.'],
    ['src/Model', 'Fournit des modèles métier simples qui appellent les repositories PDO existants.'],
    ['src/Vues', 'Contient les templates PHP qui affichent le HTML.'],
    ['src/Utils/Security', 'Regroupe CSRF, authentification, brute force, upload sécurisé et headers HTTP.'],
];

$responsibilities = [
    ['Front-office', 'Pages publiques pour consulter les recettes : accueil, liste, détail, impression et présentation.'],
    ['Back-office', 'Zone admin protégée pour gérer recettes, administrateurs, commentaires et journal sécurité.'],
    ['Router', 'public/index.php utilise AltoRouter pour envoyer chaque URL vers un contrôleur.'],
    ['Base de données', 'Tables admins, recipes, recipe_ratings, recipe_comments, security_logs et login_attempts.'],
    ['Audit', 'La table security_logs garde les connexions et actions sensibles; la page admin permet filtrage, export CSV et nettoyage.'],
    ['Repositories', 'Classes PHP qui exécutent les requêtes SQL préparées avec PDO.'],
    ['Validation', 'Contrôles serveur sur les champs recettes et administrateurs avant écriture en base.'],
    ['Sécurité', 'Protection XSS, SQLi, CSRF, brute force, sessions et upload image.'],
    ['Tests', 'PHPUnit vérifie les validations, protections, repositories de logs et nettoyage automatique.'],
    ['Assets', 'Images WebP, logo SVG, Bootstrap local, CSS applicatif et JavaScript local.'],
    ['Documentation', 'README, CODEX, rapport sécurité, présentation et conformité au sujet officiel.'],
];

public_header('Explication de la stack');
?>
<section class="section-blue-soft py-5">
    <div class="container py-lg-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <p class="kicker">Comprendre le projet</p>
                <h1 class="display-4 fw-black text-ink mb-4">Explication de la stack.</h1>
                <p class="lead text-secondary mb-4">Une stack technique, c’est l’ensemble des technologies qui font tenir l’application : affichage, base de données, sécurité, routing, design et interactions.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-primary btn-lg" href="/presentation">Voir la présentation</a>
                    <a class="btn btn-outline-primary btn-lg" href="/conformite">Voir la conformité</a>
                    <a class="btn btn-light btn-lg" href="/recettes">Voir les recettes</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="lux-card lux-card-lg p-4 p-lg-5">
                    <p class="kicker text-herb">Schéma simple</p>
                    <div class="stack-flow mt-4 text-center fw-bold">
                        <div class="stack-node stack-node-soft">Visiteur ou admin</div>
                        <div class="stack-arrow">↓ requête HTTP</div>
                        <div class="stack-node">Apache/MAMP, XAMPP ou LAMP envoie vers <code>public/index.php</code></div>
                        <div class="stack-arrow">↓ AltoRouter</div>
                        <div class="stack-node">Controller prépare la page ou le formulaire</div>
                        <div class="stack-arrow">↓ appels internes</div>
                        <div class="row g-3">
                            <div class="col-md-4"><div class="stack-node stack-node-green h-100">Model / Repository PDO</div></div>
                            <div class="col-md-4"><div class="stack-node stack-node-green h-100">Sécurité</div></div>
                            <div class="col-md-4"><div class="stack-node stack-node-green h-100">Validation</div></div>
                        </div>
                        <div class="stack-arrow">↓ requêtes préparées PDO</div>
                        <div class="stack-node stack-node-soft">MySQL stocke et renvoie les données</div>
                        <div class="stack-arrow">↓ affichage</div>
                        <div class="stack-node">Vue PHP + HTML/Bootstrap + données échappées avec <code>e()</code></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <p class="kicker">Méthode du prof</p>
                <h2 class="section-title mb-3">MAMP, Apache, front controller et MVC classique.</h2>
                <p class="text-secondary lh-lg">Le cours montre une architecture avec un point d’entrée unique, une réécriture d’URL via Apache et une séparation Controller / Model / Vue. Le projet suit cette logique sur le front-office public et le back-office admin.</p>
                <p class="text-secondary lh-lg">Le projet fonctionne avec Apache sous MAMP, XAMPP ou LAMP grâce à <code>public/.htaccess</code>, et aussi avec le serveur PHP intégré utilisé par Railway.</p>
            </div>
            <div class="col-lg-7">
                <div class="vstack gap-3">
                    <article class="lux-card p-4">
                        <h3 class="h4 font-display fw-black">Front controller</h3>
                        <p class="mb-0 text-secondary lh-lg"><code>public/index.php</code> reçoit les URLs propres et utilise <code>AltoRouter</code>. Exemple : <code>/recette/veloute-de-potimarron</code> appelle <code>RecipeController::show()</code>.</p>
                    </article>
                    <article class="lux-card p-4">
                        <h3 class="h4 font-display fw-black">Apache / MAMP / XAMPP / LAMP</h3>
                        <p class="mb-0 text-secondary lh-lg"><code>public/.htaccess</code> active <code>mod_rewrite</code> : si l’URL ne correspond pas à un vrai fichier, Apache l’envoie à <code>public/index.php</code>. Le DocumentRoot doit pointer vers <code>public/</code>.</p>
                    </article>
                    <article class="lux-card p-4">
                        <h3 class="h4 font-display fw-black">MVC classique</h3>
                        <p class="mb-0 text-secondary lh-lg"><code>src/Controller</code> prépare les données, <code>src/Model</code> appelle les repositories PDO, et <code>src/Vues</code> affiche le HTML. Les protections transversales restent dans <code>src/Utils/Security</code>.</p>
                    </article>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-soft py-5">
    <div class="container">
        <div class="mb-4 col-lg-8">
            <p class="kicker">Rôle des technologies</p>
            <h2 class="section-title">Qui fait quoi dans la stack ?</h2>
            <p class="text-secondary">Chaque technologie a une responsabilité précise. Les contrôleurs préparent, les modèles demandent les données, les vues affichent, les repositories interrogent MySQL, et les fichiers de sécurité protègent les actions sensibles.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($stackItems as [$name, $description]): ?>
                <div class="col-md-6 col-xl-3">
                    <article class="lux-card h-100 p-4">
                        <h3 class="h4 font-display fw-black"><?= e($name) ?></h3>
                        <p class="small text-secondary lh-lg mb-0"><?= e($description) ?></p>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="mb-4 col-lg-8">
            <p class="kicker text-herb">Organisation du projet</p>
            <h2 class="section-title">Qui fait quoi dans le code ?</h2>
            <p class="text-secondary">Cette lecture aide à expliquer le projet au jury sans entrer directement dans tous les fichiers. Pour la preuve critère par critère, la page Conformité reprend la grille officielle avec des extraits réels.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($responsibilities as [$name, $description]): ?>
                <div class="col-md-6">
                    <article class="lux-card h-100 p-4">
                        <div class="d-flex gap-3">
                            <span class="icon-bubble flex-shrink-0"><?= e(mb_substr($name, 0, 1)) ?></span>
                            <div>
                                <h3 class="h4 font-display fw-black"><?= e($name) ?></h3>
                                <p class="text-secondary lh-lg mb-0"><?= e($description) ?></p>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-blue-soft py-5">
    <div class="container">
        <div class="lux-card lux-card-lg p-4 p-lg-5">
            <p class="kicker">Phrase simple pour l’oral</p>
            <blockquote class="blockquote fs-3 font-display fw-black text-ink mb-0">PHP reçoit la demande, vérifie la sécurité, utilise PDO pour parler à MySQL, puis renvoie une page HTML structurée avec Bootstrap, personnalisée par CSS et enrichie par JavaScript.</blockquote>
        </div>
    </div>
</section>
<?php public_footer(); ?>
