<?php render_flash(); ?>
<div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="text-sm font-medium text-cyan-200">Connecte : <?= e(current_admin_email()) ?></p>
        <h1 class="mt-2 text-3xl font-bold text-white">Dashboard securite</h1>
    </div>
    <a class="btn-primary" href="/admin/recettes/creer">Nouvelle recette</a>
</div>

<?php if ($error): ?>
    <div class="panel-card p-5 text-amber-100"><?= e($error) ?></div>
<?php else: ?>
    <div class="grid gap-5 md:grid-cols-4">
        <div class="panel-card p-5"><p class="text-sm text-slate-400">Recettes</p><p class="mt-2 text-4xl font-bold text-white"><?= e($recipeCount) ?></p></div>
        <div class="panel-card p-5"><p class="text-sm text-slate-400">Administrateurs</p><p class="mt-2 text-4xl font-bold text-white"><?= e($adminCount) ?></p></div>
        <div class="panel-card p-5"><p class="text-sm text-slate-400">Commentaires en attente</p><p class="mt-2 text-4xl font-bold text-white"><?= e($pendingCommentCount) ?></p></div>
        <div class="panel-card p-5"><p class="text-sm text-slate-400">Protections</p><p class="mt-2 text-lg font-semibold text-cyan-100">CSRF · CSP · PDO · Upload</p></div>
    </div>
    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="panel-card p-5">
            <h2 class="text-xl font-semibold text-white">Dernieres recettes</h2>
            <div class="mt-4 space-y-3">
                <?php foreach ($latestRecipes as $recipe): ?>
                    <div class="rounded-lg border border-white/10 bg-slate-950/50 p-3">
                        <p class="font-medium text-white"><?= e($recipe['title']) ?></p>
                        <p class="text-sm text-slate-400"><?= e($recipe['created_at']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="panel-card p-5">
            <h2 class="text-xl font-semibold text-white">Tentatives echouees recentes</h2>
            <div class="mt-4 space-y-3">
                <?php if (!$latestFailures): ?>
                    <p class="text-sm text-slate-400">Aucune tentative suspecte recente.</p>
                <?php endif; ?>
                <?php foreach ($latestFailures as $attempt): ?>
                    <div class="rounded-lg border border-white/10 bg-slate-950/50 p-3 text-sm">
                        <p class="text-white"><?= e($attempt['email'] ?: 'email vide') ?> · <?= e($attempt['ip_address']) ?></p>
                        <p class="text-slate-400"><?= e($attempt['created_at']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section class="panel-card p-5 lg:col-span-2">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-white">Journal securite recent</h2>
                <a class="text-sm font-semibold text-cyan-200 hover:text-white" href="/admin/journal-securite">Voir tout le journal</a>
            </div>
            <div class="mt-4 grid gap-3 lg:grid-cols-2">
                <?php if (!$latestSecurityLogs): ?>
                    <p class="text-sm text-slate-400">Aucun evenement securite enregistre.</p>
                <?php endif; ?>
                <?php foreach ($latestSecurityLogs as $log): ?>
                    <div class="rounded-lg border border-white/10 bg-slate-950/50 p-3 text-sm">
                        <p class="font-semibold text-white"><?= e($log['event_type']) ?> · <?= e($log['actor_email'] ?: 'visiteur') ?></p>
                        <p class="mt-1 text-slate-400"><?= e($log['details'] ?? '') ?></p>
                        <p class="mt-2 text-xs text-slate-500"><?= e($log['ip_address']) ?> · <?= e($log['created_at']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
<?php endif; ?>
