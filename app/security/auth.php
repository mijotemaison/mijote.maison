<?php

declare(strict_types=1);

const ADMIN_SESSION_TIMEOUT_SECONDS = 1800;

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('secure_recipes_session');
    session_start();
}

function login_admin(array $admin): void
{
    session_regenerate_id(true);
    $_SESSION['admin_id'] = (int) $admin['id'];
    $_SESSION['admin_email'] = (string) $admin['email'];
    $_SESSION['admin_username'] = (string) $admin['username'];
    $_SESSION['admin_last_activity'] = time();
}

function logout_admin(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
}

function is_admin_authenticated(): bool
{
    enforce_admin_session_timeout();
    return isset($_SESSION['admin_id']);
}

function require_admin(): void
{
    enforce_admin_session_timeout();
    if (!is_admin_authenticated()) {
        flash('error', 'Acces reserve aux administrateurs authentifies.');
        redirect('/connexion');
    }
    $_SESSION['admin_last_activity'] = time();
}

function enforce_admin_session_timeout(): void
{
    if (!isset($_SESSION['admin_id'])) {
        return;
    }

    $lastActivity = (int) ($_SESSION['admin_last_activity'] ?? time());
    if (time() - $lastActivity <= ADMIN_SESSION_TIMEOUT_SECONDS) {
        return;
    }

    unset($_SESSION['admin_id'], $_SESSION['admin_email'], $_SESSION['admin_username'], $_SESSION['admin_last_activity']);
    session_regenerate_id(true);
    flash('error', 'Session expiree apres inactivite. Merci de vous reconnecter.');
}

function current_admin_email(): string
{
    return (string) ($_SESSION['admin_email'] ?? '');
}

function current_admin_id(): int
{
    return (int) ($_SESSION['admin_id'] ?? 0);
}
