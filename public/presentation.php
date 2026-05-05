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
    echo '<pre class="max-h-[26rem] overflow-auto p-4 text-[0.78rem] leading-6 text-slate-100"><code id="' . e($id) . '">' . e(trim($code)) . '</code></pre>';
    echo '</div>';
}

$slides = [
    ['Mijoté Maison', 'Un site public de recettes qui ressemble à un vrai produit, avec une administration sécurisée en arrière-plan.'],
    ['Problème traité', 'Une application exposée à Internet reçoit des données utilisateur, des connexions admin et des uploads. Chaque entrée peut devenir une attaque.'],
    ['Architecture', 'Le code est séparé entre pages publiques, back-office, repositories PDO, validation et fichiers app/security.'],
    ['Authentification', 'Les mots de passe sont hachés, vérifiés avec password_verify et la session est régénérée après connexion.'],
    ['Injection SQL', 'Les requêtes passent par PDO prepare/execute. Les variables utilisateur ne sont jamais concaténées dans le SQL.'],
    ['XSS', 'Les données stockées sont échappées au moment de l’affichage avec e() et une CSP limite l’exécution de scripts.'],
    ['CSRF', 'Les formulaires sensibles contiennent un token aléatoire vérifié côté serveur avant l’action.'],
    ['Brute force', 'Les tentatives de connexion sont journalisées et le login est bloqué après plusieurs échecs récents.'],
    ['Upload', 'Les images sont filtrées par extension, MIME, taille, nom aléatoire et stockage contrôlé.'],
    ['Conclusion', 'Le projet montre une application complète, présentable au public, et défendable techniquement devant le jury.'],
];

