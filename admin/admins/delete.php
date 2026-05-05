<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/AdminRepository.php';

require_admin();

if (!is_post()) {
    flash('error', 'Suppression refusee : methode POST obligatoire.');
    redirect('/admin/admins/index.php');
}

require_valid_csrf();

$repo = new AdminRepository(db());
$id = (int) ($_POST['id'] ?? 0);

if ($repo->count() <= 1) {
    flash('error', 'Impossible de supprimer le dernier administrateur.');
    redirect('/admin/admins/index.php');
}

if ($id === (int) ($_SESSION['admin_id'] ?? 0)) {
    flash('error', 'Suppression de votre propre compte refusee pendant la session active.');
    redirect('/admin/admins/index.php');
}

$admin = $repo->find($id);
if (!$admin) {
    flash('error', 'Administrateur introuvable.');
    redirect('/admin/admins/index.php');
}

$repo->delete($id);
flash('success', 'Administrateur supprime.');
redirect('/admin/admins/index.php');
