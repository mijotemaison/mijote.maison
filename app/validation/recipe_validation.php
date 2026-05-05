<?php

declare(strict_types=1);

function validate_recipe_input(array $data): array
{
    $errors = [];

    $title = trim((string) ($data['title'] ?? ''));
    $short = trim((string) ($data['short_description'] ?? ''));
    $description = trim((string) ($data['description'] ?? ''));
    $ingredients = trim((string) ($data['ingredients'] ?? ''));
    $steps = trim((string) ($data['preparation_steps'] ?? ''));

    if ($title === '' || mb_strlen($title) > 150) {
        $errors['title'] = 'Le titre est obligatoire et limite a 150 caracteres.';
    }
    if ($short === '' || mb_strlen($short) > 300) {
        $errors['short_description'] = 'La description courte est obligatoire et limitee a 300 caracteres.';
    }
    if ($description === '' || mb_strlen($description) > 5000) {
        $errors['description'] = 'La description est obligatoire et limitee a 5000 caracteres.';
    }
    if ($ingredients === '' || mb_strlen($ingredients) > 5000) {
        $errors['ingredients'] = 'Les ingredients sont obligatoires et limites a 5000 caracteres.';
    }
    if ($steps === '' || mb_strlen($steps) > 7000) {
        $errors['preparation_steps'] = 'Les etapes sont obligatoires et limitees a 7000 caracteres.';
    }

    return $errors;
}

function clean_recipe_input(array $data): array
{
    return [
        'title' => trim((string) ($data['title'] ?? '')),
        'short_description' => trim((string) ($data['short_description'] ?? '')),
        'description' => trim((string) ($data['description'] ?? '')),
        'ingredients' => trim((string) ($data['ingredients'] ?? '')),
        'preparation_steps' => trim((string) ($data['preparation_steps'] ?? '')),
    ];
}

function make_slug(string $title): string
{
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
    $slug = strtolower((string) $slug);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?: '';
    $slug = trim($slug, '-');

    return $slug !== '' ? $slug : 'recette';
}
