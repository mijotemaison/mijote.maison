<?php

declare(strict_types=1);

use App\Repository\LoginAttemptRepository;

const MAX_LOGIN_FAILURES = 5;
const LOGIN_WINDOW_MINUTES = 15;
const LOGIN_BLOCK_MINUTES = 15;

function client_ip(): string
{
    return substr($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', 0, 45);
}

function user_agent(): string
{
    return substr($_SERVER['HTTP_USER_AGENT'] ?? 'unknown', 0, 255);
}

function login_is_blocked(PDO $pdo, string $email): bool
{
    $repo = new LoginAttemptRepository($pdo);
    $failures = $repo->countRecentFailures($email, client_ip(), LOGIN_WINDOW_MINUTES);

    return $failures >= MAX_LOGIN_FAILURES;
}

function record_login_attempt(PDO $pdo, string $email, bool $success): void
{
    $repo = new LoginAttemptRepository($pdo);
    $repo->create([
        'email' => $email,
        'ip_address' => client_ip(),
        'user_agent' => user_agent(),
        'success' => $success ? 1 : 0,
    ]);
}
