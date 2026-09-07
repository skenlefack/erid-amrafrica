<?php
/** @var array $services */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$pillarLabels = ['quant' => 'Pillar A', 'qual' => 'Pillar B', 'systems' => 'Pillar C'];
?>
<div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
  <p class="muted" style="margin:0">Gérez les services et leur tarification.</p>
  <a class="btn btn-gold sm" href="/admin/services/new">+ Nouveau service</a>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Pilier</th><th>Titre (FR)</th><th>Modèle</th><th>Prix USD</th><th>Actif</th><th>Ordre</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($services as $s): ?>
      <tr>
        <td><span class="badge"><?= $e($pillarLabels[$s['pillar']] ?? $s['pillar']) ?></span></td>
        <td><strong><?= $e($s['title_fr']) ?></strong><br><small class="muted"><?= $e($s['routing_tag']) ?></small></td>
        <td><?= $e(ucfirst($s['price_model'])) ?></td>
        <td><?= $s['price_from_usd'] ? '$' . number_format((float)$s['price_from_usd']) : '—' ?></td>
        <td><?= $s['is_active'] ? '<span class="status status-published">Oui</span>' : '<span class="status status-draft">Non</span>' ?></td>
        <td><?= (int)$s['sort_order'] ?></td>
        <td style="display:flex;gap:6px">
          <a class="btn btn-ghost sm" href="/admin/services/<?= (int)$s['id'] ?>/edit">Éditer</a>
          <form method="post" action="/admin/services/<?= (int)$s['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer ce service ?')">
            <?= Csrf::field() ?>
            <button type="submit" class="btn btn-ghost sm" style="color:#c62828">Suppr.</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$services): ?><tr><td colspan="7" class="muted">Aucun service configuré.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
