<?php
/** @var array $rumours @var ?string $filter @var int $page @var int $totalPages */
use App\Core\View; $e = fn($s) => View::e($s);
$qs = fn(array $o) => '?' . http_build_query(array_filter(array_merge(['status' => $filter], $o)));
?>
<div class="filters">
  <?php foreach (['', 'new','triaged','escalated','dismissed'] as $st): ?>
    <a class="chip <?= ($filter ?? null) === ($st ?: null) ? 'on' : '' ?>" href="/admin/rumours<?= $st ? '?status='.$st : '' ?>"><?= $e($st ?: 'Tous') ?></a>
  <?php endforeach; ?>
</div>
<div class="panel">
  <h2>Rumour Management — signaux de surveillance (EBS)</h2>
  <table class="data-table">
    <thead><tr><th>#</th><th>Canal</th><th>Pays</th><th>Région</th><th>Lieu</th><th>Signal</th><th>Triage</th><th>Risque</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($rumours as $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><span class="badge"><?= $e($r['source_channel']) ?></span></td>
        <td><?= $e($r['country'] ?: '—') ?></td>
        <td><?= $e($r['region'] ?? '—') ?></td>
        <td><?= $e($r['setting_type'] ?? '—') ?></td>
        <td class="truncate"><?= $e(mb_substr((string)$r['raw_signal'], 0, 80)) ?></td>
        <td><span class="status status-<?= $e($r['triage_status']) ?>"><?= $e($r['triage_status']) ?></span></td>
        <td><?= $r['risk_score'] !== null ? (int)$r['risk_score'] : '—' ?></td>
        <td><a class="link" href="/admin/rumours/<?= (int)$r['id'] ?>">Ouvrir →</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rumours): ?><tr><td colspan="9" class="muted">Aucun signal capté.</td></tr><?php endif; ?>
    </tbody>
  </table>
  <?php if ($totalPages > 1): ?>
  <div style="display:flex;gap:8px;justify-content:center;margin-top:20px">
    <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="/admin/rumours<?= $qs(['page' => $page - 1]) ?>">← Préc.</a><?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="/admin/rumours<?= $qs(['page' => $i]) ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="/admin/rumours<?= $qs(['page' => $page + 1]) ?>">Suiv. →</a><?php endif; ?>
  </div>
  <?php endif; ?>
</div>
