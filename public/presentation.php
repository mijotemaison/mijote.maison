<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$slides = [
    ['Mijoté & Protégé', 'Projet final cybersécurité : un vrai site de recettes chaleureux avec une administration sécurisée.'],
    ['Contexte', 'Un front-office public de recettes et un back-office administrateur exposé comme une application réelle.'],
    ['Objectif', 'Développer une application complète tout en appliquant les bases de la sécurité web.'],
    ['Stack', 'PHP natif, MySQL, PDO, JavaScript vanilla et Tailwind CSS compilé localement.'],
    ['Architecture', 'Séparation public, admin, app/security, repositories, validation, documentation et base de données.'],
    ['Fonctionnalités publiques', 'Accueil gourmand, recherche visuelle, liste recettes, détail recette et navigation claire.'],
    ['Fonctionnalités admin', 'Dashboard, CRUD recettes, CRUD administrateurs, upload image et messages flash.'],
    ['Authentification', 'password_hash, password_verify, session_regenerate_id et cookies de session sécurisés.'],
    ['Injection SQL', 'Toutes les lectures, créations, modifications et suppressions passent par PDO prepare/execute.'],
    ['XSS', 'Validation, helper e(), absence de HTML utilisateur et Content Security Policy.'],
    ['CSRF', 'Token sur connexion, création, modification et suppression. Les actions sensibles utilisent POST.'],
    ['Brute force', 'Table login_attempts, journalisation IP/email/user-agent et blocage après échecs répétés.'],
    ['Upload sécurisé', 'Extensions limitées, MIME vérifié, taille limitée, nom aléatoire et exécution PHP bloquée.'],
    ['Tests réalisés', 'Accès admin, CSRF invalide, upload .php, XSS, SQLi login, brute force, responsive et CSP.'],
    ['Résultat final', 'Application fonctionnelle, claire, documentée, sécurisée et présentable devant un jury.'],
    ['Améliorations possibles', 'HTTPS forcé, rôles avancés, logs admin détaillés, audit automatisé et tests PHPUnit.'],
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
                    <a class="btn-secondary" href="/admin/dashboard.php">Admin</a>
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
<?php public_footer(); ?>
