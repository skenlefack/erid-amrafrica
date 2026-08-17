<?php
/** @var array $pages */
use App\Core\View; $e = fn($s) => View::e($s);
?>
<div class="toolbar"><a class="btn btn-gold" href="/admin/pages/new">+ Nouvelle page</a></div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Slug</th><th>Titre (FR)</th><th>Statut</th><th>Derni&egrave;re modification</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($pages as $p): ?>
      <tr>
        <td><code>/page/<?= $e($p['slug']) ?></code></td>
        <td><?= $e($p['title_fr']) ?></td>
        <td><span class="status status-<?= $e($p['status']) ?>"><?= $e($p['status']) ?></span></td>
        <td><?= $e(substr($p['updated_at'], 0, 16)) ?></td>
        <td><a href="/admin/pages/<?= (int)$p['id'] ?>/edit" class="btn btn-ghost sm">&Eacute;diter</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$pages): ?><tr><td colspan="5" class="muted">Aucune page.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
