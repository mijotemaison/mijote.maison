<?php

declare(strict_types=1);

namespace App\Model;

use App\Repository\AdminRepository;
use PDO;

final class Admin
{
    private AdminRepository $repository;

    public function __construct(private PDO $pdo)
    {
        $this->repository = new AdminRepository($pdo);
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
