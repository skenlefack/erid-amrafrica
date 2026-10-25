<?php
/** @var array $rules */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
  <p class="muted" style="margin:0">Matrice de mots-clés bilingues — les signaux RSS sont filtrés automatiquement.</p>
  <a class="btn btn-gold sm" href="/admin/keyword-rules/new">+ Nouvelle règle</a>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Nom</th><th>Mots-clés</th><th>Langue</th><th>Catégorie</th><th>Poids</th><th>Actif</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($rules as $r): ?>
      <tr>
        <td><strong><?= $e($r['name']) ?></strong></td>
        <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:11px" title="<?= $e($r['pattern']) ?>"><?= $e(mb_substr($r['pattern'], 0, 60)) ?><?= mb_strlen($r['pattern']) > 60 ? '...' : '' ?></td>
        <td><code><?= $e($r['language']) ?></code></td>
        <td><?= $r['category'] ? $e($r['category']) : '<span class="muted">—</span>' ?></td>
        <td><span class="badge"><?= (int)$r['risk_weight'] ?></span></td>
        <td><span class="status <?= $r['is_active'] ? 'status-published' : 'status-draft' ?>"><?= $r['is_active'] ? 'Actif' : 'Inactif' ?></span></td>
        <td style="display:flex;gap:6px">
          <a class="btn btn-ghost sm" href="/admin/keyword-rules/<?= (int)$r['id'] ?>/edit">Éditer</a>
          <form method="post" action="/admin/keyword-rules/<?= (int)$r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Supprimer cette règle ?')">
            <?= Csrf::field() ?>
            <button type="submit" class="btn btn-ghost sm" style="color:#c62828">Suppr.</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rules): ?><tr><td colspan="7" class="muted">Aucune règle configurée.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
