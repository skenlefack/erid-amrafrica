<?php
/** @var array $partners @var bool $enabled */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
  <div style="display:flex;gap:10px;align-items:center">
    <p class="muted" style="margin:0">Réseau & Partenaires — logo bar du footer.</p>
    <form method="post" action="/admin/partners/toggle-footer" style="display:inline">
        <?= Csrf::field() ?>
        <button type="submit" class="btn <?= $enabled ? 'btn-gold' : 'btn-ghost' ?> sm"><?= $enabled ? '✅ Footer activé' : '❌ Footer désactivé' ?></button>
    </form>
  </div>
  <a class="btn btn-gold sm" href="/admin/partners/new">+ Nouveau partenaire</a>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Ordre</th><th>Logo</th><th>Nom</th><th>Site web</th><th>Actif</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($partners as $p): ?>
      <tr>
        <td><?= (int)$p['sort_order'] ?></td>
        <td>
          <?php if ($p['logo_path']): ?>
            <img src="<?= $e($p['logo_path']) ?>" alt="<?= $e($p['name']) ?>" style="height:28px;width:auto;border-radius:3px;background:#f5f5f5;padding:2px">
          <?php else: ?>
            <span class="muted" style="font-size:11px">—</span>
          <?php endif; ?>
        </td>
        <td><strong><?= $e($p['name']) ?></strong></td>
        <td><?php if ($p['website_url']): ?><a href="<?= $e($p['website_url']) ?>" target="_blank" class="link" style="font-size:12px"><?= $e(parse_url($p['website_url'], PHP_URL_HOST) ?: $p['website_url']) ?></a><?php else: ?><span class="muted">—</span><?php endif; ?></td>
        <td><span class="status <?= $p['is_active'] ? 'status-published' : 'status-draft' ?>"><?= $p['is_active'] ? 'Actif' : 'Inactif' ?></span></td>
        <td style="display:flex;gap:6px">
          <a class="btn btn-ghost sm" href="/admin/partners/<?= (int)$p['id'] ?>/edit">Éditer</a>
          <form method="post" action="/admin/partners/<?= (int)$p['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer ce partenaire ?')">
            <?= Csrf::field() ?>
            <button type="submit" class="btn btn-ghost sm" style="color:#c62828">Suppr.</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$partners): ?><tr><td colspan="6" class="muted">Aucun partenaire configuré.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
