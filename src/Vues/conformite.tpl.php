<?php

declare(strict_types=1);

function conformite_code_panel(string $title, string $file, string $code): void
{
    $id = 'conformite-code-' . md5($title . $file);
    echo '<div class="overflow-hidden rounded-2xl border border-slate-800 bg-slate-950 shadow-lg">';
    echo '<div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-800 bg-slate-900 px-4 py-3">';
    echo '<div><p class="text-sm font-extrabold text-white">' . e($title) . '</p><p class="text-xs text-slate-400">' . e($file) . '</p></div>';
    echo '<button class="rounded-full border border-slate-700 px-3 py-1.5 text-xs font-extrabold text-slate-200 transition hover:bg-slate-800" type="button" data-copy-code="' . e($id) . '">Copier</button>';
    echo '</div>';
    echo '<pre class="max-h-80 overflow-auto p-4 text-[0.78rem] leading-6 text-slate-100"><code id="' . e($id) . '">' . e(trim($code)) . '</code></pre>';
    echo '</div>';
}

$criteria = [
    [
        'criterion' => 'Architecture générale',
        'expected' => 'Séparation claire front-office / back-office, structure cohérente du projet, navigation fonctionnelle.',
        'answer' => 'Le projet sépare les pages publiques, les pages admin, la logique MVC et les protections transversales. Le front controller public reçoit les URLs propres et AltoRouter envoie chaque route vers le bon contrôleur.',
        'files' => ['public/index.php', 'src/Controller/*', 'src/Model/*', 'src/Vues/*'],
        'code' => [
            'title' => 'Routes propres vers contrôleurs',
            'file' => 'public/index.php',
            'body' => <<<'PHP'
$router = new AltoRouter();
$router->map('GET', '/', [SiteController::class, 'home'], 'home');
$router->map('GET', '/recettes', [RecipeController::class, 'index'], 'recipes');
$router->map('GET|POST', '/recette/[*:slug]', [RecipeController::class, 'show'], 'recipe_show');
$router->map('GET|POST', '/connexion', [AuthController::class, 'login'], 'login');
$router->map('GET', '/conformite', [SiteController::class, 'conformite'], 'conformite');
PHP,
        ],
        'explanation' => 'Cette partie correspond à la méthode du prof : une requête HTTP arrive sur un point d’entrée unique, puis le routeur choisit le contrôleur. Le front-office et le back-office passent par AltoRouter et les contrôleurs protègent les routes admin avec `require_admin()`.',
    ],
    [
        'criterion' => 'Page d’accueil',
        'expected' => 'Affichage correct des recettes, navigation fonctionnelle, aucune action sensible accessible publiquement.',
        'answer' => 'La page d’accueil affiche une présentation du site, des recettes récentes et des recettes populaires. Elle ne contient aucun formulaire de création, modification ou suppression.',
        'files' => ['src/Controller/SiteController.php', 'src/Vues/home.tpl.php', 'app/repositories/RecipeRepository.php'],
        'code' => [
            'title' => 'Accueil alimenté par les recettes publiées',
            'file' => 'src/Controller/SiteController.php',
            'body' => <<<'PHP'
$pdo = \db();
$recipeModel = new Recipe($pdo);
$interactionModel = new RecipeInteraction($pdo);
$recipes = $recipeModel->latest(6);
$popularRecipes = $recipeModel->popular(4);
$totalRecipes = $recipeModel->countPublished();
PHP,
        ],
        'explanation' => 'Le visiteur voit uniquement des données en lecture. Les méthodes `latest()` et `popular()` filtrent les recettes publiées, ce qui empêche d’afficher les brouillons ou archives côté public.',
    ],
    [
        'criterion' => 'Page recette',
        'expected' => 'Affichage complet et lisible d’une recette, données correctement récupérées depuis la base.',
        'answer' => 'Chaque recette dispose d’une page séparée `/recette/{slug}` avec titre, image, description, ingrédients, étapes, note, commentaires publiés et version imprimable dédiée.',
        'files' => ['src/Controller/RecipeController.php', 'src/Vues/recipe.tpl.php', 'src/Vues/recipe_print.tpl.php', 'app/repositories/RecipeRepository.php'],
        'code' => [
            'title' => 'Lecture sécurisée par slug',
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
        'explanation' => 'La page recette est séparée de l’accueil, comme demandé. La recette est récupérée depuis MySQL avec un slug paramétré, puis affichée dans une vue dédiée avec échappement HTML. La route `/recette/{slug}/impression` fournit aussi une page HTML dédiée à l’impression.',
    ],
    [
        'criterion' => 'Page de connexion',
        'expected' => 'Formulaire fonctionnel, gestion des erreurs, accès restreint au back-office.',
        'answer' => 'La route `/connexion` affiche une page nommée “Page de connexion”. Le formulaire utilise un token CSRF, vérifie l’email et le mot de passe, et redirige vers le dashboard après succès.',
        'files' => ['src/Controller/AuthController.php', 'src/Vues/login.tpl.php', 'app/security/brute_force.php'],
        'code' => [
            'title' => 'Connexion admin avec message générique',
            'file' => 'src/Controller/AuthController.php',
            'body' => <<<'PHP'
$admin = filter_var($email, FILTER_VALIDATE_EMAIL) ? $adminModel->findByEmail($email) : null;
$valid = $admin && password_verify($password, (string) $admin['password_hash']);
record_login_attempt($pdo, $email, (bool) $valid);

if (!$valid) {
    record_security_event($pdo, 'login_failed', 'Tentative de connexion admin refusee.', $email);
    flash('error', 'Identifiants invalides.');
    redirect('/connexion');
}
PHP,
        ],
        'explanation' => 'Le site ne propose aucune inscription publique. Le rôle attendu est uniquement admin, et les erreurs ne révèlent pas si l’email existe.',
    ],
    [
        'criterion' => 'CRUD Recettes',
        'expected' => 'Création, lecture, modification et suppression fonctionnelles des recettes.',
        'answer' => 'Le back-office contient les pages de liste, création, édition, aperçu, duplication et suppression des recettes. Les actions d’écriture sont en POST et protégées par CSRF.',
        'files' => ['src/Controller/Admin/RecipeAdminController.php', 'src/Vues/admin/recipes/*', 'app/repositories/RecipeRepository.php'],
        'code' => [
            'title' => 'Création recette avec validation, CSRF et upload',
            'file' => 'src/Controller/Admin/RecipeAdminController.php',
            'body' => <<<'PHP'
if (\is_post()) {
    \require_valid_csrf();
    $data = \clean_recipe_input($_POST);
    $errors = \validate_recipe_input($data);
    $upload = \upload_recipe_image($_FILES['image'] ?? []);

    if (!$errors) {
        $repo = new \RecipeRepository(\db());
        $data['slug'] = $repo->uniqueSlug(\make_slug($data['title']));
        $data['image_path'] = $upload['path'];
        $repo->create($data);
    }
}
PHP,
        ],
        'explanation' => 'Le CRUD est réel : les formulaires admin écrivent dans MySQL via `RecipeRepository`. Les suppressions passent aussi par POST pour éviter les actions sensibles par simple lien GET.',
    ],
    [
        'criterion' => 'CRUD Administrateurs',
        'expected' => 'Gestion des administrateurs accessible uniquement au back-office.',
        'answer' => 'Le back-office permet d’ajouter, modifier, lister et supprimer les administrateurs. Les hashes de mots de passe ne sont jamais affichés, et le dernier admin ne peut pas être supprimé.',
        'files' => ['src/Controller/Admin/AdminUserController.php', 'src/Vues/admin/admins/*', 'app/repositories/AdminRepository.php'],
        'code' => [
            'title' => 'Suppression encadrée des administrateurs',
            'file' => 'src/Controller/Admin/AdminUserController.php',
        'body' => <<<'PHP'
public function delete(string|int $id): void
{
    \require_admin();
    \require_valid_csrf();

    $repo = new \AdminRepository(\db());
    $adminId = (int) $id;

    if ($repo->count() <= 1) {
        \flash('error', 'Impossible de supprimer le dernier administrateur.');
        \redirect('/admin/administrateurs');
    }
}
PHP,
        ],
        'explanation' => 'Le projet évite de bloquer l’accès au site en empêchant la suppression du dernier administrateur. Il empêche aussi l’auto-suppression du compte connecté.',
    ],
    [
        'criterion' => 'Authentification',
        'expected' => 'Hachage des mots de passe, gestion sécurisée des sessions, protection des accès.',
        'answer' => 'Les mots de passe admin sont hashés avec Argon2id si disponible, vérifiés avec `password_verify()`, et la session est régénérée après connexion.',
        'files' => ['app/security/auth.php', 'src/Controller/AuthController.php', 'src/Controller/Admin/*'],
        'code' => [
            'title' => 'Hash, session sécurisée et protection admin',
            'file' => 'app/security/auth.php',
            'body' => <<<'PHP'
function admin_password_hash(string $password): string
{
    if (defined('PASSWORD_ARGON2ID')) {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    return password_hash($password, PASSWORD_DEFAULT);
}

function login_admin(array $admin): void
{
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $admin['id'];
}

function require_admin(): void
{
    if (!is_admin_authenticated()) {
        redirect('/connexion');
    }
}
PHP,
        ],
        'explanation' => 'L’accès admin n’est pas basé sur une variable GET ou un cookie lisible, mais sur une session PHP configurée en HttpOnly/SameSite. `require_admin()` est appelé en haut des pages admin.',
    ],
    [
        'criterion' => 'Protection contre l’injection SQL',
        'expected' => 'Utilisation de requêtes préparées, aucune concaténation SQL dangereuse.',
        'answer' => 'Les accès base sont centralisés dans les repositories et utilisent PDO avec `prepare()`, paramètres nommés et `execute()`.',
        'files' => ['app/config/database.php', 'app/repositories/RecipeRepository.php', 'app/repositories/AdminRepository.php'],
        'code' => [
            'title' => 'PDO configuré sans émulation de requêtes préparées',
            'file' => 'app/config/database.php',
            'body' => <<<'PHP'
$pdo = new PDO($dsn, (string) $user, (string) $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
PHP,
        ],
        'explanation' => 'Les valeurs utilisateur ne sont pas insérées directement dans le SQL. Elles sont envoyées comme paramètres, ce qui neutralise une tentative SQLi du type email admin suivi de OR 1=1.',
    ],
    [
        'criterion' => 'Protection XSS',
        'expected' => 'Échappement et validation des données, aucun script utilisateur exécutable.',
        'answer' => 'Les données venant de MySQL ou des formulaires sont affichées avec le helper `e()`, basé sur `htmlspecialchars()`.',
        'files' => ['app/helpers/functions.php', 'src/Vues/recipe.tpl.php', 'src/Vues/recipes.tpl.php'],
        'code' => [
            'title' => 'Échappement HTML centralisé',
            'file' => 'app/helpers/functions.php',
            'body' => <<<'PHP'
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
PHP,
        ],
        'explanation' => 'Si un attaquant met `<script>alert(1)</script>` dans un champ, le navigateur reçoit du texte échappé au lieu d’un script actif. La validation serveur limite aussi les tailles et formats acceptés.',
    ],
    [
        'criterion' => 'Content Security Policy (CSP)',
        'expected' => 'Présence d’une CSP cohérente limitant l’exécution de scripts.',
        'answer' => 'Les headers HTTP sont centralisés. La CSP autorise le site lui-même, les polices Google nécessaires et un nonce par requête pour les scripts contrôlés.',
        'files' => ['app/security/headers.php', 'public/.htaccess'],
        'code' => [
            'title' => 'CSP centralisée avec nonce',
            'file' => 'app/security/headers.php',
            'body' => <<<'PHP'
$nonce = csp_nonce();
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-{$nonce}'; style-src 'self' https://fonts.googleapis.com; img-src 'self' data:; font-src 'self' data: https://fonts.gstatic.com; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
PHP,
        ],
        'explanation' => 'La CSP réduit fortement l’impact d’une injection XSS en limitant les sources autorisées pour scripts, styles, images et formulaires. `.htaccess` garde aussi des headers Apache simples.',
    ],
    [
        'criterion' => 'Protection CSRF',
        'expected' => 'Tokens CSRF présents et vérifiés sur les formulaires sensibles.',
        'answer' => 'Les formulaires sensibles contiennent un champ caché `csrf_token`. Avant une création, modification, suppression ou connexion, le token est vérifié côté serveur.',
        'files' => ['app/security/csrf.php', 'src/Controller/Admin/RecipeAdminController.php', 'src/Controller/Admin/AdminUserController.php'],
        'code' => [
            'title' => 'Token CSRF généré et vérifié',
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
        'explanation' => 'Un site externe peut envoyer un POST vers notre application, mais il ne connaît pas le token stocké en session. L’action est donc refusée avant de modifier la base.',
    ],
    [
        'criterion' => 'Protection contre la force brute',
        'expected' => 'Limitation des tentatives de connexion, mécanisme de blocage ou temporisation.',
        'answer' => 'Les tentatives de connexion sont stockées en base avec email, IP, user agent, succès/échec. Après 5 échecs récents, la connexion est temporairement bloquée.',
        'files' => ['app/security/brute_force.php', 'app/repositories/LoginAttemptRepository.php', 'src/Controller/AuthController.php'],
        'code' => [
            'title' => 'Blocage après échecs répétés',
            'file' => 'app/security/brute_force.php',
            'body' => <<<'PHP'
const MAX_LOGIN_FAILURES = 5;
const LOGIN_WINDOW_MINUTES = 15;
const LOGIN_BLOCK_MINUTES = 15;

function login_is_blocked(PDO $pdo, string $email): bool
{
    $repo = new LoginAttemptRepository($pdo);
    $failures = $repo->countRecentFailures($email, client_ip(), LOGIN_WINDOW_MINUTES);

    return $failures >= MAX_LOGIN_FAILURES;
}
PHP,
        ],
        'explanation' => 'Ce mécanisme limite les essais massifs sur la page de connexion et laisse une trace exploitable dans les logs.',
    ],
    [
        'criterion' => 'Upload de fichiers sécurisé',
        'expected' => 'Vérification type, extension, taille, absence de fichiers exécutables.',
        'answer' => 'L’upload accepte uniquement `jpg`, `jpeg`, `png`, `webp`, vérifie la taille 2 Mo, compare extension et MIME réel, puis renomme le fichier avec un nom aléatoire.',
        'files' => ['app/security/upload.php', 'public/uploads/recipes/.htaccess'],
        'code' => [
            'title' => 'Contrôles upload image',
            'file' => 'app/security/upload.php',
            'body' => <<<'PHP'
$maxSize = 2 * 1024 * 1024;
if (($file['size'] ?? 0) > $maxSize) {
    return ['path' => null, 'error' => 'Image trop lourde : limite 2 Mo.'];
}

$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
if (!in_array($extension, $allowedExtensions, true)) {
    return ['path' => null, 'error' => 'Extension refusee. Formats acceptes : jpg, jpeg, png, webp.'];
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($tmpPath) ?: '';
$filename = bin2hex(random_bytes(16)) . '.' . $extension;
PHP,
        ],
        'explanation' => 'Le nom envoyé par l’utilisateur n’est jamais utilisé comme nom final. Le `.htaccess` du dossier upload désactive aussi l’exécution PHP sous Apache.',
    ],
    [
        'criterion' => 'Qualité du code',
        'expected' => 'Lisibilité, noms explicites, organisation claire, commentaires pertinents.',
        'answer' => 'Le code est organisé par responsabilités : configuration, helpers, sécurité, validation, repositories, contrôleurs, modèles, vues et admin.',
        'files' => ['app/bootstrap.php', 'src/Controller/*', 'app/repositories/*', 'tests/*'],
        'code' => [
            'title' => 'Bootstrap central du projet',
            'file' => 'app/bootstrap.php',
            'body' => <<<'PHP'
require_once BASE_PATH . '/app/config/app.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/helpers/functions.php';
require_once BASE_PATH . '/app/security/headers.php';
require_once BASE_PATH . '/app/security/auth.php';
require_once BASE_PATH . '/app/security/csrf.php';

enforce_https_in_production();
start_secure_session();
apply_security_headers();
PHP,
        ],
        'explanation' => 'Le bootstrap montre les dépendances importantes au même endroit. Les protections sont chargées avant les pages, ce qui évite de répéter la configuration dans chaque fichier.',
    ],
    [
        'criterion' => 'Documentation sécurité',
        'expected' => 'Explication claire des failles et des protections mises en place.',
        'answer' => 'Le projet contient README, CODEX, rapport Markdown, rapport PDF, présentation interactive, page Stack et cette page Conformité.',
        'files' => ['README.md', 'CODEX.md', 'docs/rapport-securite.md', 'docs/rapport-securite-projet-final-greta92.pdf'],
        'code' => [
            'title' => 'Documentation liée aux protections',
            'file' => 'README.md',
            'body' => <<<'MD'
## Sécurité appliquée

- Mots de passe hachés avec `password_hash()`.
- Vérification avec `password_verify()`.
- Requêtes SQL préparées dans les repositories.
- Échappement HTML centralisé avec `e()`.
- Tokens CSRF centralisés dans `app/security/csrf.php`.
- Limitation brute force via table `login_attempts`.
MD,
        ],
        'explanation' => 'La documentation n’est pas générique : elle cite les fichiers, les protections, les tests et les commandes. La présentation et la page Conformité servent de support oral pour le jury.',
    ],
];

public_header('Conformité au sujet officiel');
?>
<section class="bg-[#fff1dc] py-14">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[.9fr_1.1fr] lg:items-center lg:px-8">
        <div>
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Sujet officiel GRETA 92</p>
            <h1 class="mt-3 font-serif text-5xl font-bold leading-tight text-stone-950 sm:text-6xl">Conformité au sujet officiel.</h1>
            <p class="mt-5 text-lg leading-8 text-stone-700">Cette page reprend la grille d’évaluation sans la colonne points. Pour chaque critère, elle explique ce que le projet réalise et montre le morceau de code qui le prouve.</p>
            <div class="mt-7 flex flex-wrap gap-3">
                <a class="btn-primary" href="/presentation">Voir la présentation</a>
                <a class="btn-secondary" href="/stack">Comprendre la stack</a>
            </div>
        </div>
        <div class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-xl shadow-orange-900/10">
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-herb">Lecture rapide</p>
            <div class="mt-6 grid gap-4">
                <div class="rounded-2xl bg-orange-50 p-4">
                    <p class="font-serif text-2xl font-bold text-stone-950">Front-office</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Accueil, liste, détail recette, connexion, navigation publique sans action sensible.</p>
                </div>
                <div class="rounded-2xl bg-emerald-50 p-4">
                    <p class="font-serif text-2xl font-bold text-stone-950">Back-office</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">CRUD recettes, CRUD admins, upload, dashboard, modération et journal sécurité.</p>
                </div>
                <div class="rounded-2xl bg-white p-4 shadow-sm">
                    <p class="font-serif text-2xl font-bold text-stone-950">Sécurité</p>
                    <p class="mt-2 text-sm leading-6 text-stone-600">PDO, XSS, CSP, CSRF, brute force, sessions, headers et upload sécurisé.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-8 max-w-3xl">
        <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Grille officielle</p>
        <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">Réponse point par point.</h2>
        <p class="mt-3 leading-7 text-stone-600">La colonne “points” a volontairement été retirée. Le but ici est de justifier techniquement chaque attente devant le jury.</p>
    </div>

    <div class="overflow-hidden rounded-[2rem] border border-orange-100 bg-white shadow-xl shadow-orange-900/10">
        <div class="grid gap-3 border-b border-orange-100 bg-orange-50 px-5 py-4 text-sm font-extrabold uppercase tracking-[0.12em] text-stone-700 md:grid-cols-[.8fr_1.2fr_1.5fr]">
            <span>Critère évalué</span>
            <span>Description attendue</span>
            <span>Réponse du projet</span>
        </div>
        <?php foreach ($criteria as $index => $item): ?>
            <article class="border-b border-orange-100 last:border-b-0">
                <div class="grid gap-4 px-5 py-6 md:grid-cols-[.8fr_1.2fr_1.5fr]">
                    <div>
                        <span class="rounded-full bg-tomato px-3 py-1 text-xs font-extrabold text-white"><?= e((string) ($index + 1)) ?></span>
                        <h3 class="mt-3 font-serif text-2xl font-bold text-stone-950"><?= e($item['criterion']) ?></h3>
                    </div>
                    <p class="leading-7 text-stone-600"><?= e($item['expected']) ?></p>
                    <p class="leading-7 text-stone-700"><?= e($item['answer']) ?></p>
                </div>
                <div class="grid gap-5 bg-[#fffaf3] px-5 py-6 lg:grid-cols-[.9fr_1.4fr]">
                    <div>
                        <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-herb">Fichiers concernés</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <?php foreach ($item['files'] as $file): ?>
                                <span class="rounded-full bg-white px-3 py-1.5 text-xs font-extrabold text-stone-700 shadow-sm"><?= e($file) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <p class="mt-6 text-sm font-extrabold uppercase tracking-[0.16em] text-tomato">Explication pour le jury</p>
                        <p class="mt-3 leading-7 text-stone-700"><?= e($item['explanation']) ?></p>
                    </div>
                    <?php conformite_code_panel($item['code']['title'], $item['code']['file'], $item['code']['body']); ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-[#fff1dc] py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-xl shadow-orange-900/10 sm:p-8">
            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Conclusion défendable</p>
            <blockquote class="mt-4 font-serif text-3xl font-bold leading-tight text-stone-950">Le projet répond au sujet officiel : un site de recettes public, un back-office admin, des CRUD fonctionnels et des protections web visibles dans le code.</blockquote>
        </div>
    </div>
</section>
<?php public_footer(); ?>
