<?php
$values = [
    'username' => $_POST['username'] ?? ($admin['username'] ?? ''),
    'email' => $_POST['email'] ?? ($admin['email'] ?? ''),
];
?>
<div>
    <label class="label" for="username">Nom administrateur</label>
    <input class="field" id="username" name="username" value="<?= e($values['username']) ?>" maxlength="80" required>
    <?php if (isset($errors['username'])): ?><p class="mt-2 text-sm text-rose-200"><?= e($errors['username']) ?></p><?php endif; ?>
</div>
<div>
    <label class="label" for="email">Email</label>
    <input class="field" id="email" name="email" type="email" value="<?= e($values['email']) ?>" maxlength="190" required>
    <?php if (isset($errors['email'])): ?><p class="mt-2 text-sm text-rose-200"><?= e($errors['email']) ?></p><?php endif; ?>
</div>
<div>
    <label class="label" for="password">Mot de passe</label>
    <input class="field" id="password" name="password" type="password" autocomplete="new-password">
    <?php if (isset($errors['password'])): ?><p class="mt-2 text-sm text-rose-200"><?= e($errors['password']) ?></p><?php endif; ?>
</div>
