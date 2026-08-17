<?php
/** @var array $rumours @var ?string $filter */
use App\Core\View; $e = fn($s) => View::e($s);
?>
<div class="filters">
  <?php foreach (['', 'new','triaged','escalated','dismissed'] as $st): ?>
    <a class="chip <?= ($filter ?? null) === ($st ?: null) ? 'on' : '' ?>" href="/admin/rumours<?= $st ? '?status='.$st : '' ?>"><?= $e($st ?: 'Tous') ?></a>
  <?php endforeach; ?>
</div>
<div class="panel">
  <h2>Rumour Management &mdash; signaux de surveillance (EBS)</h2>
  <table class="data-table">
    <thead><tr><th>#</th><th>Canal</th><th>Secteur</th><th>Pays</th><th>Signal</th><th>Triage</th><th>Risque</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($rumours as $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><span class="badge"><?= $e($r['source_channel']) ?></span></td>
        <td><?= $e($r['sector']) ?></td>
        <td><?= $e($r['country']) ?></td>
        <td class="truncate"><?= $e(mb_substr((string)$r['raw_signal'], 0, 90)) ?></td>
        <td><span class="status status-<?= $e($r['triage_status']) ?>"><?= $e($r['triage_status']) ?></span></td>
        <td><?= $r['risk_score'] !== null ? (int)$r['risk_score'] : '&mdash;' ?></td>
        <td><a class="link" href="/admin/rumours/<?= (int)$r['id'] ?>">Ouvrir &rarr;</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rumours): ?><tr><td colspan="8" class="muted">Aucun signal capt&eacute;.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
