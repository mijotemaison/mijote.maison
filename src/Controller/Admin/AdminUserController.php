<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Repository\AdminRepository;
use Throwable;

final class AdminUserController extends AbstractController
{
    public function index(): void
    {
        \require_admin();

        $admins = [];
        $error = null;
        $currentAdminId = \current_admin_id();

        try {
            $admins = (new AdminRepository(\db()))->all();
        } catch (Throwable) {
            $error = 'Base de donnees indisponible.';
        }

        $this->renderAdmin('Administrateurs', 'admin/admins/index', compact('admins', 'error', 'currentAdminId'));
    }

    public function create(): void
    {
        \require_admin();

        $errors = [];
        $admin = null;

        if (\is_post()) {
            \require_valid_csrf();
            $data = \clean_admin_input($_POST);
            $errors = \validate_admin_input($data, true);

            if (!$errors) {
                try {
                    $pdo = \db();
                    $repo = new AdminRepository($pdo);
                    if ($repo->emailExists($data['email'])) {
                        $errors['email'] = 'Cet email est deja utilise.';
                    } else {
                        $newId = $repo->create([
                            'username' => $data['username'],
                            'email' => $data['email'],
                            'password_hash' => \admin_password_hash($data['password']),
                        ]);
                        \record_security_event($pdo, 'admin_created', 'Administrateur #' . $newId . ' ajoute : ' . (string) $data['email'], \current_admin_email());
                        \flash('success', 'Administrateur ajoute.');
                        \redirect('/admin/administrateurs');
                    }
                } catch (Throwable) {
                    $errors['global'] = 'Creation impossible.';
                }
            }
        }

        $this->renderAdmin('Ajouter un administrateur', 'admin/admins/create', compact('errors', 'admin'));
    }

    public function edit(string|int $id): void
    {
        \require_admin();

        $pdo = \db();
        $repo = new AdminRepository($pdo);
        $admin = $repo->find((int) $id);
        if (!$admin) {
            \flash('error', 'Administrateur introuvable.');
            \redirect('/admin/administrateurs');
        }

        $errors = [];

        if (\is_post()) {
            \require_valid_csrf();
            $data = \clean_admin_input($_POST);
            $errors = \validate_admin_input($data, false);

            if (!$errors) {
                try {
                    if ($repo->emailExists($data['email'], (int) $admin['id'])) {
                        $errors['email'] = 'Cet email est deja utilise.';
                    } else {
                        $payload = [
                            'username' => $data['username'],
                            'email' => $data['email'],
                            'password_hash' => $data['password'] !== '' ? \admin_password_hash($data['password']) : null,
                        ];
                        $repo->update((int) $admin['id'], $payload);
                        \record_security_event($pdo, 'admin_updated', 'Administrateur #' . (int) $admin['id'] . ' modifie : ' . (string) $data['email'], \current_admin_email());
                        if ((int) $admin['id'] === (int) ($_SESSION['admin_id'] ?? 0)) {
                            $_SESSION['admin_email'] = $data['email'];
                            $_SESSION['admin_username'] = $data['username'];
                        }
                        \flash('success', 'Administrateur modifie.');
                        \redirect('/admin/administrateurs');
                    }
                } catch (Throwable) {
                    $errors['global'] = 'Modification impossible.';
                }
            }
        }

        $this->renderAdmin('Modifier un administrateur', 'admin/admins/edit', compact('errors', 'admin'));
    }

    public function delete(string|int $id): void
    {
        \require_admin();
        \require_valid_csrf();

        $pdo = \db();
        $repo = new AdminRepository($pdo);
        $adminId = (int) $id;

        if ($repo->count() <= 1) {
            \flash('error', 'Impossible de supprimer le dernier administrateur.');
            \redirect('/admin/administrateurs');
        }

        if ($adminId === \current_admin_id()) {
            \flash('error', 'Suppression de votre propre compte refusee pendant la session active.');
            \redirect('/admin/administrateurs');
        }

        $admin = $repo->find($adminId);
        if (!$admin) {
            \flash('error', 'Administrateur introuvable.');
            \redirect('/admin/administrateurs');
        }

        $repo->delete($adminId);
        \record_security_event($pdo, 'admin_deleted', 'Administrateur #' . $adminId . ' supprime : ' . (string) $admin['email'], \current_admin_email());
        \flash('success', 'Administrateur supprime.');
        \redirect('/admin/administrateurs');
    }
}
