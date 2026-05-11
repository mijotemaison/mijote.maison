<?php

declare(strict_types=1);

final class RecipeRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM recipes ORDER BY created_at DESC, id DESC');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function published(int $limit = 12, int $offset = 0, string $query = '', string $category = ''): array
    {
        [$where, $params] = $this->publicFilters($query, $category);
        $sql = 'SELECT * FROM recipes ' . $where . ' ORDER BY published_at DESC, created_at DESC, id DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countPublished(string $query = '', string $category = ''): int
    {
        [$where, $params] = $this->publicFilters($query, $category);
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM recipes ' . $where);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function latest(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM recipes WHERE status = 'published' ORDER BY published_at DESC, created_at DESC, id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function count(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM recipes WHERE status = 'published'");
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM recipes WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $recipe = $stmt->fetch();

        return $recipe ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM recipes WHERE slug = :slug AND status = 'published' LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $recipe = $stmt->fetch();

        return $recipe ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO recipes (title, slug, short_description, description, ingredients, preparation_steps, image_path, category, status, published_at, created_at, updated_at)
             VALUES (:title, :slug, :short_description, :description, :ingredients, :preparation_steps, :image_path, :category, :status, :published_at, NOW(), NOW())'
        );
        $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'ingredients' => $data['ingredients'],
            'preparation_steps' => $data['preparation_steps'],
            'image_path' => $data['image_path'],
            'category' => $data['category'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? date('Y-m-d H:i:s') : null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE recipes
             SET title = :title, slug = :slug, short_description = :short_description, description = :description,
                 ingredients = :ingredients, preparation_steps = :preparation_steps, image_path = :image_path,
                 category = :category, status = :status,
                 published_at = CASE
                    WHEN :status_publish = "published" AND published_at IS NULL THEN NOW()
                    WHEN :status_unpublish <> "published" THEN NULL
                    ELSE published_at
                 END,
                 updated_at = NOW()
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'ingredients' => $data['ingredients'],
            'preparation_steps' => $data['preparation_steps'],
            'image_path' => $data['image_path'],
            'category' => $data['category'],
            'status' => $data['status'],
            'status_publish' => $data['status'],
            'status_unpublish' => $data['status'],
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM recipes WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $suffix = 1;

        while ($this->slugExists($slug, $ignoreId)) {
            $suffix++;
            $slug = $base . '-' . $suffix;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId): bool
    {
        if ($ignoreId) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM recipes WHERE slug = :slug AND id <> :id');
            $stmt->execute(['slug' => $slug, 'id' => $ignoreId]);
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM recipes WHERE slug = :slug');
            $stmt->execute(['slug' => $slug]);
        }

        return (int) $stmt->fetchColumn() > 0;
    }

    private function publicFilters(string $query, string $category): array
    {
        $where = ["status = 'published'"];
        $params = [];

        if ($query !== '') {
            $where[] = '(title LIKE :query_title OR short_description LIKE :query_short OR ingredients LIKE :query_ingredients)';
            $params['query_title'] = '%' . $query . '%';
            $params['query_short'] = '%' . $query . '%';
            $params['query_ingredients'] = '%' . $query . '%';
        }

        if ($category !== '' && array_key_exists($category, recipe_categories())) {
            $where[] = 'category = :category';
            $params['category'] = $category;
        }

        return ['WHERE ' . implode(' AND ', $where), $params];
    }
}
