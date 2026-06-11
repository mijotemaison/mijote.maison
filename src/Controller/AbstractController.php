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

    protected function renderPublic(string $title, string $template, array $data = [], ?array $og = null, string $afterHeaderHtml = ''): void
    {
        \public_header($title, $og);
        if ($afterHeaderHtml !== '') {
            echo $afterHeaderHtml;
        }
        $this->render($template, $data);
        \public_footer();
    }

    protected function renderAdmin(string $title, string $template, array $data = []): void
    {
        \admin_header($title);
        $this->render($template, $data);
        \admin_footer();
    }

    protected function renderPrint(string $template, array $data = []): void
    {
        $this->render($template, $data);
    }
}
