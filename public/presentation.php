<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/bootstrap.php';

$slides = [
    ['Secure Recipes GRETA 92', 'Projet final cybersécurité : site de recettes public, administration protegee et securite documentee.'],
    ['Contexte', 'Un site de recettes expose a Internet avec front-office public et back-office administrateur.'],
    ['Objectif', 'Developper une application complete tout en appliquant les bases de la securite web.'],
    ['Stack', 'PHP natif, MySQL, PDO, JavaScript vanilla et Tailwind CSS compile localement.'],
    ['Architecture', 'Separation public, admin, app/security, repositories, validation, documentation et base de donnees.'],
    ['Fonctionnalites publiques', 'Accueil, liste des recettes, detail recette, navigation claire et donnees echappees.'],
    ['Fonctionnalites admin', 'Dashboard, CRUD recettes, CRUD administrateurs, upload image et messages flash.'],
    ['Authentification', 'password_hash, password_verify, session_regenerate_id et cookies de session securises.'],
    ['Injection SQL', 'Toutes les lectures, creations, modifications et suppressions passent par PDO prepare/execute.'],
    ['XSS', 'Validation serveur, helper e(), absence de HTML utilisateur et Content Security Policy.'],
    ['CSRF', 'Token sur connexion, creation, modification et suppression. Les actions sensibles utilisent POST.'],
    ['Brute force', 'Table login_attempts, journalisation IP/email/user-agent et blocage apres echecs repetes.'],
    ['Upload securise', 'Extensions limitees, MIME verifie, taille limitee, nom aleatoire et execution PHP bloquee.'],
    ['Tests realises', 'Acces admin, CSRF invalide, upload .php, XSS, SQLi login, brute force, responsive et CSP.'],
    ['Resultat final', 'Application fonctionnelle, claire, securisee, documentee et presentable devant un jury.'],
    ['Ameliorations possibles', 'HTTPS force, roles avances, logs admin detailles, audit automatise et tests PHPUnit.'],
];

public_header('Presentation');
?>
<section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <span class="text-sm font-medium text-cyan-200" data-counter>Slide 1 / <?= count($slides) ?></span>
            <h1 class="mt-2 text-3xl font-bold text-white">Presentation jury</h1>
        </div>
        <div class="flex gap-3">
            <a class="btn-secondary" href="/">Accueil</a>
            <?php if (is_admin_authenticated()): ?>
                <a class="btn-secondary" href="/admin/dashboard.php">Admin</a>
            <?php endif; ?>
        </div>
    </div>
    <progress class="mb-5 h-2 w-full overflow-hidden rounded-full bg-white/10 accent-cyan-300" value="1" max="<?= count($slides) ?>" data-progress></progress>
    <div class="panel-card min-h-[520px] overflow-hidden p-6 sm:p-10" data-deck>
        <?php foreach ($slides as $index => [$title, $body]): ?>
            <article class="<?= $index === 0 ? 'grid' : 'hidden' ?> min-h-[440px] place-items-center text-center" data-slide>
                <div class="max-w-4xl">
                    <span class="inline-flex rounded-full border border-cyan-300/30 bg-cyan-300/10 px-3 py-1 text-sm text-cyan-100">Slide <?= $index + 1 ?></span>
                    <h2 class="mt-7 text-4xl font-bold text-white sm:text-6xl"><?= e($title) ?></h2>
                    <p class="mt-7 text-xl leading-9 text-slate-300"><?= e($body) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="mt-6 flex justify-between">
        <button class="btn-secondary" type="button" data-prev>Precedent</button>
        <button class="btn-primary" type="button" data-next>Suivant</button>
    </div>
</section>
<?php public_footer(); ?>
