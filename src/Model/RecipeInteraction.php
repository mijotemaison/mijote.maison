<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

require_once BASE_PATH . '/app/repositories/RecipeInteractionRepository.php';

final class RecipeInteraction
{
    private \RecipeInteractionRepository $repository;

    public function __construct(private PDO $pdo)
    {
        $this->repository = new \RecipeInteractionRepository($pdo);
    }

    public function ratingSummary(int $recipeId): array
    {
        return $this->repository->ratingSummary($recipeId);
    }

    public function ratingSummariesForRecipeIds(array $recipeIds): array
    {
        return $this->repository->ratingSummariesForRecipeIds($recipeIds);
    }

    public function userRating(int $recipeId, string $voterHash): ?int
    {
        return $this->repository->userRating($recipeId, $voterHash);
    }

    public function rate(int $recipeId, int $rating, string $voterHash): void
    {
        $this->repository->rate($recipeId, $rating, $voterHash);
    }

    public function approvedComments(int $recipeId): array
    {
        return $this->repository->approvedComments($recipeId);
    }

    public function createComment(int $recipeId, string $authorName, string $content, string $visitorHash): void
    {
        $this->repository->createComment($recipeId, $authorName, $content, $visitorHash);
    }
}
