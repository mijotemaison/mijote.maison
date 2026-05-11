<?php

declare(strict_types=1);

final class SecurityLogRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(array $data): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO security_logs (event_type, actor_email, ip_address, user_agent, details, created_at)
             VALUES (:event_type, :actor_email, :ip_address, :user_agent, :details, NOW())'
        );
        $stmt->execute([
            'event_type' => $data['event_type'],
            'actor_email' => $data['actor_email'] ?? null,
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'] ?? null,
            'details' => $data['details'] ?? null,
        ]);
    }

    public function latest(int $limit = 8): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM security_logs ORDER BY created_at DESC, id DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
