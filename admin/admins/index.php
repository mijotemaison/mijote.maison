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
$currentAdminId = current_admin_id();
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
    <div class="mb-4 flex flex-wrap items-center gap-3 rounded-lg border border-white/10 bg-slate-950/40 p-3" data-table-toolbar="admins">
        <label class="relative flex-1 min-w-[220px]">
            <span class="sr-only">Rechercher un administrateur</span>
            <input class="field" type="search" placeholder="Rechercher (nom, email)..." data-table-search>
        </label>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
            <button type="button" class="btn-secondary !px-4 !py-2 !text-xs" data-table-prev aria-label="Page précédente">‹ Précédent</button>
            <span data-table-indicator class="tabular px-2 text-slate-300">—</span>
            <button type="button" class="btn-secondary !px-4 !py-2 !text-xs" data-table-next aria-label="Page suivante">Suivant ›</button>
        </div>
    </div>
    <div class="overflow-hidden rounded-lg border border-white/10">
        <table class="w-full min-w-[720px] divide-y divide-white/10 text-left text-sm" data-table="admins" data-page-size="10">
            <thead class="bg-white/5 text-slate-300">
                <tr><th class="px-4 py-3">Nom</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Creation</th><th class="px-4 py-3">Actions</th></tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                <?php foreach ($admins as $admin): ?>
                    <tr class="bg-slate-950/40" data-search="<?= e($admin['username'] . ' ' . $admin['email']) ?>">
                        <td class="px-4 py-3 font-medium text-white"><?= e($admin['username']) ?></td>
                        <td class="px-4 py-3 text-slate-300"><?= e($admin['email']) ?></td>
                        <td class="px-4 py-3 text-slate-400"><?= e($admin['created_at']) ?></td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <a class="btn-secondary" href="/admin/admins/edit.php?id=<?= e($admin['id']) ?>">Modifier</a>
                                <?php if ((int) $admin['id'] === $currentAdminId): ?>
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1.5 text-xs font-extrabold uppercase tracking-wide text-emerald-200" title="Vous ne pouvez pas supprimer votre propre compte.">● Vous</span>
                                <?php else: ?>
                                    <form method="post" action="/admin/admins/delete.php" data-confirm="Êtes-vous sûr de vouloir supprimer définitivement l'administrateur « <?= e($admin['username']) ?> » (<?= e($admin['email']) ?>) ? Cette action est irréversible.">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($admin['id']) ?>">
                                        <button class="btn-danger" type="submit">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="hidden p-6 text-center text-sm text-slate-400" data-table-empty>Aucun administrateur ne correspond à votre recherche.</div>
    </div>
<?php endif; ?>
<?php admin_footer(); ?>
