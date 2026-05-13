<?php render_flash(); ?>
<h1 class="mb-6 text-3xl font-bold text-white">Modifier une recette</h1>
<?php if (isset($errors['global'])): ?><div class="mb-4 rounded-lg border border-rose-400/40 bg-rose-500/10 p-4 text-rose-100"><?= e($errors['global']) ?></div><?php endif; ?>
<form class="panel-card grid gap-5 p-6" method="post" enctype="multipart/form-data" action="/admin/recettes/<?= e($recipe['id']) ?>/modifier" novalidate>
    <?= csrf_field() ?>
    <?php require __DIR__ . '/form.tpl.php'; ?>
    <?php if ($recipe['image_path']): ?>
        <p class="text-sm text-slate-400">Image actuelle : <?= e($recipe['image_path']) ?></p>
    <?php endif; ?>
    <div class="flex gap-3">
        <button class="btn-primary" type="submit">Enregistrer</button>
        <a class="btn-secondary" href="/admin/recettes">Annuler</a>
    </div>
</form>
