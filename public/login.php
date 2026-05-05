<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/AdminRepository.php';
require_once BASE_PATH . '/app/security/brute_force.php';

if (is_admin_authenticated()) {
    redirect('/admin/dashboard.php');
}

if (is_post()) {
    require_valid_csrf();
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    try {
        $pdo = db();
        if (login_is_blocked($pdo, $email)) {
            flash('error', 'Trop de tentatives. Reessayez dans quelques minutes.');
            redirect('/login.php');
        }

        $repo = new AdminRepository($pdo);
        $admin = filter_var($email, FILTER_VALIDATE_EMAIL) ? $repo->findByEmail($email) : null;
        $valid = $admin && password_verify($password, (string) $admin['password_hash']);
        record_login_attempt($pdo, $email, (bool) $valid);

        if (!$valid) {
            flash('error', 'Identifiants invalides.');
            redirect('/login.php');
        }

        login_admin($admin);
        flash('success', 'Connexion administrateur reussie.');
        redirect('/admin/dashboard.php');
    } catch (Throwable $exception) {
        flash('error', 'Connexion impossible pour le moment.');
        redirect('/login.php');
    }
}

public_header('Connexion admin');
?>
<section class="mx-auto grid min-h-[70vh] max-w-7xl items-center px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-md">
        <?php render_flash(); ?>
        <form class="panel-card p-6" method="post" action="/login.php" novalidate>
            <?= csrf_field() ?>
            <span class="inline-flex rounded-full border border-violet-300/30 bg-violet-300/10 px-3 py-1 text-sm text-violet-100">Back-office reserve</span>
            <h1 class="mt-5 text-3xl font-bold text-white">Connexion admin</h1>
            <p class="mt-2 text-sm leading-6 text-slate-400">Les erreurs restent volontairement generiques pour limiter l'enumeration de comptes.</p>
            <div class="mt-6">
                <label class="label" for="email">Email</label>
                <input class="field" id="email" name="email" type="email" autocomplete="email" required>
            </div>
            <div class="mt-4">
                <label class="label" for="password">Mot de passe</label>
                <input class="field" id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <button class="btn-primary mt-6 w-full" type="submit">Se connecter</button>
        </form>
    </div>
</section>
<?php public_footer(); ?>
