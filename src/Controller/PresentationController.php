<?php

declare(strict_types=1);

namespace App\Controller;

final class PresentationController extends AbstractController
{
    public function index(): void
    {
        $presentationParts = $this->parts();
        $slides = $this->slides();
        $slides = $this->withParts($slides, $presentationParts);

        $this->renderPublic('Présentation', 'presentation', compact('slides', 'presentationParts'));
    }

    private function slides(): array
    {
        return require BASE_PATH . '/src/Data/presentation_slides.php';
    }

    private function parts(): array
    {
        return require BASE_PATH . '/src/Data/presentation_parts.php';
    }

    private function withParts(array $slides, array $parts): array
    {
        foreach ($slides as $index => $slide) {
            $slideNumber = $index + 1;
            foreach ($parts as $part) {
                if ($slideNumber >= $part['start'] && $slideNumber <= $part['end']) {
                    $slides[$index]['part'] = $part;
                    break;
                }
            }
        }

        return $slides;
    }
}
