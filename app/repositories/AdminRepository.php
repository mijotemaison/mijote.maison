<?php

declare(strict_types=1);

final class AdminRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->prepare('SELECT id, username, email, created_at, updated_at FROM admins ORDER BY created_at DESC, id DESC');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function count(): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM admins');
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, username, email, created_at, updated_at FROM admins WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $admin = $stmt->fetch();

        return $admin ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM admins WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        return $admin ?: null;
    }

    public function emailExists(string $email, ?int $ignoreId = null): bool
    {
        if ($ignoreId) {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM admins WHERE email = :email AND id <> :id');
            $stmt->execute(['email' => $email, 'id' => $ignoreId]);
        } else {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM admins WHERE email = :email');
            $stmt->execute(['email' => $email]);
        }

        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO admins (username, email, password_hash, created_at, updated_at)
             VALUES (:username, :email, :password_hash, NOW(), NOW())'
        );
        $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        if (!empty($data['password_hash'])) {
            $stmt = $this->pdo->prepare(
                'UPDATE admins SET username = :username, email = :email, password_hash = :password_hash, updated_at = NOW() WHERE id = :id'
            );
            $stmt->execute([
                'id' => $id,
                'username' => $data['username'],
                'email' => $data['email'],
                'password_hash' => $data['password_hash'],
            ]);
            return;
        }

        $stmt = $this->pdo->prepare('UPDATE admins SET username = :username, email = :email, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'username' => $data['username'],
            'email' => $data['email'],
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM admins WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
