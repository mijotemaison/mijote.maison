<?php

declare(strict_types=1);

function generate_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(generate_csrf_token()) . '">';
}

function verify_csrf_token(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals((string) $_SESSION['csrf_token'], $token);
}

function require_valid_csrf(): void
{
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        flash('error', 'Jeton CSRF invalide. Action refusee.');
        redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}
