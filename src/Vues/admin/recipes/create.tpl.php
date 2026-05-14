<?php render_flash(); ?>
<h1 class="display-font display-6 fw-bold mb-4">Créer une recette</h1>
<?php if (isset($errors['global'])): ?><div class="alert alert-danger rounded-4"><?= e($errors['global']) ?></div><?php endif; ?>
<form class="admin-card vstack gap-4 p-4 p-lg-5" method="post" enctype="multipart/form-data" action="/admin/recettes/creer" novalidate>
    <?= csrf_field() ?>
    <?php require __DIR__ . '/form.tpl.php'; ?>
    <div class="d-flex flex-wrap gap-3">
        <button class="btn btn-primary" type="submit">Créer</button>
        <a class="btn btn-outline-secondary" href="/admin/recettes">Annuler</a>
    </div>
</form>
