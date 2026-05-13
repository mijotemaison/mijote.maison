<?php render_flash(); ?>
<h1 class="mb-6 text-3xl font-bold text-white">Ajouter un administrateur</h1>
<?php if (isset($errors['global'])): ?><div class="mb-4 rounded-lg border border-rose-400/40 bg-rose-500/10 p-4 text-rose-100"><?= e($errors['global']) ?></div><?php endif; ?>
<form class="panel-card grid gap-5 p-6" method="post" action="/admin/administrateurs/creer" novalidate>
    <?= csrf_field() ?>
    <?php require __DIR__ . '/form.tpl.php'; ?>
    <div class="flex gap-3">
        <button class="btn-primary" type="submit">Ajouter</button>
        <a class="btn-secondary" href="/admin/administrateurs">Annuler</a>
    </div>
</form>
