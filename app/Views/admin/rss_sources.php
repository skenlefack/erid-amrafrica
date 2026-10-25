<?php
/** @var array $sources */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
  <p class="muted" style="margin:0">Flux RSS surveillés — les signaux sont importés automatiquement toutes les 6h.</p>
  <a class="btn btn-gold sm" href="/admin/rss-sources/new">+ Nouvelle source</a>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Nom</th><th>URL</th><th>Type</th><th>Intervalle</th><th>Actif</th><th>Dernier poll</th><th>Items</th><th>Erreur</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($sources as $s): ?>
      <tr>
        <td><strong><?= $e($s['name']) ?></strong></td>
        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><a href="<?= $e($s['url']) ?>" target="_blank" class="link" style="font-size:11px"><?= $e($s['url']) ?></a></td>
        <td><code><?= $e($s['source_type']) ?></code></td>
        <td><?= (int)$s['poll_interval'] ?> min</td>
        <td><span class="status <?= $s['is_active'] ? 'status-published' : 'status-draft' ?>"><?= $s['is_active'] ? 'Actif' : 'Inactif' ?></span></td>
        <td style="font-size:11px"><?= $s['last_polled_at'] ? $e(substr($s['last_polled_at'], 0, 16)) : '<span class="muted">Jamais</span>' ?></td>
        <td><?= (int)$s['items_ingested'] ?></td>
        <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:11px"><?= $s['last_error'] ? '<span style="color:#c62828" title="' . $e($s['last_error']) . '">' . $e(mb_substr($s['last_error'], 0, 40)) . '</span>' : '<span class="muted">—</span>' ?></td>
        <td style="display:flex;gap:6px">
          <a class="btn btn-ghost sm" href="/admin/rss-sources/<?= (int)$s['id'] ?>/edit">Éditer</a>
          <form method="post" action="/admin/rss-sources/<?= (int)$s['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cette source ?')">
            <?= Csrf::field() ?>
            <button type="submit" class="btn btn-ghost sm" style="color:#c62828">Suppr.</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$sources): ?><tr><td colspan="9" class="muted">Aucune source RSS configurée.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
