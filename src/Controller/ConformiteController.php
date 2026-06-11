<?php

declare(strict_types=1);

namespace App\Controller;

final class ConformiteController extends AbstractController
{
    public function index(): void
    {
        $criteria = $this->criteria();
        $this->renderPublic('Conformité au sujet officiel', 'conformite', compact('criteria'));
    }

    private function criteria(): array
    {
        return require BASE_PATH . '/src/Data/conformite_criteria.php';
    }
}
