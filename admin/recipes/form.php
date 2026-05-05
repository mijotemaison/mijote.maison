<?php
$values = [
    'title' => $_POST['title'] ?? ($recipe['title'] ?? ''),
    'short_description' => $_POST['short_description'] ?? ($recipe['short_description'] ?? ''),
    'description' => $_POST['description'] ?? ($recipe['description'] ?? ''),
    'ingredients' => $_POST['ingredients'] ?? ($recipe['ingredients'] ?? ''),
    'preparation_steps' => $_POST['preparation_steps'] ?? ($recipe['preparation_steps'] ?? ''),
];
?>
<div>
    <label class="label" for="title">Titre</label>
    <input class="field" id="title" name="title" value="<?= e($values['title']) ?>" maxlength="150" required>
    <?php if (isset($errors['title'])): ?><p class="mt-2 text-sm text-rose-200"><?= e($errors['title']) ?></p><?php endif; ?>
</div>
<div>
    <label class="label" for="short_description">Courte description</label>
    <textarea class="field" id="short_description" name="short_description" rows="2" maxlength="300" required><?= e($values['short_description']) ?></textarea>
    <?php if (isset($errors['short_description'])): ?><p class="mt-2 text-sm text-rose-200"><?= e($errors['short_description']) ?></p><?php endif; ?>
</div>
<div class="grid gap-5 lg:grid-cols-2">
    <div>
        <label class="label" for="ingredients">Ingredients</label>
        <textarea class="field" id="ingredients" name="ingredients" rows="8" required><?= e($values['ingredients']) ?></textarea>
        <?php if (isset($errors['ingredients'])): ?><p class="mt-2 text-sm text-rose-200"><?= e($errors['ingredients']) ?></p><?php endif; ?>
    </div>
    <div>
        <label class="label" for="preparation_steps">Etapes</label>
        <textarea class="field" id="preparation_steps" name="preparation_steps" rows="8" required><?= e($values['preparation_steps']) ?></textarea>
        <?php if (isset($errors['preparation_steps'])): ?><p class="mt-2 text-sm text-rose-200"><?= e($errors['preparation_steps']) ?></p><?php endif; ?>
    </div>
</div>
<div>
    <label class="label" for="description">Description detaillee</label>
    <textarea class="field" id="description" name="description" rows="5" required><?= e($values['description']) ?></textarea>
    <?php if (isset($errors['description'])): ?><p class="mt-2 text-sm text-rose-200"><?= e($errors['description']) ?></p><?php endif; ?>
</div>
<div>
    <label class="label" for="image">Image recette</label>
    <input class="field" id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
    <?php if (isset($errors['image'])): ?><p class="mt-2 text-sm text-rose-200"><?= e($errors['image']) ?></p><?php endif; ?>
</div>
