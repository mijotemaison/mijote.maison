<?php

declare(strict_types=1);

namespace App\Model;

use App\Repository\RecipeRepository;
use PDO;

final class Recipe
{
    private RecipeRepository $repository;

    public function __construct(private PDO $pdo)
    {
        $this->repository = new RecipeRepository($pdo);
    }

    public function latest(int $limit = 6): array
    {
        return $this->repository->latest($limit);
    }

    public function popular(int $limit = 4): array
    {
        return $this->repository->popular($limit);
    }

    public function countPublished(): int
    {
        return $this->repository->count();
    }

    public function published(int $limit, int $offset, string $query = '', string $category = ''): array
    {
        return $this->repository->published($limit, $offset, $query, $category);
    }

    public function countPublishedWithFilters(string $query = '', string $category = ''): int
    {
        return $this->repository->countPublished($query, $category);
    }

    public function find(?int $id = null, ?string $slug = null): ?array
    {
        if ($id !== null) {
            return $this->repository->find($id);
        }

        if ($slug !== null && $slug !== '') {
            return $this->repository->findBySlug($slug);
        }

        return null;
    }

    public function incrementViewCount(int $id): void
    {
        $this->repository->incrementViewCount($id);
    }
}
