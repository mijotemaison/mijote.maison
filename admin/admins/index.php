<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/bootstrap.php';
require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/repositories/AdminRepository.php';

require_admin();

$admins = [];
$error = null;

try {
    $admins = (new AdminRepository(db()))->all();
} catch (Throwable $exception) {
    $error = 'Base de donnees indisponible.';
}

admin_header('Administrateurs');
?>
<?php render_flash(); ?>
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white">Gestion des administrateurs</h1>
        <p class="mt-2 text-slate-400">Aucun hash de mot de passe n'est affiche.</p>
    </div>
    <a class="btn-primary" href="/admin/admins/create.php">Ajouter</a>
</div>
<?php if ($error): ?>
    <div class="panel-card p-5 text-amber-100"><?= e($error) ?></div>
<?php else: ?>
    <div class="overflow-hidden rounded-lg border border-white/10">
        <table class="w-full min-w-[720px] divide-y divide-white/10 text-left text-sm">
            <thead class="bg-white/5 text-slate-300">
                <tr><th class="px-4 py-3">Nom</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Creation</th><th class="px-4 py-3">Actions</th></tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php foreach ($admins as $admin): ?>
                    <tr class="bg-slate-950/40">
                        <td class="px-4 py-3 font-medium text-white"><?= e($admin['username']) ?></td>
                        <td class="px-4 py-3 text-slate-300"><?= e($admin['email']) ?></td>
                        <td class="px-4 py-3 text-slate-400"><?= e($admin['created_at']) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a class="btn-secondary" href="/admin/admins/edit.php?id=<?= e($admin['id']) ?>">Modifier</a>
                                <form method="post" action="/admin/admins/delete.php" onsubmit="return confirm('Supprimer cet administrateur ?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($admin['id']) ?>">
                                    <button class="btn-danger" type="submit">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php admin_footer(); ?>
