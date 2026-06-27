<?php
/** @var array $articles */
use App\Core\View; $e = fn($s) => View::e($s);
?>
<div class="toolbar"><a class="btn btn-gold" href="/admin/articles/new">+ Nouvel article</a></div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Titre (FR)</th><th>Canal</th><th>Statut</th><th>Vues</th><th>Date</th></tr></thead>
    <tbody>
    <?php foreach ($articles as $a): ?>
      <tr>
        <td><?= $e($a['title_fr']) ?></td>
        <td><span class="badge"><?= $e($a['cat_slug']) ?></span></td>
        <td><span class="status status-<?= $e($a['status']) ?>"><?= $e($a['status']) ?></span></td>
        <td><?= (int)$a['views'] ?></td>
        <td><?= $e(substr((string)($a['published_at'] ?? $a['created_at']), 0, 10)) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$articles): ?><tr><td colspan="5" class="muted">Aucun article.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
