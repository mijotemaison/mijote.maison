<?php

declare(strict_types=1);

namespace App\Repository;

use DateTimeImmutable;
use PDO;

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

    public function eventTypes(): array
    {
        $stmt = $this->pdo->prepare('SELECT DISTINCT event_type FROM security_logs ORDER BY event_type ASC');
        $stmt->execute();

        return array_map(static fn (array $row): string => (string) $row['event_type'], $stmt->fetchAll());
    }

    public function countFiltered(array $filters = []): int
    {
        [$where, $params] = $this->filteredQueryParts($filters);
        $sql = 'SELECT COUNT(*) FROM security_logs' . $where;
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $name => $value) {
            $stmt->bindValue($name, $value);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function filtered(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        [$where, $params] = $this->filteredQueryParts($filters);
        $sql = 'SELECT * FROM security_logs' . $where . ' ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $name => $value) {
            $stmt->bindValue($name, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function deleteOlderThanDays(int $days): int
    {
        $stmt = $this->pdo->prepare('DELETE FROM security_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)');
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function countOlderThanDays(int $days): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM security_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)');
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    private function filteredQueryParts(array $filters): array
    {
        $conditions = [];
        $params = [];

        $eventType = trim((string) ($filters['event_type'] ?? ''));
        if ($eventType !== '') {
            $conditions[] = 'event_type = :event_type';
            $params[':event_type'] = $eventType;
        }

        $query = trim((string) ($filters['q'] ?? ''));
        if ($query !== '') {
            $conditions[] = '(actor_email LIKE :query_actor OR ip_address LIKE :query_ip OR user_agent LIKE :query_agent OR details LIKE :query_details)';
            $likeQuery = '%' . $query . '%';
            $params[':query_actor'] = $likeQuery;
            $params[':query_ip'] = $likeQuery;
            $params[':query_agent'] = $likeQuery;
            $params[':query_details'] = $likeQuery;
        }

        $dateFrom = $this->normalizedDate((string) ($filters['date_from'] ?? ''));
        if ($dateFrom !== '') {
            $conditions[] = 'created_at >= :date_from';
            $params[':date_from'] = $dateFrom . ' 00:00:00';
        }

        $dateTo = $this->normalizedDate((string) ($filters['date_to'] ?? ''));
        if ($dateTo !== '') {
            $conditions[] = 'created_at <= :date_to';
            $params[':date_to'] = $dateTo . ' 23:59:59';
        }

        return [
            $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '',
            $params,
        ];
    }

    private function normalizedDate(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if (!$date || $date->format('Y-m-d') !== $value) {
            return '';
        }

        return $value;
    }
}
