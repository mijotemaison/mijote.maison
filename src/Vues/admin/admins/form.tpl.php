<?php
$values = [
    'username' => $_POST['username'] ?? ($admin['username'] ?? ''),
    'email' => $_POST['email'] ?? ($admin['email'] ?? ''),
];
?>
<div>
    <label class="form-label fw-bold" for="username">Nom administrateur</label>
    <input class="form-control" id="username" name="username" value="<?= e($values['username']) ?>" maxlength="80" required>
    <?php if (isset($errors['username'])): ?><p class="text-danger small mt-2"><?= e($errors['username']) ?></p><?php endif; ?>
</div>
<div>
    <label class="form-label fw-bold" for="email">Email</label>
    <input class="form-control" id="email" name="email" type="email" value="<?= e($values['email']) ?>" maxlength="190" required>
    <?php if (isset($errors['email'])): ?><p class="text-danger small mt-2"><?= e($errors['email']) ?></p><?php endif; ?>
</div>
<div>
    <label class="form-label fw-bold" for="password">Mot de passe</label>
    <input class="form-control" id="password" name="password" type="password" autocomplete="new-password">
    <?php if (isset($errors['password'])): ?><p class="text-danger small mt-2"><?= e($errors['password']) ?></p><?php endif; ?>
</div>
