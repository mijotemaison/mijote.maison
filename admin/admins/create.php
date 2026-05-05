<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/AdminRepository.php';
require_once BASE_PATH . '/app/validation/admin_validation.php';

require_admin();

$errors = [];

if (is_post()) {
    require_valid_csrf();
    $data = clean_admin_input($_POST);
    $errors = validate_admin_input($data, true);

    if (!$errors) {
        try {
            $repo = new AdminRepository(db());
            if ($repo->emailExists($data['email'])) {
                $errors['email'] = 'Cet email est deja utilise.';
            } else {
                $repo->create([
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                ]);
                flash('success', 'Administrateur ajoute.');
                redirect('/admin/admins/index.php');
            }
        } catch (Throwable $exception) {
            $errors['global'] = 'Creation impossible.';
        }
    }
}

admin_header('Ajouter un administrateur');
?>
<?php render_flash(); ?>
<h1 class="mb-6 text-3xl font-bold text-white">Ajouter un administrateur</h1>
<?php if (isset($errors['global'])): ?><div class="mb-4 rounded-lg border border-rose-400/40 bg-rose-500/10 p-4 text-rose-100"><?= e($errors['global']) ?></div><?php endif; ?>
<form class="panel-card grid gap-5 p-6" method="post" action="/admin/admins/create.php" novalidate>
    <?= csrf_field() ?>
    <?php require __DIR__ . '/form.php'; ?>
    <div class="flex gap-3">
        <button class="btn-primary" type="submit">Ajouter</button>
        <a class="btn-secondary" href="/admin/admins/index.php">Annuler</a>
    </div>
</form>
<?php admin_footer(); ?>
