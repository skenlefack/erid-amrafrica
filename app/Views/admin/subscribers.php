<?php
/** @var array $subs @var array $stats @var array $filters */
use App\Core\View; $e = fn($s) => View::e($s);
$f = $filters;
?>
<div class="toolbar"><a class="btn btn-gold" href="/admin/subscribers/export">Exporter CSV</a></div>

<div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:20px">
  <div class="panel" style="flex:1;min-width:120px;text-align:center;padding:16px"><strong style="font-size:1.6rem"><?= $stats['total'] ?></strong><br><small class="muted">Total</small></div>
  <div class="panel" style="flex:1;min-width:120px;text-align:center;padding:16px"><strong style="font-size:1.6rem"><?= $stats['free'] ?></strong><br><small class="muted">Free</small></div>
  <div class="panel" style="flex:1;min-width:120px;text-align:center;padding:16px"><strong style="font-size:1.6rem"><?= $stats['intel'] ?></strong><br><small class="muted">Intelligence</small></div>
  <div class="panel" style="flex:1;min-width:120px;text-align:center;padding:16px"><strong style="font-size:1.6rem"><?= $stats['enterprise'] ?></strong><br><small class="muted">Enterprise</small></div>
  <div class="panel" style="flex:1;min-width:120px;text-align:center;padding:16px"><strong style="font-size:1.6rem"><?= $stats['total'] ? round($stats['confirmed']/$stats['total']*100) : 0 ?>%</strong><br><small class="muted">Confirmés</small></div>
</div>

<div class="panel" style="margin-bottom:20px">
  <form method="get" action="/admin/subscribers" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
    <label style="flex:1;min-width:140px">Tier
      <select name="tier"><option value="">Tous</option>
        <?php foreach (['free','intelligence','enterprise'] as $t): ?>
          <option value="<?= $t ?>" <?= ($f['tier'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label style="flex:1;min-width:140px">Confirmé
      <select name="confirmed"><option value="">Tous</option>
        <option value="1" <?= ($f['confirmed'] ?? '') === '1' ? 'selected' : '' ?>>Oui</option>
        <option value="0" <?= ($f['confirmed'] ?? '') === '0' ? 'selected' : '' ?>>Non</option>
      </select>
    </label>
    <label style="flex:2;min-width:200px">Recherche
      <input type="text" name="q" value="<?= $e($f['search'] ?? '') ?>" placeholder="Email ou nom...">
    </label>
    <button class="btn btn-gold" type="submit">Filtrer</button>
  </form>
</div>

<div class="panel">
  <table class="data-table">
    <thead><tr><th>Email</th><th>Nom</th><th>Organisation</th><th>Tier</th><th>Langue</th><th>Confirmé</th><th>Date</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($subs as $s): ?>
      <tr>
        <td><?= $e($s['email']) ?></td>
        <td><?= $e($s['full_name'] ?? '—') ?></td>
        <td><?= $e($s['organisation'] ?? '—') ?></td>
        <td><span class="badge"><?= $e($s['tier']) ?></span></td>
        <td><?= $e(strtoupper($s['locale'])) ?></td>
        <td><?= $s['confirmed'] ? '&#10003;' : '&#10007;' ?></td>
        <td><?= $e(substr($s['created_at'], 0, 10)) ?></td>
        <td><a class="link" href="/admin/subscribers/<?= (int)$s['id'] ?>">Voir</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$subs): ?><tr><td colspan="8" class="muted">Aucun abonné.</td></tr><?php endif; ?>
    </tbody>
  </table>
  <?php if ($totalPages > 1): ?>
  <?php $qs = fn(array $o) => '?' . http_build_query(array_filter(array_merge($filters, $o))); ?>
  <div style="display:flex;gap:8px;justify-content:center;margin-top:20px">
    <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="/admin/subscribers<?= $qs(['page' => $page - 1]) ?>">← Préc.</a><?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="/admin/subscribers<?= $qs(['page' => $i]) ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="/admin/subscribers<?= $qs(['page' => $page + 1]) ?>">Suiv. →</a><?php endif; ?>
  </div>
  <?php endif; ?>
</div>
