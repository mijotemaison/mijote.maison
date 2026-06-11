<?php

declare(strict_types=1);

namespace App\Controller;

final class PresentationController extends AbstractController
{
    public function index(): void
    {
        $slides = $this->slides();

        $this->renderPublic('Présentation', 'presentation', compact('slides'));
    }

    private function slides(): array
    {
        return require BASE_PATH . '/src/Data/presentation_slides.php';
    }
}
