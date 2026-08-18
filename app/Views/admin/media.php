<?php
/** @var array $items @var ?string $filter */
use App\Core\View; $e = fn($s) => View::e($s);
?>
<div class="toolbar"><a class="btn btn-gold" href="/admin/media/new">+ Nouveau média</a></div>
<div class="filters">
  <?php foreach (['', 'video','comic','podcast','image'] as $t): ?>
    <a class="chip <?= ($filter ?? null) === ($t ?: null) ? 'on' : '' ?>" href="/admin/media<?= $t ? '?type='.$t : '' ?>"><?= $e($t ?: 'Tous') ?></a>
  <?php endforeach; ?>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Aperçu</th><th>Titre (FR)</th><th>Type</th><th>Statut</th><th>Date</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($items as $m): ?>
      <tr>
        <td><?php if ($m['thumbnail']): ?><img src="<?= $e($m['thumbnail']) ?>" alt="" style="height:40px;border-radius:4px"><?php else: ?>—<?php endif; ?></td>
        <td><?= $e($m['title_fr']) ?></td>
        <td><span class="badge"><?= $e($m['type']) ?></span></td>
        <td><span class="status status-<?= $e($m['status']) ?>"><?= $e($m['status']) ?></span></td>
        <td><?= $e(substr($m['created_at'], 0, 10)) ?></td>
        <td><a href="/admin/media/<?= (int)$m['id'] ?>/edit" class="btn btn-ghost sm">Éditer</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$items): ?><tr><td colspan="6" class="muted">Aucun média.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
