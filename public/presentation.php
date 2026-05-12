<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

function render_code_panel(string $title, string $file, string $code): void
{
    $id = 'code-' . md5($title . $file);
    echo '<div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-950 shadow-lg">';
    echo '<div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-800 bg-slate-900 px-4 py-3">';
    echo '<div><p class="text-sm font-extrabold text-white">' . e($title) . '</p><p class="text-xs text-slate-400">' . e($file) . '</p></div>';
    echo '<button class="rounded-full border border-slate-700 px-3 py-1.5 text-xs font-extrabold text-slate-200 transition hover:bg-slate-800" type="button" data-copy-code="' . e($id) . '">Copier</button>';
    echo '</div>';
    echo '<pre class="max-h-72 overflow-auto p-4 text-[0.78rem] leading-6 text-slate-100"><code id="' . e($id) . '">' . e(trim($code)) . '</code></pre>';
    echo '</div>';
}

$slides = [
    [
        'kicker' => 'Ouverture',
        'title' => 'Mijoté Maison',
        'lead' => 'Projet final GRETA 92 : un site de recettes public avec un back-office administrateur sécurisé.',
        'oral' => 'Je présente une application complète, pas seulement une maquette. Le visiteur consulte les recettes, et l’administrateur gère le contenu dans une zone protégée.',
        'points' => ['Front-office public orienté recettes.', 'Back-office réservé aux administrateurs.', 'Sécurité intégrée dans le code PHP, la base et les formulaires.'],
        'files' => ['public/index.php', 'public/recipes.php', 'admin/dashboard.php'],
        'test' => 'Vérification : accueil, liste recettes, détail recette, login et dashboard répondent correctement.',
    ],
    [
        'kicker' => 'Contexte',
        'title' => 'Le sujet demandé',
        'lead' => 'Le PDF demande une page d’accueil, une page recette, une page de connexion et un back-office admin.',
        'oral' => 'Le site respecte la séparation attendue : la partie publique ne permet que la consultation, tandis que les actions sensibles sont dans le back-office.',
        'points' => ['Page d’accueil avec présentation et aperçu de recettes.', 'Page liste pour naviguer vers chaque recette.', 'Page recette détaillée séparée.', 'Page de connexion clairement nommée.'],
        'files' => ['public/index.php', 'public/recipes.php', 'public/recipe.php', 'public/login.php'],
        'test' => 'Vérification : /, /recettes, /recette/veloute-de-potimarron et /connexion répondent en HTTP 200. Les anciennes URLs .php restent compatibles.',
    ],
    [
        'kicker' => 'Objectif',
        'title' => 'Développer un vrai site sécurisé',
        'lead' => 'L’objectif est de montrer un produit utilisable et de prouver que les protections web sont comprises.',
        'oral' => 'Chaque protection est visible dans le projet : il y a un fichier ou une fonction identifiable pour l’authentification, SQLi, XSS, CSRF, brute force et upload.',
        'points' => ['Fonctionnalités réelles : CRUD recettes et admins.', 'Données stockées en MySQL.', 'Explications techniques appuyées par des extraits réels.'],
        'files' => ['app/security/*', 'app/repositories/*', 'docs/rapport-securite.md'],
        'test' => 'Vérification : le rapport PDF et cette présentation citent les mêmes protections que le code.',
    ],
    [
        'kicker' => 'Stack',
        'title' => 'PHP, MySQL, Tailwind et JavaScript',
        'lead' => 'La stack reste conforme au sujet : PHP, HTML, JavaScript et MySQL. Bootstrap est remplacé par Tailwind CSS.',
        'oral' => 'PHP orchestre les pages, PDO sécurise l’accès à MySQL, Tailwind construit l’interface, et JavaScript ajoute l’interaction du carrousel et des filtres.',
        'points' => ['PHP natif structuré, sans framework lourd.', 'MySQL avec PDO et requêtes préparées.', 'Tailwind compilé localement pour le design.', 'JavaScript vanilla pour les interactions.'],
        'files' => ['package.json', 'tailwind.config.js', 'app/config/database.php'],
        'code' => [
            [
                'title' => 'Connexion PDO centralisée',
                'file' => 'app/config/database.php',
                'body' => <<<'PHP'
$pdo = new PDO($dsn, $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
PHP,
            ],
        ],
        'test' => 'Vérification : npm run build-css compile Tailwind et les routes PHP utilisent la connexion PDO.',
    ],
    [
        'kicker' => 'Architecture',
        'title' => 'Architecture selon la méthode du prof',
        'lead' => 'Le projet reprend la logique front controller, routes propres, Apache/MAMP possible et MVC classique.',
        'oral' => 'public/index.php est le point d’entrée principal. Il utilise AltoRouter comme dans la méthode du prof pour envoyer chaque URL vers un contrôleur. Les contrôleurs préparent les données, les modèles appellent les repositories PDO, et les vues affichent le HTML.',
        'points' => ['URLs propres : /recettes, /recette/{slug}, /connexion, /presentation, /conformite, /stack.', 'public/.htaccess active la réécriture sous Apache/MAMP.', 'src/Controller, src/Model et src/Vues suivent le MVC classique.', 'Les anciennes URLs .php restent compatibles.'],
        'files' => ['public/index.php', 'public/.htaccess', 'src/Controller/*', 'src/Model/*', 'src/Vues/*'],
        'code' => [
            [
                'title' => 'Route propre avec AltoRouter',
                'file' => 'public/index.php',
                'body' => <<<'PHP'
$router = new AltoRouter();
$router->map('GET', '/recettes', [RecipeController::class, 'index'], 'recipes');
$router->map('GET|POST', '/recette/[*:slug]', [RecipeController::class, 'show'], 'recipe_show');
PHP,
            ],
            [
                'title' => 'Controller vers Vue',
                'file' => 'src/Controller/RecipeController.php',
                'body' => <<<'PHP'
$recipeModel = new Recipe($pdo);
$recipes = $recipeModel->published($perPage, ($page - 1) * $perPage, $query, $category);
$this->render('recipes', compact('recipes', 'ratingSummaries', 'query', 'category'));
PHP,
            ],
            [
                'title' => 'Réécriture Apache/MAMP',
                'file' => 'public/.htaccess',
                'body' => <<<'APACHE'
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
APACHE,
            ],
        ],
        'test' => 'Vérification : le serveur PHP démarre avec public/index.php, les routes AltoRouter répondent en 200 et les anciennes URLs .php restent disponibles.',
    ],
    [
        'kicker' => 'Front-office',
        'title' => 'La partie publique',
        'lead' => 'Le visiteur voit une page d’accueil, une liste de recettes et une page détaillée par recette.',
        'oral' => 'Le front-office ressemble à un vrai site de recettes. Il affiche les données en lecture seule et ne propose aucune action sensible.',
        'points' => ['Accueil avec présentation du site.', 'Recettes populaires basées sur les vues.', 'Recherche serveur, filtres catégorie et pagination.', 'Notes étoiles, commentaires approuvés et impression recette.'],
        'files' => ['public/index.php', 'public/recipes.php', 'public/recipe.php'],
        'code' => [
            [
                'title' => 'Recherche publique préparée',
                'file' => 'app/repositories/RecipeRepository.php',
                'body' => <<<'PHP'
public function published(int $limit = 12, int $offset = 0, string $query = '', string $category = ''): array
{
    [$where, $params] = $this->publicFilters($query, $category);
    $sql = 'SELECT * FROM recipes ' . $where . ' ORDER BY published_at DESC LIMIT :limit OFFSET :offset';
    $stmt = $this->pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}
PHP,
            ],
        ],
        'test' => 'Vérification : / affiche les populaires, /recette/{slug} incrémente les vues, imprime la recette, et permet notation + commentaire modéré.',
    ],
    [
        'kicker' => 'Back-office',
        'title' => 'La partie administrateur',
        'lead' => 'Le back-office permet de gérer les recettes et les administrateurs après connexion.',
        'oral' => 'Toutes les actions sensibles passent par des formulaires POST avec CSRF. Le public ne peut pas créer, modifier ou supprimer.',
        'points' => ['Dashboard avec statistiques et journal sécurité.', 'CRUD recettes avec upload image.', 'Aperçu avant publication et duplication en brouillon.', 'Modération des commentaires lecteurs.', 'Journal sécurité filtrable par type, recherche, dates, avec export CSV.'],
        'files' => ['admin/dashboard.php', 'admin/recipes/*', 'admin/comments/index.php', 'admin/security-logs/index.php', 'app/repositories/SecurityLogRepository.php'],
        'test' => 'Vérification : accès /admin/dashboard.php sans session redirige vers /connexion ; duplication crée un brouillon et écrit un log consultable dans /admin/security-logs/index.php.',
    ],
    [
        'kicker' => 'Authentification',
        'title' => 'Protéger l’accès admin',
        'lead' => 'Le mot de passe n’est jamais stocké en clair, et la session est régénérée après connexion.',
        'oral' => 'La connexion vérifie un hash avec password_verify. Ensuite login_admin() régénère l’identifiant de session pour limiter la fixation de session. Les anciens hashes peuvent être réhachés automatiquement vers l’algorithme courant.',
        'points' => ['Argon2id utilisé pour les nouveaux mots de passe si disponible.', 'password_verify() utilisé au login.', 'Rehash automatique si un ancien hash est détecté.', 'session_regenerate_id(true) après succès.', 'require_admin() protège les pages admin.'],
        'files' => ['src/Controller/AuthController.php', 'app/security/auth.php', 'admin/admins/*'],
        'code' => [
            [
                'title' => 'Vérification du mot de passe hashé',
                'file' => 'src/Controller/AuthController.php',
                'body' => <<<'PHP'
$adminModel = new Admin($pdo);
$admin = filter_var($email, FILTER_VALIDATE_EMAIL) ? $adminModel->findByEmail($email) : null;
$valid = $admin && password_verify($password, (string) $admin['password_hash']);

if (!$valid) {
    flash('error', 'Identifiants invalides.');
    redirect('/connexion');
}

if (admin_password_needs_rehash((string) $admin['password_hash'])) {
    $adminModel->updatePasswordHash((int) $admin['id'], admin_password_hash($password));
}

login_admin($admin);
PHP,
            ],
            [
                'title' => 'Session régénérée et accès protégé',
                'file' => 'app/security/auth.php',
                'body' => <<<'PHP'
function login_admin(array $admin): void
{
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $admin['id'];
    $_SESSION['admin_email'] = (string) $admin['email'];
}

function require_admin(): void
{
    if (!is_admin_authenticated()) {
        redirect('/connexion');
    }
}
PHP,
            ],
        ],
        'test' => 'Vérification : mauvais login refusé, login admin valide redirige vers le dashboard.',
    ],
    [
        'kicker' => 'Injection SQL',
        'title' => 'Empêcher une saisie de devenir du SQL',
        'lead' => 'Les variables utilisateur ne sont jamais concaténées dans les requêtes.',
        'oral' => 'Les repositories utilisent PDO prepare et execute. Le SQL garde des marqueurs comme :slug, et les valeurs sont envoyées séparément.',
        'points' => ['prepare() prépare la requête.', 'execute() injecte les valeurs comme paramètres.', 'PDO::ATTR_EMULATE_PREPARES désactivé.', 'Même logique pour lecture, création, modification et suppression.'],
        'files' => ['app/repositories/RecipeRepository.php', 'app/repositories/AdminRepository.php'],
        'code' => [
            [
                'title' => 'Lecture paramétrée par slug',
                'file' => 'app/repositories/RecipeRepository.php',
                'body' => <<<'PHP'
public function findBySlug(string $slug): ?array
{
    $stmt = $this->pdo->prepare("SELECT * FROM recipes WHERE slug = :slug AND status = 'published' LIMIT 1");
    $stmt->execute(['slug' => $slug]);
    $recipe = $stmt->fetch();

    return $recipe ?: null;
}
PHP,
            ],
        ],
        'test' => 'Vérification : tentative SQLi sur le login refusée sans contourner l’authentification.',
    ],
    [
        'kicker' => 'XSS et CSP',
        'title' => 'Afficher les données sans exécuter de script',
        'lead' => 'Les données issues de la base sont échappées avec e() et la CSP limite les scripts.',
        'oral' => 'La protection XSS se fait au moment de l’affichage. Si une recette contient du HTML ou du JavaScript, il est transformé en texte visible et non exécuté.',
        'points' => ['htmlspecialchars avec ENT_QUOTES.', 'Aucun HTML brut autorisé dans les recettes.', 'CSP centralisée dans headers.php.', 'Scripts limités aux fichiers locaux.'],
        'files' => ['app/helpers/functions.php', 'src/Vues/recipe.tpl.php', 'app/security/headers.php'],
        'code' => [
            [
                'title' => 'Helper d’échappement',
                'file' => 'app/helpers/functions.php',
                'body' => <<<'PHP'
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
PHP,
            ],
            [
                'title' => 'CSP centralisée',
                'file' => 'app/security/headers.php',
                'body' => <<<'PHP'
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");
PHP,
            ],
        ],
        'test' => 'Vérification : tentative XSS affichée comme texte et CSP présente dans les headers HTTP.',
    ],
    [
        'kicker' => 'CSRF',
        'title' => 'Vérifier l’intention avant une action sensible',
        'lead' => 'Chaque formulaire sensible contient un token aléatoire vérifié côté serveur.',
        'oral' => 'Un site externe ne peut pas deviner le token stocké en session. Sans token valide, l’action est refusée avant toute modification.',
        'points' => ['Token généré avec random_bytes().', 'Champ caché csrf_token dans les formulaires.', 'hash_equals() pour comparer.', 'Suppressions uniquement en POST.'],
        'files' => ['app/security/csrf.php', 'admin/recipes/delete.php', 'admin/admins/delete.php'],
        'code' => [
            [
                'title' => 'Token CSRF',
                'file' => 'app/security/csrf.php',
                'body' => <<<'PHP'
function generate_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals((string) $_SESSION['csrf_token'], $token);
}
PHP,
            ],
        ],
        'test' => 'Vérification : suppression avec token absent ou invalide refusée.',
    ],
    [
        'kicker' => 'Brute force',
        'title' => 'Limiter les essais répétés sur le login',
        'lead' => 'Les tentatives de connexion sont journalisées et le login est bloqué après plusieurs échecs récents.',
        'oral' => 'Le but est de ralentir un robot qui teste beaucoup de mots de passe. Le blocage se base sur les échecs récents liés à l’email et à l’IP.',
        'points' => ['Table login_attempts.', 'Email, IP, user agent, succès ou échec.', 'Blocage après 5 échecs sur 15 minutes.', 'Message générique au login.'],
        'files' => ['app/security/brute_force.php', 'app/repositories/LoginAttemptRepository.php', 'src/Controller/AuthController.php'],
        'code' => [
            [
                'title' => 'Seuil de blocage',
                'file' => 'app/security/brute_force.php',
                'body' => <<<'PHP'
const MAX_LOGIN_FAILURES = 5;
const LOGIN_WINDOW_MINUTES = 15;

function login_is_blocked(PDO $pdo, string $email): bool
{
    $repo = new LoginAttemptRepository($pdo);
    $failures = $repo->countRecentFailures($email, client_ip(), LOGIN_WINDOW_MINUTES);

    return $failures >= MAX_LOGIN_FAILURES;
}
PHP,
            ],
        ],
        'test' => 'Vérification : après 5 échecs récents, la connexion est temporairement bloquée.',
    ],
    [
        'kicker' => 'Upload sécurisé',
        'title' => 'Accepter seulement de vraies images',
        'lead' => 'L’upload contrôle la taille, l’extension, le type MIME réel et le nom du fichier.',
        'oral' => 'On ne réutilise jamais le nom envoyé par l’utilisateur. Le fichier est renommé avec random_bytes et seules les extensions image autorisées passent.',
        'points' => ['Extensions autorisées : jpg, jpeg, png, webp.', 'MIME réel vérifié avec finfo.', 'Taille limitée à 2 Mo.', 'Nom de fichier aléatoire.'],
        'files' => ['app/security/upload.php', 'public/uploads/recipes/.htaccess'],
        'code' => [
            [
                'title' => 'Extension, MIME et taille',
                'file' => 'app/security/upload.php',
                'body' => <<<'PHP'
$maxSize = 2 * 1024 * 1024;
if (($file['size'] ?? 0) > $maxSize) {
    return ['path' => null, 'error' => 'Image trop lourde : limite 2 Mo.'];
}

$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
if (!in_array($extension, $allowedExtensions, true)) {
    return ['path' => null, 'error' => 'Extension refusee. Formats acceptes : jpg, jpeg, png, webp.'];
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($tmpPath) ?: '';
PHP,
            ],
        ],
        'test' => 'Vérification : upload .php refusé, image valide acceptée, fichier trop lourd refusé.',
    ],
    [
        'kicker' => 'Validation serveur',
        'title' => 'Refuser les données incohérentes',
        'lead' => 'La validation côté serveur protège même si le navigateur est contourné.',
        'oral' => 'Les champs obligatoires et les longueurs sont contrôlés avant insertion ou modification en base. C’est complémentaire à l’échappement.',
        'points' => ['Titre obligatoire et limité.', 'Description courte limitée.', 'Ingrédients et étapes obligatoires.', 'Email admin validé côté serveur.'],
        'files' => ['app/validation/recipe_validation.php', 'app/validation/admin_validation.php'],
        'code' => [
            [
                'title' => 'Validation recette',
                'file' => 'app/validation/recipe_validation.php',
                'body' => <<<'PHP'
if ($title === '' || mb_strlen($title) > 150) {
    $errors['title'] = 'Le titre est obligatoire et limite a 150 caracteres.';
}
if ($short === '' || mb_strlen($short) > 300) {
    $errors['short_description'] = 'La description courte est obligatoire et limitee a 300 caracteres.';
}
if ($steps === '' || mb_strlen($steps) > 7000) {
    $errors['preparation_steps'] = 'Les etapes sont obligatoires et limitees a 7000 caracteres.';
}
PHP,
            ],
        ],
        'test' => 'Vérification : formulaire incomplet refusé avec message propre.',
    ],
    [
        'kicker' => 'Tests',
        'title' => 'Ce qui a été vérifié',
        'lead' => 'Les tests manuels, HTTP, MySQL et PHPUnit couvrent les routes principales, les CRUD et les protections.',
        'oral' => 'J’ai testé l’application avec une vraie base MySQL temporaire, puis ajouté une suite PHPUnit pour vérifier automatiquement les fonctions sensibles et les repositories.',
        'points' => ['29 tests MySQL réels passés.', '12 tests PHPUnit / 25 assertions.', 'Lint PHP complet sans erreur.', 'CSS Tailwind reconstruit.', 'CSP vérifiée dans les headers.'],
        'files' => ['PLAN.md', 'README.md', 'docs/rapport-securite.md', 'tests/*'],
        'test' => 'Vérification : composer test, npm run build-css, lint PHP complet, HTTP 200 sur les pages publiques et login admin fonctionnel.',
    ],
    [
        'kicker' => 'Conclusion',
        'title' => 'Résultat final',
        'lead' => 'Le projet démontre une application web complète, structurée, présentable et défendable techniquement.',
        'oral' => 'La partie visible ressemble à un site de recettes, et la partie technique montre les protections demandées par le sujet. Chaque point important peut être relié à un fichier et à un extrait de code.',
        'points' => ['Sujet PDF respecté côté front-office et back-office.', 'Sécurité documentée avec extraits réels.', 'Page Conformité dédiée à la grille officielle.', 'Projet prêt à être présenté et testé.'],
        'files' => ['README.md', 'PLAN.md', 'docs/rapport-securite-projet-final-greta92.pdf'],
        'test' => 'Vérification : présentation exploitable en mode carrousel, slide par slide.',
    ],
];

public_header('Présentation');
?>
<section class="bg-[#fff1dc]">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <span class="text-sm font-extrabold uppercase tracking-[0.16em] text-tomato" data-counter>Slide 1 / <?= count($slides) ?></span>
                <h1 class="mt-2 font-serif text-4xl font-bold text-stone-950">Présentation jury</h1>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="presenter-bar" data-presenter-bar>
                    <button type="button" class="presenter-bar__btn" data-presenter-toggle aria-pressed="false" title="Afficher les repères de lecture">Repères</button>
                    <span class="presenter-bar__timer tabular" data-presenter-timer aria-label="Chronomètre">00:00</span>
                    <button type="button" class="presenter-bar__btn" data-presenter-reset title="Réinitialiser le chrono">↺</button>
                    <button type="button" class="presenter-bar__btn" data-presenter-fullscreen title="Plein écran">⛶</button>
                </div>
                <a class="btn-secondary" href="/">Accueil</a>
                <a class="btn-secondary" href="/stack">Stack</a>
                <a class="btn-secondary" href="/conformite">Conformité</a>
                <?php if (is_admin_authenticated()): ?>
                    <a class="btn-secondary" href="/admin/dashboard.php">Back-office</a>
                <?php endif; ?>
            </div>
        </div>

        <progress class="mb-5 h-2 w-full overflow-hidden rounded-full bg-white accent-tomato" value="1" max="<?= count($slides) ?>" data-progress></progress>

        <div class="overflow-hidden rounded-[2rem] border border-orange-100 bg-white shadow-xl shadow-orange-900/10" data-deck>
            <?php foreach ($slides as $index => $slide): ?>
                <article class="<?= $index === 0 ? 'grid' : 'hidden' ?> min-h-[620px] gap-8 p-6 lg:grid-cols-[.82fr_1.18fr] lg:p-9" data-slide>
                    <div class="flex flex-col justify-between gap-8">
                        <div>
                            <img class="h-16 w-16 rounded-3xl" src="/assets/img/logo-mijote-maison.svg" alt="">
                            <span class="mt-8 inline-flex rounded-full bg-orange-50 px-4 py-2 text-sm font-extrabold text-tomato">Slide <?= $index + 1 ?></span>
                            <p class="mt-6 text-sm font-extrabold uppercase tracking-[0.18em] text-herb"><?= e($slide['kicker']) ?></p>
                            <h2 class="mt-3 font-serif text-4xl font-bold leading-tight text-stone-950 sm:text-6xl"><?= e($slide['title']) ?></h2>
                            <p class="mt-5 text-xl leading-9 text-stone-700"><?= e($slide['lead']) ?></p>
                        </div>
                        <div class="rounded-3xl bg-[#fff7ed] p-5" data-presenter-only>
                            <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-tomato">Fil conducteur</p>
                            <p class="mt-3 leading-7 text-stone-700"><?= e($slide['oral']) ?></p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-orange-100 bg-orange-50 p-5">
                                <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-tomato">Points clés</p>
                                <ul class="mt-4 space-y-3 text-sm leading-6 text-stone-700">
                                    <?php foreach ($slide['points'] as $point): ?>
                                        <li class="flex gap-3"><span class="mt-2 h-2 w-2 flex-none rounded-full bg-tomato"></span><span><?= e($point) ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="rounded-3xl border border-emerald-100 bg-emerald-50 p-5">
                                <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-herb">Fichiers concernés</p>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <?php foreach ($slide['files'] as $file): ?>
                                        <span class="rounded-full bg-white px-3 py-1.5 text-xs font-extrabold text-stone-700 shadow-sm"><?= e($file) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <p class="mt-5 text-sm font-extrabold uppercase tracking-[0.16em] text-tomato">Preuve de test</p>
                                <p class="mt-2 text-sm leading-6 text-stone-700"><?= e($slide['test']) ?></p>
                            </div>
                        </div>

                        <?php if (!empty($slide['code'])): ?>
                            <div class="space-y-4">
                                <?php foreach ($slide['code'] as $block): ?>
                                    <?php render_code_panel($block['title'], $block['file'], $block['body']); ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="rounded-3xl border border-orange-100 bg-white p-6 shadow-sm">
                                <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-tomato">Lecture de la slide</p>
                                <p class="mt-3 leading-7 text-stone-700">Cette slide sert à poser le contexte et à relier la démonstration au sujet officiel. Les slides sécurité suivantes affichent directement les extraits de code.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="mt-6 flex justify-between gap-3">
            <button class="btn-secondary" type="button" data-prev>Précédent</button>
            <button class="btn-primary" type="button" data-next>Suivant</button>
        </div>
    </div>
</section>
<?php public_footer(); ?>
