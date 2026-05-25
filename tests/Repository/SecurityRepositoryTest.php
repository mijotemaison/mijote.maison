<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Repository\LoginAttemptRepository;
use App\Repository\SecurityLogRepository;

final class SecurityRepositoryTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = db();
        $this->pdo->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }

    public function testSecurityLogFilteringByTypeQueryAndDate(): void
    {
        $repo = new SecurityLogRepository($this->pdo);
        $repo->create([
            'event_type' => 'phpunit_event',
            'actor_email' => 'phpunit@example.com',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'phpunit',
            'details' => 'Evenement de test PHPUnit',
        ]);

        $today = date('Y-m-d');
        $logs = $repo->filtered([
            'event_type' => 'phpunit_event',
            'q' => 'phpunit@example.com',
            'date_from' => $today,
            'date_to' => $today,
        ], 10, 0);

        self::assertNotEmpty($logs);
        self::assertSame('phpunit_event', $logs[0]['event_type']);
        self::assertSame('phpunit@example.com', $logs[0]['actor_email']);
    }

    public function testSecurityLogCountsAndDeletesOldRows(): void
    {
        $this->pdo->exec(
            "INSERT INTO security_logs (event_type, actor_email, ip_address, user_agent, details, created_at)
             VALUES ('phpunit_old', 'phpunit@example.com', '127.0.0.1', 'phpunit', 'Ancien log', DATE_SUB(NOW(), INTERVAL 120 DAY))"
        );

        $repo = new SecurityLogRepository($this->pdo);

        self::assertGreaterThanOrEqual(1, $repo->countOlderThanDays(90));
        self::assertGreaterThanOrEqual(1, $repo->deleteOlderThanDays(90));
    }

    public function testLoginAttemptCountsAndDeletesOldRows(): void
    {
        $this->pdo->exec(
            "INSERT INTO login_attempts (email, ip_address, user_agent, success, created_at)
             VALUES ('phpunit-old@example.com', '127.0.0.1', 'phpunit', 0, DATE_SUB(NOW(), INTERVAL 120 DAY))"
        );

        $repo = new LoginAttemptRepository($this->pdo);

        self::assertGreaterThanOrEqual(1, $repo->countOlderThanDays(90));
        self::assertGreaterThanOrEqual(1, $repo->deleteOlderThanDays(90));
    }
}
