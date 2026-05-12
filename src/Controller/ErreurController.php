<?php

declare(strict_types=1);

namespace App\Controller;

final class ErreurController extends AbstractController
{
    public function notFound(): void
    {
        http_response_code(404);
        \public_header('Page introuvable');
        $this->render('404', [
            'title' => 'Page introuvable',
            'message' => 'La page demandee n existe pas ou a ete deplacee.',
        ]);
        \public_footer();
    }
}
