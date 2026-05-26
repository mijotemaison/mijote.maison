<?php

declare(strict_types=1);

function render_code_panel(string $title, string $file, string $code): void
{
    $id = 'code-' . md5($title . $file);
    echo '<div class="code-panel">';
    echo '<div class="code-panel__header">';
    echo '<div><p class="mb-1 small fw-black text-white">' . e($title) . '</p><p class="mb-0 small text-white-50">' . e($file) . '</p></div>';
    echo '<button class="btn btn-sm btn-outline-light" type="button" data-copy-code="' . e($id) . '">Copier</button>';
    echo '</div>';
    echo '<pre><code id="' . e($id) . '">' . e(trim($code)) . '</code></pre>';
    echo '</div>';
}

function render_guidance_panel(string $text, string $extraClass = ''): void
{
    $paragraphs = preg_split('/\R{2,}/', trim($text)) ?: [];
    $class = trim($extraClass);

    echo '<div class="slide-guidance-card' . ($class !== '' ? ' ' . e($class) : '') . '" data-presenter-only>';
    echo '<div class="d-flex flex-column flex-lg-row align-items-lg-start gap-3">';
    echo '<div class="slide-guidance-card__label">';
    echo '<p class="kicker mb-1">Fil conducteur</p>';
    echo '<span>Repère de soutenance</span>';
    echo '</div>';
    echo '<div class="slide-guidance-card__body">';

    foreach ($paragraphs as $paragraph) {
        echo '<p>' . e($paragraph) . '</p>';
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
}

$slides = [
    [
        'kicker' => 'Ouverture',
        'title' => 'Mijoté Maison',
        'lead' => 'Projet final GRETA 92 : un site de recettes public avec un back-office administrateur sécurisé.',
        'oral' => <<<'TEXT'
Cette première slide sert à poser le cadre : le projet n’est pas une simple vitrine statique, mais une application web complète avec des données, des pages publiques et une administration.

Je peux expliquer que le visiteur consulte uniquement les recettes, tandis que l’administrateur dispose d’un back-office séparé pour gérer le contenu. C’est exactement la logique demandée par le sujet : front-office public, back-office protégé et sécurité intégrée dès la conception.
TEXT,
        'points' => ['Front-office public orienté recettes.', 'Back-office réservé aux administrateurs.', 'Sécurité intégrée dans le code PHP, la base et les formulaires.'],
        'files' => ['public/index.php', 'src/Controller/RecipeController.php', 'src/Controller/Admin/DashboardController.php'],
        'test' => 'Vérification : accueil, liste recettes, détail recette, login et dashboard répondent correctement.',
    ],
    [
        'kicker' => 'Contexte',
        'title' => 'Le sujet demandé',
        'lead' => 'Le PDF demande une page d’accueil, une page recette, une page de connexion et un back-office admin.',
        'oral' => <<<'TEXT'
Cette slide relie directement le projet au PDF officiel. Le formateur attend trois pages publiques minimales : accueil, recette détaillée et connexion.

Dans le projet, l’accueil présente le site et les recettes, la page recette affiche le détail complet, et la page connexion sert uniquement à accéder au back-office. Aucune inscription publique n’a été ajoutée, car le sujet précise qu’il n’existe qu’un rôle : admin.
TEXT,
        'points' => ['Page d’accueil avec présentation et aperçu de recettes.', 'Page liste pour naviguer vers chaque recette.', 'Page recette détaillée séparée.', 'Page de connexion clairement nommée.'],
        'files' => ['public/index.php', 'src/Controller/RecipeController.php', 'src/Controller/AuthController.php', 'src/Vues/login.tpl.php'],
        'test' => 'Vérification : /, /recettes, /recette/veloute-de-potimarron et /connexion répondent en HTTP 200 via AltoRouter.',
    ],
    [
        'kicker' => 'Objectif',
        'title' => 'Développer un vrai site sécurisé',
        'lead' => 'L’objectif est de montrer un produit utilisable et de prouver que les protections web sont comprises.',
        'oral' => <<<'TEXT'
L’objectif n’est pas seulement de dire que le site est sécurisé, mais de montrer où les protections sont réellement codées. Chaque risque demandé par le sujet correspond à une partie identifiable du projet.

Pendant la soutenance, je peux passer de la fonctionnalité visible au fichier qui la protège : authentification, requêtes préparées, échappement, CSRF, limitation brute force et upload sécurisé. Cela rend la sécurité compréhensible et vérifiable par le jury.
TEXT,
        'points' => ['Fonctionnalités réelles : CRUD recettes et admins.', 'Données stockées en MySQL.', 'Explications techniques appuyées par des extraits réels.'],
        'files' => ['src/Utils/Security/*', 'src/Repository/*', 'docs/rapport-securite.md'],
        'test' => 'Vérification : le rapport PDF et cette présentation citent les mêmes protections que le code.',
    ],
    [
        'kicker' => 'Stack',
        'title' => 'PHP, MySQL, Bootstrap et JavaScript',
        'lead' => 'La stack suit le sujet officiel : PHP, HTML, JavaScript, Bootstrap et MySQL.',
        'oral' => <<<'TEXT'
Cette slide explique le rôle de chaque technologie sans entrer tout de suite dans le code. PHP reçoit la requête, choisit le contrôleur via AltoRouter, prépare les données et appelle la vue.

MySQL stocke les recettes, les administrateurs et les journaux de connexion. PDO sert d’intermédiaire sécurisé entre PHP et MySQL grâce aux requêtes préparées. Bootstrap structure l’interface, et JavaScript reste limité aux interactions comme le carrousel de présentation ou certains comportements visuels.
TEXT,
        'points' => ['PHP natif structuré, sans framework lourd.', 'MySQL avec PDO et requêtes préparées.', 'Bootstrap local, sans CDN, pour rester compatible avec la CSP.', 'JavaScript vanilla pour les interactions.'],
        'files' => ['package.json', 'public/assets/vendor/bootstrap', 'config/database.php'],
        'code' => [
            [
                'title' => 'Connexion PDO centralisée',
                'file' => 'config/database.php',
                'body' => <<<'PHP'
$pdo = new PDO($dsn, $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
PHP,
            ],
        ],
        'test' => 'Vérification : les assets Bootstrap locaux sont chargés et les routes PHP utilisent la connexion PDO.',
    ],
    [
        'kicker' => 'Architecture',
        'title' => 'Architecture selon la méthode du prof',
        'lead' => 'Le projet reprend la logique front controller, routes propres, Apache/MAMP possible et MVC classique.',
        'oral' => <<<'TEXT'
Cette slide est importante pour montrer que le projet suit la logique MVC vue en cours. Apache, MAMP, XAMPP, LAMP ou le serveur PHP envoient la requête vers public/index.php, qui devient le point d’entrée unique.

AltoRouter analyse ensuite l’URL et appelle le bon contrôleur. Le contrôleur prépare les données, le modèle ou repository interroge MySQL avec PDO, puis la vue affiche le HTML. Cette organisation évite les pages PHP isolées et rend le projet plus propre à maintenir.
TEXT,
        'points' => ['URLs propres : /recettes, /recette/{slug}, /connexion, /presentation, /conformite, /stack.', 'public/.htaccess active la réécriture sous Apache/MAMP, XAMPP ou LAMP.', 'src/Controller, src/Model et src/Vues suivent le MVC classique.', 'Le back-office passe aussi par AltoRouter.'],
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
        'test' => 'Vérification : le serveur PHP démarre avec public/index.php et les routes AltoRouter répondent en 200.',
    ],
    [
        'kicker' => 'Front-office',
        'title' => 'La partie publique',
        'lead' => 'Le visiteur voit une page d’accueil, une liste de recettes et une page détaillée par recette.',
        'oral' => <<<'TEXT'
Ici, je montre la partie que voit un visiteur classique. Elle doit ressembler à un vrai site de recettes : navigation simple, images, titres, descriptions courtes, page détail, recherche et filtres.

La sécurité se voit surtout dans ce que le visiteur ne peut pas faire : il peut consulter, chercher et lire, mais il ne peut ni créer, ni modifier, ni supprimer une recette. Toutes les données affichées viennent de MySQL et sont échappées avant d’arriver dans le HTML.
TEXT,
        'points' => ['Accueil avec présentation du site.', 'Recettes populaires basées sur les vues.', 'Recherche serveur, filtres catégorie et pagination.', 'Notes étoiles, commentaires approuvés et page impression dédiée.'],
        'files' => ['public/index.php', 'src/Controller/RecipeController.php', 'src/Vues/recipes.tpl.php', 'src/Vues/recipe.tpl.php'],
        'code' => [
            [
                'title' => 'Recherche publique préparée',
                'file' => 'src/Repository/RecipeRepository.php',
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
        'test' => 'Vérification : / affiche les populaires, /recette/{slug} incrémente les vues, /recette/{slug}/impression affiche une version imprimable, et la page détail permet notation + commentaire modéré.',
    ],
    [
        'kicker' => 'Back-office',
        'title' => 'La partie administrateur',
        'lead' => 'Le back-office permet de gérer les recettes et les administrateurs après connexion.',
        'oral' => <<<'TEXT'
Cette slide présente la partie réservée aux administrateurs. Le back-office sert à gérer le contenu réel du site : recettes, images, administrateurs, commentaires et journal sécurité.

La séparation avec le front-office est essentielle : une action sensible n’est disponible qu’après connexion admin. Les créations, modifications et suppressions passent par des formulaires POST avec un token CSRF, ce qui répond directement à la partie sécurité du sujet.
TEXT,
        'points' => ['Dashboard avec statistiques et journal sécurité.', 'CRUD recettes avec upload image.', 'Aperçu avant publication et duplication en brouillon.', 'Modération des commentaires lecteurs.', 'Journal sécurité filtrable par type, recherche, dates, avec export CSV.'],
        'files' => ['src/Controller/Admin/*', 'src/Vues/admin/*', 'src/Repository/SecurityLogRepository.php'],
        'test' => 'Vérification : accès /admin/dashboard sans session redirige vers /connexion ; duplication crée un brouillon et écrit un log consultable dans /admin/journal-securite.',
    ],
    [
        'kicker' => 'Authentification',
        'title' => 'Protéger l’accès admin',
        'lead' => 'Le mot de passe n’est jamais stocké en clair, et la session est régénérée après connexion.',
        'oral' => <<<'TEXT'
Cette slide explique comment l’accès au back-office est protégé. Le mot de passe administrateur n’est jamais comparé en clair : PHP vérifie le mot de passe saisi avec password_verify face au hash stocké en base.

Après une connexion réussie, la session est régénérée avec session_regenerate_id(true). Cela limite les risques de fixation de session. Le projet prévoit aussi un rehash automatique si un ancien hash doit être remis au standard courant.
TEXT,
        'points' => ['Argon2id utilisé pour les nouveaux mots de passe si disponible.', 'password_verify() utilisé au login.', 'Rehash automatique si un ancien hash est détecté.', 'session_regenerate_id(true) après succès.', 'require_admin() protège les pages admin.'],
        'files' => ['src/Controller/AuthController.php', 'src/Utils/Security/auth.php', 'src/Controller/Admin/AdminUserController.php'],
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
                'file' => 'src/Utils/Security/auth.php',
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
        'oral' => <<<'TEXT'
Cette slide sert à expliquer simplement l’injection SQL. Une attaque SQLi cherche à transformer une saisie utilisateur en morceau de requête SQL.

Dans le projet, les repositories utilisent prepare() et execute() : la requête SQL est préparée avec des marqueurs, puis les valeurs sont envoyées séparément. Même si un utilisateur saisit une chaîne malveillante, elle reste une valeur de paramètre et ne devient pas du SQL exécutable.
TEXT,
        'points' => ['prepare() prépare la requête.', 'execute() injecte les valeurs comme paramètres.', 'PDO::ATTR_EMULATE_PREPARES désactivé.', 'Même logique pour lecture, création, modification et suppression.'],
        'files' => ['src/Repository/RecipeRepository.php', 'src/Repository/AdminRepository.php'],
        'code' => [
            [
                'title' => 'Lecture paramétrée par slug',
                'file' => 'src/Repository/RecipeRepository.php',
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
        'oral' => <<<'TEXT'
Cette slide explique la différence entre stocker une donnée et l’afficher. Une attaque XSS devient dangereuse quand une donnée issue de l’utilisateur est réinjectée dans la page comme du HTML ou du JavaScript.

Le projet utilise la fonction e() au moment de l’affichage pour transformer les caractères spéciaux en texte inoffensif. La CSP ajoute une deuxième barrière : même si une erreur d’affichage existe quelque part, les scripts autorisés restent fortement limités.
TEXT,
        'points' => ['htmlspecialchars avec ENT_QUOTES.', 'Aucun HTML brut autorisé dans les recettes.', 'CSP centralisée dans headers.php.', 'Scripts limités aux fichiers locaux.'],
        'files' => ['src/Utils/Helpers/functions.php', 'src/Vues/recipe.tpl.php', 'src/Utils/Security/headers.php'],
        'code' => [
            [
                'title' => 'Helper d’échappement',
                'file' => 'src/Utils/Helpers/functions.php',
                'body' => <<<'PHP'
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
PHP,
            ],
            [
                'title' => 'CSP centralisée',
                'file' => 'src/Utils/Security/headers.php',
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
        'oral' => <<<'TEXT'
Cette slide montre pourquoi le CSRF est important sur un back-office. Sans protection, un administrateur connecté pourrait être piégé par une page externe qui déclenche une action à sa place.

Le projet ajoute un token CSRF dans chaque formulaire sensible. Au moment de recevoir la requête POST, le serveur compare le token envoyé avec celui stocké en session. Si le token est absent ou invalide, l’action est arrêtée avant toute modification en base.
TEXT,
        'points' => ['Token généré avec random_bytes().', 'Champ caché csrf_token dans les formulaires.', 'hash_equals() pour comparer.', 'Suppressions uniquement en POST.'],
        'files' => ['src/Utils/Security/csrf.php', 'src/Controller/Admin/RecipeAdminController.php', 'src/Controller/Admin/AdminUserController.php'],
        'code' => [
            [
                'title' => 'Token CSRF',
                'file' => 'src/Utils/Security/csrf.php',
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
        'oral' => <<<'TEXT'
Cette slide explique la défense contre les attaques par force brute. Le risque est qu’un robot essaye rapidement beaucoup de mots de passe sur la page de connexion.

Le projet enregistre les tentatives de connexion avec l’email tenté, l’adresse IP, le succès ou l’échec et la date. Après plusieurs échecs récents, la connexion est temporairement bloquée. Le message reste volontairement générique pour ne pas révéler si l’email existe.
TEXT,
        'points' => ['Table login_attempts.', 'Email, IP, user agent, succès ou échec.', 'Blocage après 5 échecs sur 15 minutes.', 'Message générique au login.'],
        'files' => ['src/Utils/Security/brute_force.php', 'src/Repository/LoginAttemptRepository.php', 'src/Controller/AuthController.php'],
        'code' => [
            [
                'title' => 'Seuil de blocage',
                'file' => 'src/Utils/Security/brute_force.php',
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
        'oral' => <<<'TEXT'
Cette slide montre que l’upload n’est pas traité comme un simple déplacement de fichier. Un upload mal sécurisé peut permettre d’envoyer un fichier exécutable ou un fichier déguisé en image.

Le projet vérifie l’extension, le type MIME réel, la taille et le dossier de destination. Le nom original n’est jamais utilisé directement : un nom aléatoire est généré pour éviter les collisions, les chemins manipulés et les noms dangereux.
TEXT,
        'points' => ['Extensions autorisées : jpg, jpeg, png, webp.', 'MIME réel vérifié avec finfo.', 'Taille limitée à 2 Mo.', 'Nom de fichier aléatoire.'],
        'files' => ['src/Utils/Security/upload.php', 'public/uploads/recipes/.htaccess'],
        'code' => [
            [
                'title' => 'Extension, MIME et taille',
                'file' => 'src/Utils/Security/upload.php',
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
        'oral' => <<<'TEXT'
Cette slide rappelle qu’il ne faut pas faire confiance uniquement au navigateur. Même si un formulaire HTML impose des champs obligatoires, un utilisateur peut envoyer une requête modifiée à la main.

Le projet valide donc les données côté serveur avant insertion ou modification. Les champs obligatoires, les longueurs, les emails et les mots de passe sont contrôlés. Cette validation réduit les données incohérentes et complète les protections XSS et SQLi.
TEXT,
        'points' => ['Titre obligatoire et limité.', 'Description courte limitée.', 'Ingrédients et étapes obligatoires.', 'Email admin validé côté serveur.'],
        'files' => ['src/Utils/Validation/recipe_validation.php', 'src/Utils/Validation/admin_validation.php'],
        'code' => [
            [
                'title' => 'Validation recette',
                'file' => 'src/Utils/Validation/recipe_validation.php',
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
        'oral' => <<<'TEXT'
Cette slide sert à rassurer le jury sur le fait que le projet a été vérifié dans des conditions proches du réel. Les pages ne sont pas seulement écrites : elles ont été lancées, testées avec MySQL et vérifiées avec des scénarios de sécurité.

Les tests couvrent les routes publiques, la connexion admin, les protections CSRF, brute force, l’upload, les headers et les repositories. L’objectif est de montrer que chaque exigence importante du sujet est testable, pas seulement documentée.
TEXT,
        'points' => ['29 tests MySQL réels passés.', '12 tests PHPUnit / 25 assertions.', 'Lint PHP complet sans erreur.', 'Assets Bootstrap locaux vérifiés.', 'CSP vérifiée dans les headers.'],
        'files' => ['README.md', 'docs/rapport-securite.md', 'tests/*'],
        'test' => 'Vérification : composer test, lint PHP complet, HTTP 200 sur les pages publiques et login admin fonctionnel.',
    ],
    [
        'kicker' => 'Conclusion',
        'title' => 'Résultat final',
        'lead' => 'Le projet démontre une application web complète, structurée, présentable et défendable techniquement.',
        'oral' => <<<'TEXT'
Cette conclusion permet de résumer le projet en deux axes. Côté utilisateur, le site ressemble à une vraie plateforme de recettes avec navigation, images, recherche, détails et contenu lisible.

Côté technique, les protections demandées par le sujet sont présentes dans le code et documentées avec des extraits réels. Le projet peut donc être présenté à la fois comme une application fonctionnelle et comme une démonstration de développement web sécurisé.
TEXT,
        'points' => ['Sujet PDF respecté côté front-office et back-office.', 'Sécurité documentée avec extraits réels.', 'Page Conformité dédiée à la grille officielle.', 'Projet prêt à être présenté et testé.'],
        'files' => ['README.md', 'docs/rapport-securite-projet-final-greta92.pdf'],
        'test' => 'Vérification : présentation exploitable en mode carrousel, slide par slide.',
    ],
];

public_header('Présentation');
?>
<section class="section-blue-soft py-4">
    <div class="container py-lg-3">
        <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3 mb-4">
            <div>
                <span class="kicker mb-2" data-counter>Slide 1 / <?= count($slides) ?></span>
                <h1 class="section-title mb-0">Présentation jury</h1>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="presenter-bar" data-presenter-bar>
                    <button type="button" class="presenter-bar__btn" data-presenter-toggle aria-pressed="false" title="Afficher les repères de lecture">Repères</button>
                    <span class="presenter-bar__timer" data-presenter-timer aria-label="Chronomètre">00:00</span>
                    <button type="button" class="presenter-bar__btn" data-presenter-reset title="Réinitialiser le chrono">↺</button>
                    <button type="button" class="presenter-bar__btn" data-presenter-fullscreen title="Plein écran">⛶</button>
                </div>
                <a class="btn btn-outline-secondary" href="/">Accueil</a>
                <a class="btn btn-outline-secondary" href="/stack">Stack</a>
                <a class="btn btn-outline-secondary" href="/conformite">Conformité</a>
                <?php if (is_admin_authenticated()): ?>
                    <a class="btn btn-outline-secondary" href="/admin/dashboard">Back-office</a>
                <?php endif; ?>
            </div>
        </div>

        <progress class="w-100 mb-4 presentation-progress" value="1" max="<?= count($slides) ?>" data-progress></progress>

        <div class="presentation-navigation d-flex justify-content-between align-items-center gap-3 mb-4">
            <button class="btn btn-outline-secondary" type="button" data-prev>Précédent</button>
            <button class="btn btn-primary" type="button" data-next>Suivant</button>
        </div>

        <div class="presentation-deck lux-card lux-card-lg overflow-hidden" data-deck>
            <?php foreach ($slides as $index => $slide): ?>
                <article class="presentation-slide <?= $index === 0 ? 'd-grid' : 'd-none' ?> p-4 p-lg-5" data-slide>
                    <div class="slide-top mb-4 mb-lg-5">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7">
                                <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                                    <img class="site-logo" src="/assets/img/logo-mijote-maison.svg" alt="">
                                    <span class="badge rounded-pill text-bg-light border px-3 py-2">Slide <?= $index + 1 ?></span>
                                    <p class="kicker text-herb mb-0"><?= e($slide['kicker']) ?></p>
                                </div>
                                <h2 class="display-5 fw-black text-ink mb-3"><?= e($slide['title']) ?></h2>
                                <p class="lead text-secondary lh-lg mb-0"><?= e($slide['lead']) ?></p>
                            </div>
                            <div class="col-lg-5">
                                <div class="slide-summary-card h-100" data-audience-only>
                                    <p class="kicker mb-2">Repère rapide</p>
                                    <p class="mb-0 text-secondary"><?= e($slide['test']) ?></p>
                                </div>
                                <?php render_guidance_panel($slide['oral'], 'slide-guidance-card--top h-100'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-xl-5">
                            <div class="lux-card h-100 p-4 bg-white">
                                <p class="kicker mb-3">Points clés</p>
                                <ul class="list-unstyled vstack gap-3 mb-0 text-secondary">
                                    <?php foreach ($slide['points'] as $point): ?>
                                        <li class="d-flex gap-2"><span class="text-primary fw-black">•</span><span><?= e($point) ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="col-xl-7">
                            <div class="row g-4">
                                <div class="col-lg-5">
                                    <div class="lux-card h-100 p-4 bg-white">
                                        <p class="kicker text-herb mb-3">Fichiers concernés</p>
                                        <div class="d-flex flex-wrap gap-2 mb-0">
                                            <?php foreach ($slide['files'] as $file): ?>
                                                <span class="badge-soft"><?= e($file) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="lux-card h-100 p-4 bg-white">
                                        <p class="kicker mb-2">Preuve de test</p>
                                        <p class="small text-secondary lh-lg mb-0"><?= e($slide['test']) ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($slide['code'])): ?>
                                <div class="vstack gap-3 mt-4">
                                    <?php foreach ($slide['code'] as $block): ?>
                                        <?php render_code_panel($block['title'], $block['file'], $block['body']); ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="lux-card p-4 mt-4">
                                    <p class="kicker mb-2">Lecture de la slide</p>
                                    <p class="mb-0 text-secondary lh-lg">Cette slide pose le contexte et relie la démonstration au sujet officiel. Les slides sécurité suivantes affichent directement les extraits de code.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php public_footer(); ?>
