<?php

declare(strict_types=1);

function validate_admin_input(array $data, bool $passwordRequired = true): array
{
    $errors = [];

    $username = trim((string) ($data['username'] ?? ''));
    $email = trim((string) ($data['email'] ?? ''));
    $password = (string) ($data['password'] ?? '');

    if ($username === '' || mb_strlen($username) > 80) {
        $errors['username'] = 'Le nom administrateur est obligatoire et limite a 80 caracteres.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
        $errors['email'] = 'Email administrateur invalide.';
    }

    if ($passwordRequired || $password !== '') {
        if (mb_strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caracteres, une majuscule et un chiffre.';
        }
    }

    return $errors;
}

function clean_admin_input(array $data): array
{
    return [
        'username' => trim((string) ($data['username'] ?? '')),
        'email' => trim((string) ($data['email'] ?? '')),
        'password' => (string) ($data['password'] ?? ''),
    ];
}
