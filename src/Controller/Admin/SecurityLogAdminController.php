<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Repository\LoginAttemptRepository;
use App\Repository\SecurityLogRepository;
use PDO;
use Throwable;

final class SecurityLogAdminController extends AbstractController
{
    public function index(): void
    {
        \require_admin();

        $pdo = \db();
        $securityLogRepo = new SecurityLogRepository($pdo);
        $loginAttemptRepo = new LoginAttemptRepository($pdo);

        if (\is_post()) {
            $this->handleCleanup($pdo, $securityLogRepo, $loginAttemptRepo);
        }

        $filters = [
            'event_type' => trim((string) ($_GET['event_type'] ?? '')),
            'q' => trim((string) ($_GET['q'] ?? '')),
            'date_from' => trim((string) ($_GET['date_from'] ?? '')),
            'date_to' => trim((string) ($_GET['date_to'] ?? '')),
        ];
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $logs = [];
        $eventTypes = [];
        $totalLogs = 0;
        $totalPages = 1;
        $error = null;

        try {
            $eventTypes = $securityLogRepo->eventTypes();
            $totalLogs = $securityLogRepo->countFiltered($filters);
            $totalPages = max(1, (int) ceil($totalLogs / $perPage));
            if ($page > $totalPages) {
                $page = $totalPages;
                $offset = ($page - 1) * $perPage;
            }
            $logs = $securityLogRepo->filtered($filters, $perPage, $offset);
        } catch (Throwable) {
            $error = 'Base de donnees indisponible.';
        }

        $queryParams = array_filter([
            'event_type' => $filters['event_type'],
            'q' => $filters['q'],
            'date_from' => $filters['date_from'],
            'date_to' => $filters['date_to'],
        ], static fn (string $value): bool => $value !== '');

        if (!$error && (string) ($_GET['export'] ?? '') === 'csv') {
            $this->exportCsv($securityLogRepo, $filters);
        }

        \admin_header('Journal securite');
        $this->render('admin/security_logs', compact(
            'filters',
            'logs',
            'eventTypes',
            'totalLogs',
            'totalPages',
            'page',
            'queryParams',
            'error'
        ));
        \admin_footer();
    }

    private function handleCleanup(PDO $pdo, SecurityLogRepository $securityLogRepo, LoginAttemptRepository $loginAttemptRepo): void
    {
        \require_valid_csrf();
        $action = (string) ($_POST['action'] ?? '');

        if ($action === 'cleanup') {
            $days = max(30, min(365, (int) ($_POST['days'] ?? 90)));
            $deletedLogs = $securityLogRepo->deleteOlderThanDays($days);
            $deletedAttempts = $loginAttemptRepo->deleteOlderThanDays($days);
            \record_security_event(
                $pdo,
                'security_cleanup',
                'Nettoyage des evenements de plus de ' . $days . ' jours : ' . $deletedLogs . ' logs securite et ' . $deletedAttempts . ' tentatives login supprimes.',
                \current_admin_email()
            );
            \flash('success', 'Nettoyage termine : ' . $deletedLogs . ' logs securite et ' . $deletedAttempts . ' tentatives login supprimes.');
        } else {
            \flash('error', 'Action inconnue.');
        }

        \redirect('/admin/journal-securite');
    }

    private function exportCsv(SecurityLogRepository $securityLogRepo, array $filters): void
    {
        $exportLogs = $securityLogRepo->filtered($filters, 5000, 0);
        $filename = 'journal-securite-' . date('Ymd-His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        if ($output !== false) {
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['id', 'event_type', 'actor_email', 'ip_address', 'user_agent', 'details', 'created_at'], ',', '"', '', "\n");
            foreach ($exportLogs as $log) {
                fputcsv($output, [
                    $log['id'],
                    $log['event_type'],
                    $log['actor_email'],
                    $log['ip_address'],
                    $log['user_agent'],
                    $log['details'],
                    $log['created_at'],
                ], ',', '"', '', "\n");
            }
            fclose($output);
        }
        exit;
    }
}
