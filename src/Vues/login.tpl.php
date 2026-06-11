<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center justify-content-center g-5">
            <div class="col-lg-6 d-none d-lg-block">
                <img class="hero-img" src="/assets/img/recipes/ingredients-frais.webp" alt="Ingrédients frais">
                <div class="lux-card p-4 mt-4">
                    <span class="kicker">Page de connexion</span>
                    <p class="text-muted mt-3 mb-3">Formulaire de connexion permettant l'accès au back-office.</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-soft badge-tomato">Rôle : admin</span>
                        <span class="badge-soft badge-herb">Aucune inscription publique</span>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-lg-5">
                <?php render_flash(); ?>
                <form class="lux-card lux-card-lg p-4 p-lg-5" method="post" action="/connexion" novalidate>
                    <?= csrf_field() ?>
                    <img class="site-logo mb-4" src="/assets/img/logo-mijote-maison.svg" alt="">
                    <span class="badge-soft badge-tomato">Accès back-office</span>
                    <h1 class="display-font display-6 fw-bold mt-3">Page de connexion</h1>
                    <p class="small text-muted">Formulaire de connexion permettant l'accès au back-office.</p>
                    <div class="alert alert-light border rounded-4 small">
                        <p class="mb-1"><strong>Rôle :</strong> admin</p>
                        <p class="mb-0"><strong>Inscription publique :</strong> non disponible</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="email">Email</label>
                        <input class="form-control" id="email" name="email" type="email" autocomplete="email" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold" for="password">Mot de passe</label>
                        <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Se connecter au back-office</button>
                </form>
            </div>
        </div>
    </div>
</section>
