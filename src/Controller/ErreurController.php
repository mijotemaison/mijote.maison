<?php

declare(strict_types=1);

namespace App\Controller;

final class ErreurController extends AbstractController
{
    public function notFound(): void
    {
        http_response_code(404);
        $this->renderPublic('Page introuvable', '404', [
            'title' => 'Page introuvable',
            'message' => 'La page demandee n existe pas ou a ete deplacee.',
        ]);
    }
}
