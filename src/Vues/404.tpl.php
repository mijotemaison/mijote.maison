<section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-[2rem] border border-orange-100 bg-white p-8 text-center shadow-sm">
        <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Erreur 404</p>
        <h1 class="mt-3 font-serif text-5xl font-bold text-stone-950"><?= e($title ?? 'Page introuvable') ?></h1>
        <p class="mx-auto mt-4 max-w-2xl leading-7 text-stone-600"><?= e($message ?? 'Cette page est introuvable.') ?></p>
        <a class="btn-primary mt-7" href="/">Retour a l'accueil</a>
    </div>
</section>
