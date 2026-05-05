<?php

declare(strict_types=1);

function upload_recipe_image(array $file): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Le fichier envoye est invalide.'];
    }

    $maxSize = 2 * 1024 * 1024;
    if (($file['size'] ?? 0) > $maxSize) {
        return ['path' => null, 'error' => 'Image trop lourde : limite 2 Mo.'];
    }

    $originalName = (string) ($file['name'] ?? '');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($extension, $allowedExtensions, true)) {
        return ['path' => null, 'error' => 'Extension refusee. Formats acceptes : jpg, jpeg, png, webp.'];
    }

    $tmpPath = (string) ($file['tmp_name'] ?? '');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmpPath) ?: '';
    $allowedMimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
    ];

    if (($allowedMimes[$extension] ?? '') !== $mime) {
        return ['path' => null, 'error' => 'Type MIME refuse.'];
    }

    if (!is_dir(UPLOAD_RECIPE_DIR)) {
        mkdir(UPLOAD_RECIPE_DIR, 0755, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $extension;
    $destination = UPLOAD_RECIPE_DIR . '/' . $filename;

    if (!move_uploaded_file($tmpPath, $destination)) {
        return ['path' => null, 'error' => 'Impossible de sauvegarder l’image.'];
    }

    return ['path' => 'uploads/recipes/' . $filename, 'error' => null];
}

function delete_recipe_image(?string $path): void
{
    if (!$path) {
        return;
    }

    $fullPath = PUBLIC_PATH . '/' . ltrim($path, '/');
    $uploadsRoot = realpath(UPLOAD_RECIPE_DIR);
    $target = realpath($fullPath);

    if ($uploadsRoot && $target && str_starts_with($target, $uploadsRoot) && is_file($target)) {
        unlink($target);
    }
}
