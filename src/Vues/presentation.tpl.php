<section class="section-blue-soft py-4">
    <div class="container py-lg-3">
        <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3 mb-4">
            <div>
                <span class="kicker mb-2" data-counter>Page 1 / <?= count($slides) ?></span>
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
                                    <span class="badge rounded-pill text-bg-light border px-3 py-2">Page <?= $index + 1 ?> / <?= count($slides) ?></span>
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
                                        <?php render_pedagogy_code_panel($block['title'], $block['file'], $block['body']); ?>
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
