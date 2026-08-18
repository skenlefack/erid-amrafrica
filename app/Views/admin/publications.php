<?php
/** @var array $publications @var ?string $filter */
use App\Core\View; $e = fn($s) => View::e($s);
$types = ['peer_reviewed'=>'Peer-reviewed','whitepaper'=>'Whitepaper','field_blog'=>'Field blog','policy_brief'=>'Policy brief'];
?>
<div class="toolbar"><a class="btn btn-gold" href="/admin/publications/new">+ Nouvelle publication</a></div>
<div class="filters">
  <a class="chip <?= !($filter ?? null) ? 'on' : '' ?>" href="/admin/publications">Tous</a>
  <?php foreach ($types as $k => $label): ?>
    <a class="chip <?= ($filter ?? null) === $k ? 'on' : '' ?>" href="/admin/publications?type=<?= $k ?>"><?= $e($label) ?></a>
  <?php endforeach; ?>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Titre (FR)</th><th>Type</th><th>Auteurs</th><th>Téléch.</th><th>Accès</th><th>Date</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($publications as $p): ?>
      <tr>
        <td><?= $e($p['title_fr']) ?></td>
        <td><span class="badge"><?= $e($types[$p['pub_type']] ?? $p['pub_type']) ?></span></td>
        <td><?= $e(mb_substr($p['authors'] ?? '', 0, 40)) ?></td>
        <td><?= (int)$p['downloads'] ?></td>
        <td><?= $p['is_gated'] ? '<span class="badge">Premium</span>' : 'Libre' ?></td>
        <td><?= $e($p['published_at'] ?? '—') ?></td>
        <td><a href="/admin/publications/<?= (int)$p['id'] ?>/edit" class="btn btn-ghost sm">Éditer</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$publications): ?><tr><td colspan="7" class="muted">Aucune publication.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
