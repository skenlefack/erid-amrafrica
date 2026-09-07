<?php
/** @var array $users @var ?string $filter @var ?string $search @var int $page @var int $totalPages */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$qs = fn(array $o) => '?' . http_build_query(array_filter(array_merge(['role' => $filter, 'q' => $search], $o)));
$roleLabels = ['superadmin' => 'Super Admin', 'editor' => 'Éditeur', 'consultant' => 'Consultant', 'analyst' => 'Analyste'];
?>
<div class="panel">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:12px">
    <h2>Gestion des utilisateurs</h2>
    <div style="display:flex;gap:8px">
      <form method="get" action="/admin/users" style="display:flex;gap:6px">
        <?php if ($filter): ?><input type="hidden" name="role" value="<?= $e($filter) ?>"><?php endif; ?>
        <input type="text" name="q" value="<?= $e($search ?? '') ?>" placeholder="Rechercher..." style="width:180px;padding:6px 10px;border:1px solid var(--border);border-radius:4px;font-size:13px">
        <button class="btn btn-ghost sm" type="submit">🔍</button>
      </form>
      <a class="btn btn-gold sm" href="/admin/users/new">+ Nouvel utilisateur</a>
    </div>
  </div>

  <div class="filters" style="margin-bottom:16px">
    <a class="chip <?= !$filter ? 'on' : '' ?>" href="/admin/users<?= $search ? '?q='.$e($search) : '' ?>">Tous</a>
    <?php foreach ($roleLabels as $k => $label): ?>
      <a class="chip <?= $filter === $k ? 'on' : '' ?>" href="/admin/users?role=<?= $k ?><?= $search ? '&q='.$e($search) : '' ?>"><?= $e($label) ?></a>
    <?php endforeach; ?>
  </div>

  <table class="data-table">
    <thead><tr><th>#</th><th>Nom</th><th>E-mail</th><th>Rôle</th><th>Langue</th><th>Statut</th><th>Dernière connexion</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($users as $u): ?>
      <tr<?= !$u['is_active'] ? ' style="opacity:.5"' : '' ?>>
        <td><?= (int)$u['id'] ?></td>
        <td><strong><?= $e($u['full_name']) ?></strong></td>
        <td><?= $e($u['email']) ?></td>
        <td><span class="badge"><?= $e($roleLabels[$u['role']] ?? $u['role']) ?></span></td>
        <td><?= $e(strtoupper($u['locale'])) ?></td>
        <td>
          <?php if ($u['is_active']): ?>
            <span class="status status-published">Actif</span>
          <?php else: ?>
            <span class="status status-draft">Inactif</span>
          <?php endif; ?>
        </td>
        <td><?= $u['last_login_at'] ? $e(date('d/m/Y H:i', strtotime($u['last_login_at']))) : '<span class="muted">Jamais</span>' ?></td>
        <td style="display:flex;gap:6px">
          <a class="link" href="/admin/users/<?= (int)$u['id'] ?>/edit">Éditer</a>
          <?php if ((int)$u['id'] !== ($_SESSION['uid'] ?? 0)): ?>
          <form method="post" action="/admin/users/<?= (int)$u['id'] ?>/toggle" style="display:inline" onsubmit="return confirm('<?= $u['is_active'] ? 'Désactiver' : 'Réactiver' ?> cet utilisateur ?')">
            <?= Csrf::field() ?>
            <button type="submit" class="link" style="background:none;border:none;cursor:pointer;color:<?= $u['is_active'] ? '#c62828' : '#2e7d32' ?>;font-size:13px;padding:0">
              <?= $u['is_active'] ? 'Désactiver' : 'Réactiver' ?>
            </button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$users): ?><tr><td colspan="8" class="muted">Aucun utilisateur trouvé.</td></tr><?php endif; ?>
    </tbody>
  </table>

  <?php if ($totalPages > 1): ?>
  <div style="display:flex;gap:8px;justify-content:center;margin-top:20px">
    <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="/admin/users<?= $qs(['page' => $page - 1]) ?>">← Préc.</a><?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="/admin/users<?= $qs(['page' => $i]) ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="/admin/users<?= $qs(['page' => $page + 1]) ?>">Suiv. →</a><?php endif; ?>
  </div>
  <?php endif; ?>
</div>
