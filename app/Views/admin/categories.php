<?php
/** @var array $categories */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
  <p class="muted" style="margin:0">Taxonomie des articles — catégories administrables dynamiquement.</p>
  <a class="btn btn-gold sm" href="/admin/categories/new">+ Nouvelle catégorie</a>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Ordre</th><th>Slug</th><th>Nom (FR)</th><th>Name (EN)</th><th>Couleur</th><th>Articles</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($categories as $c): ?>
      <tr>
        <td><?= (int)$c['sort_order'] ?></td>
        <td><code><?= $e($c['slug']) ?></code></td>
        <td><strong><?= $e($c['name_fr']) ?></strong></td>
        <td><?= $e($c['name_en']) ?></td>
        <td><span style="display:inline-block;width:24px;height:24px;border-radius:4px;background:<?= $e($c['accent_color']) ?>;vertical-align:middle"></span> <?= $e($c['accent_color']) ?></td>
        <td><?= (int)$c['article_count'] ?></td>
        <td style="display:flex;gap:6px">
          <a class="btn btn-ghost sm" href="/admin/categories/<?= (int)$c['id'] ?>/edit">Éditer</a>
          <?php if ((int)$c['article_count'] === 0): ?>
          <form method="post" action="/admin/categories/<?= (int)$c['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
            <?= Csrf::field() ?>
            <button type="submit" class="btn btn-ghost sm" style="color:#c62828">Suppr.</button>
          </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$categories): ?><tr><td colspan="7" class="muted">Aucune catégorie configurée.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
