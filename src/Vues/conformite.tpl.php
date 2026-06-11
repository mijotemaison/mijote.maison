<section class="section-blue-soft py-5">
    <div class="container py-lg-4">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <p class="kicker">Sujet officiel GRETA 92</p>
                <h1 class="display-4 fw-black text-ink mb-4">Conformité au sujet officiel.</h1>
                <p class="lead text-secondary mb-4">Cette page reprend la grille d’évaluation sans la colonne points. Pour chaque critère, elle explique ce que le projet réalise et montre le morceau de code qui le prouve.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-primary btn-lg" href="/presentation">Voir la présentation</a>
                    <a class="btn btn-outline-primary btn-lg" href="/stack">Comprendre la stack</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="lux-card lux-card-lg p-4 p-lg-5">
                    <p class="kicker text-herb">Lecture rapide</p>
                    <div class="vstack gap-3 mt-4">
                        <div class="rounded-4 bg-white border p-4">
                            <p class="h4 font-display fw-black mb-2">Front-office</p>
                            <p class="small text-secondary lh-lg mb-0">Accueil, liste, détail recette, connexion, navigation publique sans action sensible.</p>
                        </div>
                        <div class="rounded-4 bg-white border p-4">
                            <p class="h4 font-display fw-black mb-2">Back-office</p>
                            <p class="small text-secondary lh-lg mb-0">CRUD recettes, CRUD admins, upload, dashboard, modération et journal sécurité.</p>
                        </div>
                        <div class="rounded-4 bg-white border p-4">
                            <p class="h4 font-display fw-black mb-2">Sécurité</p>
                            <p class="small text-secondary lh-lg mb-0">PDO, XSS, CSP, CSRF, brute force, sessions, headers et upload sécurisé.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="mb-4 col-lg-8">
            <p class="kicker">Grille officielle</p>
            <h2 class="section-title">Réponse point par point.</h2>
            <p class="text-secondary lh-lg">La colonne “points” a volontairement été retirée. Le but ici est de justifier techniquement chaque attente devant le jury.</p>
        </div>

        <div class="lux-card lux-card-lg overflow-hidden">
            <div class="row g-0 border-bottom bg-white fw-black text-uppercase small letter-spaced d-none d-lg-flex">
                <div class="col-lg-3 p-4">Critère évalué</div>
                <div class="col-lg-4 p-4">Description attendue</div>
                <div class="col-lg-5 p-4">Réponse du projet</div>
            </div>
            <?php foreach ($criteria as $index => $item): ?>
                <article class="border-bottom">
                    <div class="row g-0">
                        <div class="col-lg-3 p-4">
                            <span class="badge rounded-pill text-bg-primary mb-3"><?= e((string) ($index + 1)) ?></span>
                            <h3 class="h4 font-display fw-black"><?= e($item['criterion']) ?></h3>
                        </div>
                        <div class="col-lg-4 p-4 text-secondary lh-lg"><?= e($item['expected']) ?></div>
                        <div class="col-lg-5 p-4 text-secondary lh-lg"><?= e($item['answer']) ?></div>
                    </div>
                    <div class="row g-4 bg-light-subtle border-top p-4">
                        <div class="col-lg-5">
                            <p class="kicker text-herb mb-3">Fichiers concernés</p>
                            <div class="d-flex flex-wrap gap-2 mb-4">
                                <?php foreach ($item['files'] as $file): ?>
                                    <span class="badge-soft"><?= e($file) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <p class="kicker mb-2">Explication pour le jury</p>
                            <p class="text-secondary lh-lg mb-0"><?= e($item['explanation']) ?></p>
                        </div>
                        <div class="col-lg-7">
                            <?php render_pedagogy_code_panel($item['code']['title'], $item['code']['file'], $item['code']['body']); ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-blue-soft py-5">
    <div class="container">
        <div class="lux-card lux-card-lg p-4 p-lg-5">
            <p class="kicker">Conclusion défendable</p>
            <blockquote class="blockquote fs-3 font-display fw-black text-ink mb-0">Le projet répond au sujet officiel : un site de recettes public, un back-office admin, des CRUD fonctionnels et des protections web visibles dans le code.</blockquote>
        </div>
    </div>
</section>
