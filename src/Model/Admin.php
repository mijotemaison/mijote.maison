<?php

declare(strict_types=1);

namespace App\Model;

use PDO;

require_once BASE_PATH . '/app/repositories/AdminRepository.php';

final class Admin
{
    private \AdminRepository $repository;

    public function __construct(private PDO $pdo)
    {
        $this->repository = new \AdminRepository($pdo);
    }

    public function findByEmail(string $email): ?array
    {
        return $this->repository->findByEmail($email);
    }

    public function updatePasswordHash(int $id, string $passwordHash): void
    {
        $this->repository->updatePasswordHash($id, $passwordHash);
    }
}
