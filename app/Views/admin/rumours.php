<?php
/** @var array $rumours */
use App\Core\View; $e = fn($s) => View::e($s);
?>
<div class="panel">
  <h2>Rumour Management — signaux de surveillance (EBS)</h2>
  <table class="data-table">
    <thead><tr><th>#</th><th>Canal</th><th>Secteur</th><th>Pays</th><th>Signal</th><th>Triage</th><th>Risque</th></tr></thead>
    <tbody>
    <?php foreach ($rumours as $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><span class="badge"><?= $e($r['source_channel']) ?></span></td>
        <td><?= $e($r['sector']) ?></td>
        <td><?= $e($r['country']) ?></td>
        <td class="truncate"><?= $e(mb_substr((string)$r['raw_signal'], 0, 90)) ?></td>
        <td><span class="status status-<?= $e($r['triage_status']) ?>"><?= $e($r['triage_status']) ?></span></td>
        <td><?= $r['risk_score'] !== null ? (int)$r['risk_score'] : '—' ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rumours): ?><tr><td colspan="7" class="muted">Aucun signal capté.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
