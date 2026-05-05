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
            flash('error', 'Trop de tentatives. Réessayez dans quelques minutes.');
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
        flash('success', 'Connexion administrateur réussie.');
        redirect('/admin/dashboard.php');
    } catch (Throwable $exception) {
        flash('error', 'Connexion impossible pour le moment.');
        redirect('/login.php');
    }
}

public_header('Connexion admin');
?>
<section class="relative overflow-hidden bg-[#fff1dc]">
    <div class="mx-auto grid min-h-[calc(100vh-10rem)] max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_.9fr] lg:items-center lg:px-8">
        <div class="hidden lg:block">
            <img class="aspect-[4/3] w-full rounded-[2rem] object-cover shadow-2xl shadow-orange-900/20" src="/assets/img/recipes/ingredients-frais.webp" alt="Ingrédients frais">
            <div class="mt-6 rounded-[2rem] bg-white p-6 shadow-sm">
                <p class="text-sm font-extrabold uppercase tracking-[0.16em] text-herb">Back-office sécurisé</p>
                <p class="mt-2 text-stone-700">Connexion protégée par CSRF, limitation brute force, mots de passe hachés et session régénérée.</p>
            </div>
        </div>
        <div class="mx-auto w-full max-w-md">
            <?php render_flash(); ?>
            <form class="rounded-[2rem] border border-orange-100 bg-white p-7 shadow-xl shadow-orange-900/10" method="post" action="/login.php" novalidate>
                <?= csrf_field() ?>
                <img class="h-16 w-16 rounded-2xl" src="/assets/img/logo-mijote-protege.svg" alt="">
                <span class="mt-6 inline-flex rounded-full bg-orange-50 px-4 py-2 text-sm font-extrabold text-tomato">Administration</span>
                <h1 class="mt-4 font-serif text-4xl font-bold text-stone-950">Connexion admin</h1>
                <p class="mt-2 text-sm leading-6 text-stone-600">Accès réservé aux administrateurs du site Mijoté & Protégé.</p>
                <div class="mt-6">
                    <label class="mb-2 block text-sm font-extrabold text-stone-700" for="email">Email</label>
                    <input class="w-full rounded-2xl border border-orange-200 bg-orange-50 px-4 py-3 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-tomato focus:ring-4 focus:ring-orange-200" id="email" name="email" type="email" autocomplete="email" required>
                </div>
                <div class="mt-4">
                    <label class="mb-2 block text-sm font-extrabold text-stone-700" for="password">Mot de passe</label>
                    <input class="w-full rounded-2xl border border-orange-200 bg-orange-50 px-4 py-3 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-tomato focus:ring-4 focus:ring-orange-200" id="password" name="password" type="password" autocomplete="current-password" required>
                </div>
                <button class="btn-primary mt-6 w-full" type="submit">Se connecter</button>
            </form>
        </div>
    </div>
</section>
<?php public_footer(); ?>
