<section class="bg-[#fff1dc]">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-8 sm:px-6 lg:px-8">
        <a class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-extrabold text-herb shadow-sm hover:text-tomato" href="/recettes">← Retour aux recettes</a>
        <?php if ($recipe): ?>
            <button class="btn-secondary print:hidden" type="button" data-print-recipe>Imprimer la recette</button>
        <?php endif; ?>
    </div>
</section>
<?php render_flash(); ?>

<?php if ($error): ?>
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-amber-900"><?= e($error) ?></div>
    </section>
<?php elseif (!$recipe): ?>
    <section class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-orange-100 bg-white p-8 shadow-sm">
            <h1 class="font-serif text-4xl font-bold text-stone-950">Recette introuvable</h1>
            <p class="mt-3 text-stone-600">La recette demandée n'existe pas ou n'est plus disponible.</p>
        </div>
    </section>
<?php else: ?>
    <article>
        <section class="bg-[#fff1dc] pb-12">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[1.08fr_.92fr] lg:items-center lg:px-8">
                <div>
                    <div class="flex flex-wrap gap-2 text-sm font-extrabold">
                        <span class="rounded-full bg-white px-4 py-2 text-tomato shadow-sm"><?= e(recipe_category_label($recipe['category'] ?? null)) ?></span>
                        <span class="rounded-full bg-white px-4 py-2 text-herb shadow-sm"><?= e($meta['time']) ?></span>
                        <span class="rounded-full bg-white px-4 py-2 text-amber-700 shadow-sm"><?= e($meta['level']) ?></span>
                        <span class="rounded-full bg-white px-4 py-2 text-stone-700 shadow-sm"><?= e((string) ($recipe['view_count'] ?? 0)) ?> vues</span>
                    </div>
                    <h1 class="mt-6 max-w-4xl font-serif text-5xl font-bold leading-tight text-stone-950 sm:text-7xl"><?= e($recipe['title']) ?></h1>
                    <p class="mt-5 max-w-2xl text-xl leading-9 text-stone-700"><?= e($recipe['description']) ?></p>
                </div>
                <img class="aspect-[4/3] w-full rounded-[2rem] object-cover shadow-2xl shadow-orange-900/20" src="<?= e(recipe_image_url($recipe['image_path'])) ?>" alt="">
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[.82fr_1.18fr]">
                <aside class="space-y-6">
                    <div class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-sm">
                        <h2 class="font-serif text-3xl font-bold text-stone-950">En bref</h2>
                        <dl class="mt-5 grid gap-3 text-sm">
                            <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3"><dt class="font-extrabold text-stone-600">Temps</dt><dd class="font-extrabold text-tomato"><?= e($meta['time']) ?></dd></div>
                            <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3"><dt class="font-extrabold text-stone-600">Difficulté</dt><dd class="font-extrabold text-herb"><?= e($meta['level']) ?></dd></div>
                            <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3"><dt class="font-extrabold text-stone-600">Portions</dt><dd class="font-extrabold text-stone-900"><?= e($meta['servings']) ?></dd></div>
                            <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3"><dt class="font-extrabold text-stone-600">Ambiance</dt><dd class="font-extrabold text-amber-700"><?= e($meta['season']) ?></dd></div>
                            <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3"><dt class="font-extrabold text-stone-600">Vues</dt><dd class="font-extrabold text-stone-900"><?= e((string) ($recipe['view_count'] ?? 0)) ?></dd></div>
                        </dl>
                        <div class="mt-5 rounded-3xl bg-white p-4 ring-1 ring-orange-100">
                            <p class="text-sm font-extrabold uppercase tracking-[0.14em] text-tomato">Note des lecteurs</p>
                            <div class="mt-2 flex items-center gap-3">
                                <?= render_stars((float) $ratingSummary['average'], 'text-2xl') ?>
                                <span class="font-extrabold text-stone-900"><?= e($ratingSummary['count'] > 0 ? number_format((float) $ratingSummary['average'], 1, ',', ' ') . '/5' : 'Aucune note') ?></span>
                            </div>
                            <p class="mt-1 text-sm text-stone-500"><?= e((string) $ratingSummary['count']) ?> avis enregistré(s)</p>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-emerald-100 bg-emerald-50 p-6 text-herb">
                        <p class="text-sm font-extrabold uppercase tracking-[0.16em]">Astuce maison</p>
                        <p class="mt-3 leading-7">Préparez les ingrédients avant de commencer : la recette devient plus fluide et la cuisson plus régulière.</p>
                    </div>
                </aside>

                <div class="space-y-8">
                    <section class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="font-serif text-3xl font-bold text-stone-950">Ingrédients</h2>
                        <div class="mt-6 whitespace-pre-line rounded-3xl bg-orange-50 p-6 text-lg leading-9 text-stone-700"><?= e($recipe['ingredients']) ?></div>
                    </section>
                    <section class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="font-serif text-3xl font-bold text-stone-950">Préparation</h2>
                        <div class="mt-6 space-y-4">
                            <?php foreach (preg_split('/\R+/', (string) $recipe['preparation_steps']) as $index => $step): ?>
                                <?php if (trim($step) === '') { continue; } ?>
                                <div class="grid gap-4 rounded-3xl bg-[#fff7ed] p-5 sm:grid-cols-[3rem_1fr]">
                                    <span class="grid h-12 w-12 place-items-center rounded-full bg-tomato text-lg font-extrabold text-white"><?= e($index + 1) ?></span>
                                    <p class="text-lg leading-8 text-stone-700"><?= e($step) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
            </div>
        </section>

        <section id="avis" class="bg-[#fff1dc] py-14">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-[.85fr_1.15fr] lg:px-8">
                <aside class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-xl shadow-orange-900/10 sm:p-8">
                    <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-tomato">Avis lecteurs</p>
                    <h2 class="mt-3 font-serif text-4xl font-bold text-stone-950">Donner une note</h2>
                    <p class="mt-3 leading-7 text-stone-600">Votre note aide les prochains visiteurs à choisir une recette. Elle peut être modifiée si vous votez à nouveau.</p>
                    <form class="mt-6 grid gap-4" method="post" action="<?= e(recipe_url((string) $recipe['slug'])) ?>#avis">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="rate">
                        <div class="flex flex-wrap gap-2" role="group" aria-label="Noter la recette">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <button class="rounded-full border <?= $userRating === $i ? 'border-amber-500 bg-amber-400 text-stone-950' : 'border-orange-200 bg-orange-50 text-amber-600' ?> px-4 py-2 text-xl font-black transition hover:border-amber-500 hover:bg-amber-100" type="submit" name="rating" value="<?= e($i) ?>" aria-label="Noter <?= e($i) ?> sur 5">
                                    <?= str_repeat('★', $i) ?>
                                </button>
                            <?php endfor; ?>
                        </div>
                        <?php if ($userRating): ?>
                            <p class="text-sm font-bold text-herb">Votre note actuelle : <?= e($userRating) ?>/5.</p>
                        <?php endif; ?>
                    </form>

                    <hr class="my-8 border-orange-100">

                    <h3 class="font-serif text-3xl font-bold text-stone-950">Laisser un commentaire</h3>
                    <p class="mt-2 text-sm leading-6 text-stone-600">Les commentaires sont relus avant publication pour garder une page utile et agréable.</p>
                    <form class="mt-5 grid gap-4" method="post" action="<?= e(recipe_url((string) $recipe['slug'])) ?>#avis">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="comment">
                        <label class="hidden" aria-hidden="true">Site web
                            <input name="website" tabindex="-1" autocomplete="off">
                        </label>
                        <div>
                            <label class="mb-2 block text-sm font-extrabold text-stone-700" for="author_name">Nom</label>
                            <input class="w-full rounded-2xl border border-orange-200 bg-orange-50 px-4 py-3 text-stone-900 outline-none transition focus:border-tomato focus:ring-4 focus:ring-orange-200" id="author_name" name="author_name" maxlength="80" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-extrabold text-stone-700" for="content">Commentaire</label>
                            <textarea class="w-full rounded-2xl border border-orange-200 bg-orange-50 px-4 py-3 text-stone-900 outline-none transition focus:border-tomato focus:ring-4 focus:ring-orange-200" id="content" name="content" rows="5" maxlength="800" required></textarea>
                        </div>
                        <button class="btn-primary" type="submit">Envoyer le commentaire</button>
                    </form>
                </aside>

                <div class="rounded-[2rem] border border-orange-100 bg-white p-6 shadow-xl shadow-orange-900/10 sm:p-8">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-herb">Commentaires</p>
                            <h2 class="mt-2 font-serif text-4xl font-bold text-stone-950">Ce qu’en pensent les lecteurs</h2>
                        </div>
                        <span class="rounded-full bg-orange-50 px-4 py-2 text-sm font-extrabold text-tomato"><?= e((string) count($comments)) ?> publié(s)</span>
                    </div>
                    <div class="mt-7 space-y-4">
                        <?php if (!$comments): ?>
                            <p class="rounded-3xl bg-orange-50 p-5 text-stone-600">Aucun commentaire publié pour le moment.</p>
                        <?php endif; ?>
                        <?php foreach ($comments as $comment): ?>
                            <article class="rounded-3xl border border-orange-100 bg-[#fffaf3] p-5">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <h3 class="font-serif text-2xl font-bold text-stone-950"><?= e($comment['author_name']) ?></h3>
                                    <time class="text-xs font-bold uppercase tracking-[0.12em] text-stone-500" datetime="<?= e($comment['created_at']) ?>"><?= e(date('d/m/Y', strtotime((string) $comment['created_at']))) ?></time>
                                </div>
                                <p class="mt-3 whitespace-pre-line leading-7 text-stone-700"><?= e($comment['content']) ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    </article>
<?php endif; ?>
<?php public_footer(); ?>
