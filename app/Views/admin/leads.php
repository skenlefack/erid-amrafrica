<?php
/** @var array $leads @var ?string $filter */
use App\Core\View; $e = fn($s) => View::e($s);
?>
<div class="filters">
  <?php foreach (['', 'new','reviewing','scoping','quoted','won','lost'] as $st): ?>
    <a class="chip <?= $filter === ($st ?: null) ? 'on' : '' ?>" href="/admin/leads<?= $st ? '?status='.$st : '' ?>"><?= $e($st ?: 'Tous') ?></a>
  <?php endforeach; ?>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>#</th><th>Organisation</th><th>Contact</th><th>Type</th><th>Statut</th><th>Valeur</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($leads as $l): ?>
      <tr>
        <td><?= (int)$l['id'] ?></td>
        <td><?= $e($l['organisation']) ?></td>
        <td><?= $e($l['lead_name']) ?><br><small class="muted"><?= $e($l['email']) ?></small></td>
        <td><span class="badge"><?= $e($l['intake_type']) ?></span></td>
        <td><span class="status status-<?= $e($l['status']) ?>"><?= $e($l['status']) ?></span></td>
        <td><?= $l['est_value_usd'] ? '$'.number_format((float)$l['est_value_usd']) : '—' ?></td>
        <td><a class="link" href="/admin/leads/<?= (int)$l['id'] ?>">Ouvrir →</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$leads): ?><tr><td colspan="7" class="muted">Aucun lead.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
