<?php
/** @var array $logs @var array $entities @var array $actions @var array $filters @var int $page @var int $totalPages @var int $total */
use App\Core\View; $e = fn($s) => View::e($s);
$f = $filters;
$qs = fn(array $overrides) => '?' . http_build_query(array_filter(array_merge($f, $overrides)));
?>
<div class="panel" style="margin-bottom:20px">
  <form method="get" action="/admin/audit" style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end">
    <label style="flex:1;min-width:140px">Entit&eacute;
      <select name="entity"><option value="">Toutes</option>
        <?php foreach ($entities as $ent): ?>
          <option value="<?= $e($ent) ?>" <?= ($f['entity'] ?? '') === $ent ? 'selected' : '' ?>><?= $e($ent) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label style="flex:1;min-width:140px">Action
      <select name="action"><option value="">Toutes</option>
        <?php foreach ($actions as $act): ?>
          <option value="<?= $e($act) ?>" <?= ($f['action'] ?? '') === $act ? 'selected' : '' ?>><?= $e($act) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label style="flex:1;min-width:140px">Du<input type="date" name="from" value="<?= $e($f['from'] ?? '') ?>"></label>
    <label style="flex:1;min-width:140px">Au<input type="date" name="to" value="<?= $e($f['to'] ?? '') ?>"></label>
    <button class="btn btn-gold" type="submit">Filtrer</button>
  </form>
</div>

<div class="panel">
  <p class="muted"><?= $total ?> entr&eacute;e(s) &mdash; page <?= $page ?>/<?= $totalPages ?></p>
  <table class="data-table">
    <thead><tr><th>Date</th><th>Utilisateur</th><th>Action</th><th>Entit&eacute;</th><th>ID</th><th>IP</th><th>D&eacute;tails</th></tr></thead>
    <tbody>
    <?php foreach ($logs as $l):
      $rowClass = match($l['action']) {
          'create' => 'style="background:rgba(46,204,113,.06)"',
          'delete' => 'style="background:rgba(231,76,60,.06)"',
          'login','logout' => 'style="background:rgba(52,152,219,.06)"',
          default => '',
      };
    ?>
      <tr <?= $rowClass ?>>
        <td style="white-space:nowrap"><?= $e(substr($l['created_at'], 0, 16)) ?></td>
        <td><?= $e($l['user_name'] ?? '—') ?></td>
        <td><span class="badge"><?= $e($l['action']) ?></span></td>
        <td><?= $e($l['entity']) ?></td>
        <td><?= $e($l['entity_id'] ?? '—') ?></td>
        <td><small class="muted"><?= $e($l['ip_address'] ?? '') ?></small></td>
        <td><?php if ($l['meta_json']): ?><details><summary>voir</summary><pre style="font-size:.75rem;white-space:pre-wrap"><?= $e($l['meta_json']) ?></pre></details><?php else: ?>&mdash;<?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$logs): ?><tr><td colspan="7" class="muted">Aucune entr&eacute;e.</td></tr><?php endif; ?>
    </tbody>
  </table>

  <?php if ($totalPages > 1): ?>
  <div style="display:flex;gap:8px;justify-content:center;margin-top:20px">
    <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="/admin/audit<?= $qs(['page' => $page - 1]) ?>">&laquo; Pr&eacute;c.</a><?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="/admin/audit<?= $qs(['page' => $i]) ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="/admin/audit<?= $qs(['page' => $page + 1]) ?>">Suiv. &raquo;</a><?php endif; ?>
  </div>
  <?php endif; ?>
</div>
