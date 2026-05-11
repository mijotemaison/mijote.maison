<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    echo "Ce script doit etre lance en ligne de commande.\n";
    exit(1);
}

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/LoginAttemptRepository.php';
require_once BASE_PATH . '/app/repositories/SecurityLogRepository.php';

$options = getopt('', ['days::', 'dry-run']);
$envDays = (int) (env_value('LOG_RETENTION_DAYS', '90') ?? '90');
$days = (int) ($options['days'] ?? $envDays);
$days = max(7, min(3650, $days));
$dryRun = array_key_exists('dry-run', $options);

try {
    $pdo = db();
    $loginAttemptRepo = new LoginAttemptRepository($pdo);
    $securityLogRepo = new SecurityLogRepository($pdo);

    $oldAttempts = $loginAttemptRepo->countOlderThanDays($days);
    $oldLogs = $securityLogRepo->countOlderThanDays($days);

    if ($dryRun) {
        echo "[dry-run] Retention: {$days} jours\n";
        echo "[dry-run] Tentatives login supprimables: {$oldAttempts}\n";
        echo "[dry-run] Logs securite supprimables: {$oldLogs}\n";
        exit(0);
    }

    $deletedAttempts = $loginAttemptRepo->deleteOlderThanDays($days);
    $deletedLogs = $securityLogRepo->deleteOlderThanDays($days);

    $securityLogRepo->create([
        'event_type' => 'maintenance_cleanup',
        'actor_email' => 'system@local',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'cli-maintenance',
        'details' => 'Nettoyage automatique retention ' . $days . ' jours : ' . $deletedLogs . ' logs securite et ' . $deletedAttempts . ' tentatives login supprimes.',
    ]);

    echo "Retention: {$days} jours\n";
    echo "Tentatives login supprimees: {$deletedAttempts}\n";
    echo "Logs securite supprimes: {$deletedLogs}\n";
    echo "Evenement maintenance_cleanup journalise.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, 'Erreur nettoyage securite: ' . $exception->getMessage() . "\n");
    exit(1);
}
