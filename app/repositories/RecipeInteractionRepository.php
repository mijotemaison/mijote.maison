<?php

declare(strict_types=1);

final class RecipeInteractionRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function ratingSummary(int $recipeId): array
    {
        $stmt = $this->pdo->prepare('SELECT ROUND(AVG(rating), 1) AS average_rating, COUNT(*) AS rating_count FROM recipe_ratings WHERE recipe_id = :recipe_id');
        $stmt->execute(['recipe_id' => $recipeId]);
        $summary = $stmt->fetch() ?: [];

        return [
            'average' => (float) ($summary['average_rating'] ?? 0),
            'count' => (int) ($summary['rating_count'] ?? 0),
        ];
    }

    public function ratingSummariesForRecipeIds(array $recipeIds): array
    {
        $ids = array_values(array_unique(array_map('intval', $recipeIds)));
        if ($ids === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare(
            "SELECT recipe_id, ROUND(AVG(rating), 1) AS average_rating, COUNT(*) AS rating_count
             FROM recipe_ratings
             WHERE recipe_id IN ($placeholders)
             GROUP BY recipe_id"
        );
        $stmt->execute($ids);

        $summaries = [];
        foreach ($stmt->fetchAll() as $row) {
            $summaries[(int) $row['recipe_id']] = [
                'average' => (float) $row['average_rating'],
                'count' => (int) $row['rating_count'],
            ];
        }

        return $summaries;
    }

    public function userRating(int $recipeId, string $voterHash): ?int
    {
        $stmt = $this->pdo->prepare('SELECT rating FROM recipe_ratings WHERE recipe_id = :recipe_id AND voter_hash = :voter_hash LIMIT 1');
        $stmt->execute(['recipe_id' => $recipeId, 'voter_hash' => $voterHash]);
        $rating = $stmt->fetchColumn();

        return $rating === false ? null : (int) $rating;
    }

    public function rate(int $recipeId, int $rating, string $voterHash): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO recipe_ratings (recipe_id, rating, voter_hash, created_at, updated_at)
             VALUES (:recipe_id, :rating, :voter_hash, NOW(), NOW())
             ON DUPLICATE KEY UPDATE rating = VALUES(rating), updated_at = NOW()'
        );
        $stmt->execute([
            'recipe_id' => $recipeId,
            'rating' => $rating,
            'voter_hash' => $voterHash,
        ]);
    }

    public function approvedComments(int $recipeId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM recipe_comments
             WHERE recipe_id = :recipe_id AND status = 'approved'
             ORDER BY created_at DESC, id DESC"
        );
        $stmt->execute(['recipe_id' => $recipeId]);

        return $stmt->fetchAll();
    }

    public function createComment(int $recipeId, string $authorName, string $content, string $visitorHash): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO recipe_comments (recipe_id, author_name, content, status, visitor_hash, created_at, updated_at)
             VALUES (:recipe_id, :author_name, :content, 'pending', :visitor_hash, NOW(), NOW())"
        );
        $stmt->execute([
            'recipe_id' => $recipeId,
            'author_name' => $authorName,
            'content' => $content,
            'visitor_hash' => $visitorHash,
        ]);
    }

    public function allComments(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.*, r.title AS recipe_title, r.slug AS recipe_slug
             FROM recipe_comments c
             INNER JOIN recipes r ON r.id = c.recipe_id
             ORDER BY FIELD(c.status, "pending", "approved", "rejected"), c.created_at DESC, c.id DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countPendingComments(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM recipe_comments WHERE status = 'pending'");
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function updateCommentStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare('UPDATE recipe_comments SET status = :status, updated_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function deleteComment(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM recipe_comments WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
