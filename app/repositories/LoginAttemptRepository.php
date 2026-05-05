<?php

declare(strict_types=1);

final class LoginAttemptRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO login_attempts (email, ip_address, user_agent, success, created_at)
             VALUES (:email, :ip_address, :user_agent, :success, NOW())'
        );
        $stmt->execute([
            'email' => $data['email'],
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'],
            'success' => (int) $data['success'],
        ]);
    }

    public function countRecentFailures(string $email, string $ip, int $minutes): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM login_attempts
             WHERE success = 0
               AND (email = :email OR ip_address = :ip_address)
               AND created_at >= DATE_SUB(NOW(), INTERVAL :minutes MINUTE)'
        );
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':ip_address', $ip);
        $stmt->bindValue(':minutes', $minutes, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function latestFailures(int $limit = 8): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT email, ip_address, user_agent, created_at
             FROM login_attempts
             WHERE success = 0
             ORDER BY created_at DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
