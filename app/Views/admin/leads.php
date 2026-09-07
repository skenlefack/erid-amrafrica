<?php
/** @var array $leads @var ?string $filter @var ?string $search @var int $page @var int $totalPages @var int $total */
use App\Core\View; $e = fn($s) => View::e($s);
$qs = fn(array $o) => '?' . http_build_query(array_filter(array_merge(['status' => $filter, 'q' => $search], $o)));
?>
<div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
  <div style="display:flex;gap:8px">
    <a class="btn btn-gold" href="/admin/leads/new">+ Nouveau lead</a>
    <a class="btn btn-ghost sm" href="/admin/leads/export">📥 Export CSV</a>
  </div>
  <form method="get" action="/admin/leads" style="display:flex;gap:6px">
    <?php if ($filter): ?><input type="hidden" name="status" value="<?= $e($filter) ?>"><?php endif; ?>
    <input type="text" name="q" value="<?= $e($search ?? '') ?>" placeholder="Rechercher org, contact, projet..." style="width:240px;padding:6px 10px;border:1px solid var(--border);border-radius:4px;font-size:13px">
    <button class="btn btn-ghost sm" type="submit">🔍</button>
  </form>
</div>
<div class="filters">
  <?php foreach (['', 'new','reviewing','scoping','quoted','won','lost'] as $st): ?>
    <a class="chip <?= $filter === ($st ?: null) ? 'on' : '' ?>" href="/admin/leads<?= $st ? '?status='.$st : '' ?><?= $search ? '&q='.$e($search) : '' ?>"><?= $e($st ?: 'Tous') ?></a>
  <?php endforeach; ?>
  <span class="muted" style="margin-left:auto;font-size:12px"><?= $total ?? count($leads) ?> lead(s)</span>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>#</th><th>Organisation</th><th>Contact</th><th>Type</th><th>Statut</th><th>Valeur</th><th>Date</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($leads as $l): ?>
      <tr>
        <td><?= (int)$l['id'] ?></td>
        <td><strong><?= $e($l['organisation']) ?></strong></td>
        <td><?= $e($l['lead_name']) ?><br><small class="muted"><?= $e($l['email']) ?></small></td>
        <td><span class="badge"><?= $e($l['intake_type']) ?></span></td>
        <td><span class="status status-<?= $e($l['status']) ?>"><?= $e($l['status']) ?></span></td>
        <td><?= $l['est_value_usd'] ? '$'.number_format((float)$l['est_value_usd']) : '—' ?></td>
        <td><?= $e(substr((string)$l['created_at'], 0, 10)) ?></td>
        <td><a class="link" href="/admin/leads/<?= (int)$l['id'] ?>">Ouvrir →</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$leads): ?><tr><td colspan="8" class="muted">Aucun lead trouvé.</td></tr><?php endif; ?>
    </tbody>
  </table>
  <?php if ($totalPages > 1): ?>
  <div style="display:flex;gap:8px;justify-content:center;margin-top:20px">
    <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="/admin/leads<?= $qs(['page' => $page - 1]) ?>">← Préc.</a><?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="/admin/leads<?= $qs(['page' => $i]) ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="/admin/leads<?= $qs(['page' => $page + 1]) ?>">Suiv. →</a><?php endif; ?>
  </div>
  <?php endif; ?>
</div>
