<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Admin;
use Throwable;

require_once BASE_PATH . '/app/security/brute_force.php';

final class AuthController extends AbstractController
{
    public function login(): void
    {
        if (\is_admin_authenticated()) {
            \redirect('/admin/dashboard');
        }

        if (\is_post()) {
            $this->handleLogin();
        }

        \public_header('Page de connexion');
        $this->render('login');
    }

    public function logout(): void
    {
        \logout_admin();
        \redirect('/connexion');
    }

    private function handleLogin(): void
    {
        \require_valid_csrf();
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        try {
            $pdo = \db();
            if (\login_is_blocked($pdo, $email)) {
                \record_security_event($pdo, 'login_blocked', 'Connexion temporairement bloquee apres echecs repetes.', $email);
                \flash('error', 'Trop de tentatives. Réessayez dans quelques minutes.');
                \redirect('/connexion');
            }

            $adminModel = new Admin($pdo);
            $admin = filter_var($email, FILTER_VALIDATE_EMAIL) ? $adminModel->findByEmail($email) : null;
            $valid = $admin && password_verify($password, (string) $admin['password_hash']);
            \record_login_attempt($pdo, $email, (bool) $valid);

            if (!$valid) {
                \record_security_event($pdo, 'login_failed', 'Tentative de connexion admin refusee.', $email);
                \flash('error', 'Identifiants invalides.');
                \redirect('/connexion');
            }

            if (\admin_password_needs_rehash((string) $admin['password_hash'])) {
                $adminModel->updatePasswordHash((int) $admin['id'], \admin_password_hash($password));
                \record_security_event($pdo, 'password_rehashed', 'Hash du mot de passe admin mis a jour vers l algorithme courant.', (string) $admin['email']);
            }

            \login_admin($admin);
            \record_security_event($pdo, 'login_success', 'Connexion administrateur reussie.', (string) $admin['email']);
            \flash('success', 'Connexion réussie.');
            \redirect('/admin/dashboard');
        } catch (Throwable) {
            \flash('error', 'Connexion impossible pour le moment.');
            \redirect('/connexion');
        }
    }
}
