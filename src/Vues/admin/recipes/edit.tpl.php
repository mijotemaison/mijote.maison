<?php render_flash(); ?>
<h1 class="display-font display-6 fw-bold mb-4">Modifier une recette</h1>
<?php if (isset($errors['global'])): ?><div class="alert alert-danger rounded-4"><?= e($errors['global']) ?></div><?php endif; ?>
<form class="admin-card vstack gap-4 p-4 p-lg-5" method="post" enctype="multipart/form-data" action="/admin/recettes/<?= e($recipe['id']) ?>/modifier" novalidate>
    <?= csrf_field() ?>
    <?php require __DIR__ . '/form.tpl.php'; ?>
    <?php if ($recipe['image_path']): ?>
        <p class="small text-muted mb-0">Image actuelle : <?= e($recipe['image_path']) ?></p>
    <?php endif; ?>
    <div class="d-flex flex-wrap gap-3">
        <button class="btn btn-primary" type="submit">Enregistrer</button>
        <a class="btn btn-outline-secondary" href="/admin/recettes">Annuler</a>
    </div>
</form>