$sections = [
    [
        'id' => 'auth',
        'kicker' => 'Authentification',
        'title' => 'Empêcher l’accès au back-office sans compte valide',
        'attack' => 'Un attaquant tente de deviner un mot de passe, voler une session ou accéder directement aux pages admin.',
        'solution' => 'Le login vérifie le hash du mot de passe, régénère l’identifiant de session et chaque page admin appelle require_admin().',
        'files' => ['public/login.php', 'app/security/auth.php', 'admin/*'],
        'code' => [
            [
                'title' => 'Vérification du mot de passe hashé',
                'file' => 'public/login.php',
                'body' => <<<'PHP'
$admin = filter_var($email, FILTER_VALIDATE_EMAIL) ? $repo->findByEmail($email) : null;
$valid = $admin && password_verify($password, (string) $admin['password_hash']);
record_login_attempt($pdo, $email, (bool) $valid);

if (!$valid) {
    flash('error', 'Identifiants invalides.');
    redirect('/login.php');
}

login_admin($admin);
PHP,
            ],
            [
                'title' => 'Session régénérée et accès admin protégé',
                'file' => 'app/security/auth.php',
                'body' => <<<'PHP'
function login_admin(array $admin): void
{
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $admin['id'];
    $_SESSION['admin_email'] = (string) $admin['email'];
    $_SESSION['admin_username'] = (string) $admin['username'];
}

function require_admin(): void
{
    if (!is_admin_authenticated()) {
        flash('error', 'Acces reserve aux administrateurs authentifies.');
        redirect('/login.php');
    }
}
PHP,
            ],
        ],
        'explanation' => [
            'password_verify() compare le mot de passe saisi au hash stocké, sans jamais stocker le mot de passe en clair.',
            'session_regenerate_id(true) limite la fixation de session après connexion.',
            'require_admin() centralise la protection des pages admin : sans session, l’utilisateur est renvoyé vers le login.',
        ],
        'test' => 'Test réalisé : /admin/dashboard.php sans session redirige vers /login.php, login valide arrive au dashboard.',
    ],
    [
        'id' => 'sqli',
        'kicker' => 'Injection SQL',
        'title' => 'Bloquer les requêtes SQL manipulées par l’utilisateur',
        'attack' => 'Un attaquant saisit une valeur comme \' OR 1=1 pour modifier une requête SQL.',
        'solution' => 'Les accès base sont regroupés dans des repositories qui utilisent PDO prepare/execute et des paramètres.',
        'files' => ['app/repositories/RecipeRepository.php', 'app/repositories/AdminRepository.php'],
        'code' => [
            [
                'title' => 'Lecture paramétrée par slug',
                'file' => 'app/repositories/RecipeRepository.php',
                'body' => <<<'PHP'
public function findBySlug(string $slug): ?array
{
    $stmt = $this->pdo->prepare('SELECT * FROM recipes WHERE slug = :slug LIMIT 1');
    $stmt->execute(['slug' => $slug]);
    $recipe = $stmt->fetch();

    return $recipe ?: null;
}
PHP,
            ],
            [
                'title' => 'Création de recette avec paramètres nommés',
                'file' => 'app/repositories/RecipeRepository.php',
                'body' => <<<'PHP'
$stmt = $this->pdo->prepare(
    'INSERT INTO recipes (title, slug, short_description, description, ingredients, preparation_steps, image_path, created_at, updated_at)
     VALUES (:title, :slug, :short_description, :description, :ingredients, :preparation_steps, :image_path, NOW(), NOW())'
);
$stmt->execute([
    'title' => $data['title'],
    'slug' => $data['slug'],
    'short_description' => $data['short_description'],
    'description' => $data['description'],
    'ingredients' => $data['ingredients'],
    'preparation_steps' => $data['preparation_steps'],
    'image_path' => $data['image_path'],
]);
PHP,
            ],
        ],
        'explanation' => [
            'Le SQL contient des marqueurs comme :slug au lieu de concaténer la saisie utilisateur.',
            'PDO transmet les valeurs séparément de la requête, ce qui empêche une saisie de devenir du SQL exécutable.',
            'La même logique est utilisée pour lire, créer, modifier et supprimer.',
        ],
        'test' => 'Test réalisé : tentative SQLi sur le login refusée sans casser la requête.',
    ],
    [
        'id' => 'xss',
        'kicker' => 'XSS',
        'title' => 'Empêcher l’exécution de scripts injectés',
        'attack' => 'Un attaquant tente de stocker <script>alert(1)</script> dans une recette pour l’exécuter chez les visiteurs.',
        'solution' => 'Les données venant de la base sont échappées avec e() avant affichage HTML. Une CSP limite aussi les sources de scripts.',
        'files' => ['app/helpers/functions.php', 'public/index.php', 'public/recipe.php', 'app/security/headers.php'],
        'code' => [
            [
                'title' => 'Helper d’échappement HTML',
                'file' => 'app/helpers/functions.php',
                'body' => <<<'PHP'
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
PHP,
            ],
            [
                'title' => 'Affichage échappé dans une fiche recette',
                'file' => 'public/recipe.php',
                'body' => <<<'PHP'
<h1 class="mt-6 max-w-4xl font-serif text-5xl font-bold leading-tight text-stone-950 sm:text-7xl">
    <?= e($recipe['title']) ?>
</h1>

<p class="mt-5 max-w-2xl text-xl leading-9 text-stone-700">
    <?= e($recipe['description']) ?>
</p>
PHP,
            ],
            [
                'title' => 'Content Security Policy',
                'file' => 'app/security/headers.php',
                'body' => <<<'PHP'
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");
PHP,
            ],
        ],
        'explanation' => [
            'htmlspecialchars transforme les caractères dangereux en entités HTML.',
            'ENT_QUOTES protège aussi les guillemets dans les attributs HTML.',
            'La CSP ajoute une deuxième barrière en limitant les scripts aux fichiers locaux.',
        ],
        'test' => 'Test réalisé : un titre contenant un script est affiché comme texte, pas exécuté.',
    ],
    [
        'id' => 'csrf',
        'kicker' => 'CSRF',
        'title' => 'Empêcher une action admin déclenchée depuis un autre site',
        'attack' => 'Un attaquant pousse un admin connecté à envoyer une requête de suppression ou modification sans son accord.',
        'solution' => 'Chaque formulaire sensible contient un token CSRF stocké en session et vérifié avant l’action.',
        'files' => ['app/security/csrf.php', 'admin/recipes/delete.php', 'admin/admins/delete.php'],
        'code' => [
            [
                'title' => 'Génération et champ caché',
                'file' => 'app/security/csrf.php',
                'body' => <<<'PHP'
function generate_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(generate_csrf_token()) . '">';
}
PHP,
            ],
            [
                'title' => 'Vérification serveur',
                'file' => 'app/security/csrf.php',
                'body' => <<<'PHP'
function verify_csrf_token(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals((string) $_SESSION['csrf_token'], $token);
}

function require_valid_csrf(): void
{
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        flash('error', 'Jeton CSRF invalide. Action refusee.');
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
PHP,
            ],
        ],
        'explanation' => [
            'random_bytes génère un token imprévisible.',
            'hash_equals évite les comparaisons fragiles.',
            'Sans token valide, l’action est refusée avant la modification en base.',
        ],
        'test' => 'Test réalisé : suppression recette avec token invalide refusée, nombre de recettes inchangé.',
    ],
    [
        'id' => 'bruteforce',
        'kicker' => 'Brute force',
        'title' => 'Limiter les essais répétés sur le login',
        'attack' => 'Un robot tente de deviner le mot de passe admin avec des centaines d’essais.',
        'solution' => 'Chaque tentative est enregistrée et la connexion est temporairement bloquée après 5 échecs récents.',
        'files' => ['app/security/brute_force.php', 'app/repositories/LoginAttemptRepository.php', 'public/login.php'],
        'code' => [
            [
                'title' => 'Seuil et fenêtre temporelle',
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
            [
                'title' => 'Journalisation d’une tentative',
                'file' => 'app/security/brute_force.php',
                'body' => <<<'PHP'
function record_login_attempt(PDO $pdo, string $email, bool $success): void
{
    $repo = new LoginAttemptRepository($pdo);
    $repo->create([
        'email' => $email,
        'ip_address' => client_ip(),
        'user_agent' => user_agent(),
        'success' => $success ? 1 : 0,
    ]);
}
PHP,
            ],
        ],
        'explanation' => [
            'La protection combine l’email tenté et l’adresse IP.',
            'Le message côté login reste générique pour ne pas confirmer si le compte existe.',
            'Les tentatives abusives deviennent visibles dans le dashboard.',
        ],
        'test' => 'Test réalisé : après 5 échecs récents, une connexion valide est temporairement refusée.',
    ],
    [
        'id' => 'upload',
        'kicker' => 'Upload sécurisé',
        'title' => 'Empêcher l’envoi de fichiers exécutables',
        'attack' => 'Un attaquant essaie d’envoyer un fichier .php déguisé en image pour exécuter du code sur le serveur.',
        'solution' => 'L’upload vérifie l’erreur PHP, la taille, l’extension, le MIME réel et renomme le fichier aléatoirement.',
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
            [
                'title' => 'Nom aléatoire',
                'file' => 'app/security/upload.php',
                'body' => <<<'PHP'
$filename = bin2hex(random_bytes(16)) . '.' . $extension;
$destination = UPLOAD_RECIPE_DIR . '/' . $filename;

if (!move_uploaded_file($tmpPath, $destination)) {
    return ['path' => null, 'error' => 'Impossible de sauvegarder l’image.'];
}

return ['path' => 'uploads/recipes/' . $filename, 'error' => null];
PHP,
            ],
        ],
        'explanation' => [
            'Le nom original envoyé par l’utilisateur n’est jamais réutilisé.',
            'finfo lit le type réel du fichier, pas seulement son extension.',
            'La taille est limitée à 2 Mo pour éviter les abus simples.',
        ],
        'test' => 'Test réalisé : upload .php refusé, image WebP/PNG valide acceptée.',
    ],
    [
        'id' => 'validation',
        'kicker' => 'Validation serveur',
        'title' => 'Refuser les données incohérentes avant la base',
        'attack' => 'Un utilisateur envoie des champs vides, trop longs ou mal formés en contournant le navigateur.',
        'solution' => 'La validation est faite côté serveur avant création ou modification.',
        'files' => ['app/validation/recipe_validation.php', 'app/validation/admin_validation.php'],
        'code' => [
            [
                'title' => 'Validation d’une recette',
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
        'explanation' => [
            'La validation côté serveur reste obligatoire car le HTML peut être contourné.',
            'Les limites de longueur réduisent les entrées abusives et protègent l’affichage.',
            'Les erreurs sont renvoyées proprement à l’admin.',
        ],
        'test' => 'Test réalisé : formulaires incomplets refusés, erreurs affichées sans insertion.',
    ],
];

public_header('Présentation');
?>
<section class="bg-[#fff1dc]">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <span class="text-sm font-extrabold uppercase tracking-[0.16em] text-tomato" data-counter>Slide 1 / <?= count($slides) ?></span>
                <h1 class="mt-2 font-serif text-4xl font-bold text-stone-950">Présentation jury</h1>
            </div>
            <div class="flex gap-3">
                <a class="btn-secondary" href="/">Accueil</a>
                <?php if (is_admin_authenticated()): ?>
                    <a class="btn-secondary" href="/admin/dashboard.php">Espace équipe</a>
                <?php endif; ?>
            </div>
        </div>
        <progress class="mb-5 h-2 w-full overflow-hidden rounded-full bg-white accent-tomato" value="1" max="<?= count($slides) ?>" data-progress></progress>
        <div class="min-h-[520px] overflow-hidden rounded-[2rem] border border-orange-100 bg-white p-6 shadow-xl shadow-orange-900/10 sm:p-10" data-deck>
            <?php foreach ($slides as $index => [$title, $body]): ?>
                <article class="<?= $index === 0 ? 'grid' : 'hidden' ?> min-h-[440px] place-items-center text-center" data-slide>
                    <div class="max-w-4xl">
                        <img class="mx-auto h-20 w-20 rounded-3xl" src="/assets/img/logo-mijote-maison.svg" alt="">
                        <span class="mt-8 inline-flex rounded-full bg-orange-50 px-4 py-2 text-sm font-extrabold text-tomato">Slide <?= $index + 1 ?></span>
                        <h2 class="mt-7 font-serif text-5xl font-bold text-stone-950 sm:text-7xl"><?= e($title) ?></h2>
                        <p class="mt-7 text-xl leading-9 text-stone-700"><?= e($body) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="mt-6 flex justify-between">
            <button class="btn-secondary" type="button" data-prev>Précédent</button>
            <button class="btn-primary" type="button" data-next>Suivant</button>
        </div>
    </div>
</section>

<section class="bg-cream py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10 grid gap-6 lg:grid-cols-[.8fr_1.2fr] lg:items-end">
            <div>
                <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Dossier technique</p>
                <h2 class="mt-3 font-serif text-5xl font-bold leading-tight text-stone-950">Quelle partie du code protège quelle attaque ?</h2>
            </div>
            <p class="text-lg leading-8 text-stone-700">Cette section sert pour l’oral : elle relie chaque menace à une décision technique, cite le fichier concerné et montre l’extrait réel du projet.</p>
        </div>

        <div class="mb-8 flex flex-wrap gap-2">
            <?php foreach ($sections as $section): ?>
                <a class="rounded-full border border-orange-200 bg-white px-4 py-2 text-sm font-extrabold text-stone-700 shadow-sm transition hover:border-tomato hover:text-tomato" href="#<?= e($section['id']) ?>"><?= e($section['kicker']) ?></a>
            <?php endforeach; ?>
        </div>

        <div class="space-y-10">
            <?php foreach ($sections as $section): ?>
                <article id="<?= e($section['id']) ?>" class="scroll-mt-28 overflow-hidden rounded-[2rem] border border-orange-100 bg-white shadow-xl shadow-orange-900/10">
                    <div class="grid gap-8 p-6 lg:grid-cols-[.75fr_1.25fr] lg:p-8">
                        <div>
                            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato"><?= e($section['kicker']) ?></p>
                            <h3 class="mt-3 font-serif text-4xl font-bold leading-tight text-stone-950"><?= e($section['title']) ?></h3>
                            <div class="mt-6 space-y-4 text-stone-700">
                                <div class="rounded-2xl bg-red-50 p-4">
                                    <p class="font-extrabold text-red-900">Menace</p>
                                    <p class="mt-1 leading-7"><?= e($section['attack']) ?></p>
                                </div>
                                <div class="rounded-2xl bg-emerald-50 p-4">
                                    <p class="font-extrabold text-herb">Solution appliquée</p>
                                    <p class="mt-1 leading-7"><?= e($section['solution']) ?></p>
                                </div>
                                <div class="rounded-2xl bg-orange-50 p-4">
                                    <p class="font-extrabold text-stone-900">Fichiers cités</p>
                                    <p class="mt-1 text-sm leading-7"><?= e(implode(' · ', $section['files'])) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <?php foreach ($section['code'] as $block): ?>
                                <?php render_code_panel($block['title'], $block['file'], $block['body']); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="grid gap-5 border-t border-orange-100 bg-[#fffaf2] p-6 lg:grid-cols-[1fr_.75fr] lg:p-8">
                        <div>
                            <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-herb">Explication orale</p>
                            <ul class="mt-4 space-y-3 text-stone-700">
                                <?php foreach ($section['explanation'] as $item): ?>
                                    <li class="flex gap-3 leading-7"><span class="mt-2 h-2 w-2 flex-none rounded-full bg-tomato"></span><span><?= e($item) ?></span></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="rounded-2xl bg-white p-5 shadow-sm">
                            <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-tomato">Preuve de test</p>
                            <p class="mt-3 leading-7 text-stone-700"><?= e($section['test']) ?></p>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php public_footer(); ?>
