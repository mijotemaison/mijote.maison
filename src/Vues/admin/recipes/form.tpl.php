<?php
$values = [
    'title' => $_POST['title'] ?? ($recipe['title'] ?? ''),
    'short_description' => $_POST['short_description'] ?? ($recipe['short_description'] ?? ''),
    'description' => $_POST['description'] ?? ($recipe['description'] ?? ''),
    'ingredients' => $_POST['ingredients'] ?? ($recipe['ingredients'] ?? ''),
    'preparation_steps' => $_POST['preparation_steps'] ?? ($recipe['preparation_steps'] ?? ''),
    'category' => $_POST['category'] ?? ($recipe['category'] ?? 'plats'),
    'status' => $_POST['status'] ?? ($recipe['status'] ?? 'draft'),
];
?>
<div>
    <label class="form-label fw-bold" for="title">Titre</label>
    <input class="form-control" id="title" name="title" value="<?= e($values['title']) ?>" maxlength="150" required>
    <?php if (isset($errors['title'])): ?><p class="text-danger small mt-2"><?= e($errors['title']) ?></p><?php endif; ?>
</div>
<div>
    <label class="form-label fw-bold" for="short_description">Courte description</label>
    <textarea class="form-control" id="short_description" name="short_description" rows="2" maxlength="300" required><?= e($values['short_description']) ?></textarea>
    <?php if (isset($errors['short_description'])): ?><p class="text-danger small mt-2"><?= e($errors['short_description']) ?></p><?php endif; ?>
</div>
<div class="row g-4">
    <div class="col-lg-6">
        <label class="form-label fw-bold" for="category">Catégorie</label>
        <select class="form-select" id="category" name="category" required>
            <?php foreach (recipe_categories() as $value => $label): ?>
                <option value="<?= e($value) ?>" <?= $values['category'] === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['category'])): ?><p class="text-danger small mt-2"><?= e($errors['category']) ?></p><?php endif; ?>
    </div>
    <div class="col-lg-6">
        <label class="form-label fw-bold" for="status">Statut de publication</label>
        <select class="form-select" id="status" name="status" required>
            <?php foreach (recipe_statuses() as $value => $label): ?>
                <option value="<?= e($value) ?>" <?= $values['status'] === $value ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['status'])): ?><p class="text-danger small mt-2"><?= e($errors['status']) ?></p><?php endif; ?>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-6">
        <label class="form-label fw-bold" for="ingredients">Ingrédients</label>
        <textarea class="form-control" id="ingredients" name="ingredients" rows="8" required><?= e($values['ingredients']) ?></textarea>
        <?php if (isset($errors['ingredients'])): ?><p class="text-danger small mt-2"><?= e($errors['ingredients']) ?></p><?php endif; ?>
    </div>
    <div class="col-lg-6">
        <label class="form-label fw-bold" for="preparation_steps">Étapes</label>
        <textarea class="form-control" id="preparation_steps" name="preparation_steps" rows="8" required><?= e($values['preparation_steps']) ?></textarea>
        <?php if (isset($errors['preparation_steps'])): ?><p class="text-danger small mt-2"><?= e($errors['preparation_steps']) ?></p><?php endif; ?>
    </div>
</div>
<div>
    <label class="form-label fw-bold" for="description">Description détaillée</label>
    <textarea class="form-control" id="description" name="description" rows="5" required><?= e($values['description']) ?></textarea>
    <?php if (isset($errors['description'])): ?><p class="text-danger small mt-2"><?= e($errors['description']) ?></p><?php endif; ?>
</div>
<div>
    <label class="form-label fw-bold" for="image">Image recette</label>
    <input class="form-control" id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
    <?php if (isset($errors['image'])): ?><p class="text-danger small mt-2"><?= e($errors['image']) ?></p><?php endif; ?>
</div>
