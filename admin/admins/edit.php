<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/AdminRepository.php';
require_once BASE_PATH . '/app/validation/admin_validation.php';

require_admin();

$repo = new AdminRepository(db());
$admin = $repo->find((int) ($_GET['id'] ?? $_POST['id'] ?? 0));
if (!$admin) {
    flash('error', 'Administrateur introuvable.');
    redirect('/admin/admins/index.php');
}

$errors = [];

if (is_post()) {
    require_valid_csrf();
    $data = clean_admin_input($_POST);
    $errors = validate_admin_input($data, false);

    if (!$errors) {
        try {
            if ($repo->emailExists($data['email'], (int) $admin['id'])) {
                $errors['email'] = 'Cet email est deja utilise.';
            } else {
                $payload = [
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password_hash' => $data['password'] !== '' ? password_hash($data['password'], PASSWORD_DEFAULT) : null,
                ];
                $repo->update((int) $admin['id'], $payload);
                if ((int) $admin['id'] === (int) ($_SESSION['admin_id'] ?? 0)) {
                    $_SESSION['admin_email'] = $data['email'];
                    $_SESSION['admin_username'] = $data['username'];
                }
                flash('success', 'Administrateur modifie.');
                redirect('/admin/admins/index.php');
            }
        } catch (Throwable $exception) {
            $errors['global'] = 'Modification impossible.';
        }
    }
}

admin_header('Modifier un administrateur');
?>
<?php render_flash(); ?>
<h1 class="mb-6 text-3xl font-bold text-white">Modifier un administrateur</h1>
<?php if (isset($errors['global'])): ?><div class="mb-4 rounded-lg border border-rose-400/40 bg-rose-500/10 p-4 text-rose-100"><?= e($errors['global']) ?></div><?php endif; ?>
<form class="panel-card grid gap-5 p-6" method="post" action="/admin/admins/edit.php?id=<?= e($admin['id']) ?>" novalidate>
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= e($admin['id']) ?>">
    <?php require __DIR__ . '/form.php'; ?>
    <p class="text-sm text-slate-400">Laissez le mot de passe vide pour le conserver.</p>
    <div class="flex gap-3">
        <button class="btn-primary" type="submit">Enregistrer</button>
        <a class="btn-secondary" href="/admin/admins/index.php">Annuler</a>
    </div>
</form>
<?php admin_footer(); ?>
