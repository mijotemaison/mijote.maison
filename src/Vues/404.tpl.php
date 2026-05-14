<section class="py-5">
    <div class="container">
        <div class="lux-card lux-card-lg p-5 text-center mx-auto max-w-820">
            <span class="kicker">Erreur 404</span>
            <h1 class="display-font display-4 fw-bold mt-3"><?= e($title ?? 'Page introuvable') ?></h1>
            <p class="lead-luxe mx-auto max-w-620"><?= e($message ?? 'Cette page est introuvable.') ?></p>
            <a class="btn btn-primary mt-3" href="/">Retour à l'accueil</a>
        </div>
    </div>
</section>
