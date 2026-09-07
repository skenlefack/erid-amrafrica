<?php
/** @var array $pages @var int $page @var int $totalPages */
use App\Core\View; $e = fn($s) => View::e($s);
?>
<div class="toolbar"><a class="btn btn-gold" href="/admin/pages/new">+ Nouvelle page</a></div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Slug</th><th>Titre (FR)</th><th>Statut</th><th>Dernière modification</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($pages as $p): ?>
      <tr>
        <td><code>/page/<?= $e($p['slug']) ?></code></td>
        <td><?= $e($p['title_fr']) ?></td>
        <td><span class="status status-<?= $e($p['status']) ?>"><?= $e($p['status']) ?></span></td>
        <td><?= $e(substr($p['updated_at'], 0, 16)) ?></td>
        <td style="display:flex;gap:6px">
          <a href="/admin/pages/<?= (int)$p['id'] ?>/edit" class="btn btn-ghost sm">Éditer</a>
          <a href="/page/<?= $e($p['slug']) ?>" target="_blank" class="btn btn-ghost sm">👁</a>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$pages): ?><tr><td colspan="5" class="muted">Aucune page.</td></tr><?php endif; ?>
    </tbody>
  </table>
  <?php if ($totalPages > 1): ?>
  <div style="display:flex;gap:8px;justify-content:center;margin-top:20px">
    <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="/admin/pages?page=<?= $page - 1 ?>">← Préc.</a><?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="/admin/pages?page=<?= $i ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="/admin/pages?page=<?= $page + 1 ?>">Suiv. →</a><?php endif; ?>
  </div>
  <?php endif; ?>
</div>
