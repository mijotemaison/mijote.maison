<?php

declare(strict_types=1);

namespace App\Controller;

abstract class AbstractController
{
    protected function render(string $template, array $data = []): void
    {
        $viewPath = BASE_PATH . '/src/Vues/' . $template . '.tpl.php';

        if (!is_file($viewPath)) {
            http_response_code(500);
            echo 'Vue introuvable.';
            return;
        }

        extract($data, EXTR_SKIP);
        require $viewPath;
    }

    protected function renderLegacyPublicPage(string $file): void
    {
        require PUBLIC_PATH . '/' . $file;
    }
}
