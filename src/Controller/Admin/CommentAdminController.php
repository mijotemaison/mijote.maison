<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Repository\RecipeInteractionRepository;
use PDO;
use Throwable;

final class CommentAdminController extends AbstractController
{
    public function index(): void
    {
        \require_admin();

        $pdo = \db();
        $repo = new RecipeInteractionRepository($pdo);

        if (\is_post()) {
            $this->handleAction($pdo, $repo);
        }

        $comments = [];
        $error = null;

        try {
            $comments = $repo->allComments();
        } catch (Throwable) {
            $error = 'Base de donnees indisponible.';
        }

        \admin_header('Commentaires');
        $this->render('admin/comments', compact('comments', 'error'));
        \admin_footer();
    }

    private function handleAction(PDO $pdo, RecipeInteractionRepository $repo): void
    {
        \require_valid_csrf();
        $id = (int) ($_POST['id'] ?? 0);
        $action = (string) ($_POST['action'] ?? '');

        try {
            if ($action === 'approve') {
                $repo->updateCommentStatus($id, 'approved');
                \record_security_event($pdo, 'comment_approved', 'Commentaire #' . $id . ' approuve.', \current_admin_email());
                \flash('success', 'Commentaire approuve.');
            } elseif ($action === 'reject') {
                $repo->updateCommentStatus($id, 'rejected');
                \record_security_event($pdo, 'comment_rejected', 'Commentaire #' . $id . ' refuse.', \current_admin_email());
                \flash('success', 'Commentaire refuse.');
            } elseif ($action === 'delete') {
                $repo->deleteComment($id);
                \record_security_event($pdo, 'comment_deleted', 'Commentaire #' . $id . ' supprime.', \current_admin_email());
                \flash('success', 'Commentaire supprime.');
            } else {
                \flash('error', 'Action inconnue.');
            }
        } catch (Throwable) {
            \flash('error', 'Action impossible sur ce commentaire.');
        }

        \redirect('/admin/commentaires');
    }
}
