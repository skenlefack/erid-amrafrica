<?php
/** @var array $articles @var ?string $filter @var ?string $search @var int $page @var int $totalPages @var int $total */
use App\Core\View; $e = fn($s) => View::e($s);
$qs = fn(array $o) => '?' . http_build_query(array_filter(array_merge(['status' => $filter, 'q' => $search], $o)));
?>
<div class="toolbar" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
  <a class="btn btn-gold" href="/admin/articles/new">+ Nouvel article</a>
  <form method="get" action="/admin/articles" style="display:flex;gap:6px">
    <?php if ($filter): ?><input type="hidden" name="status" value="<?= $e($filter) ?>"><?php endif; ?>
    <input type="text" name="q" value="<?= $e($search ?? '') ?>" placeholder="Rechercher un article..." style="width:220px;padding:6px 10px;border:1px solid var(--border);border-radius:4px;font-size:13px">
    <button class="btn btn-ghost sm" type="submit">🔍</button>
  </form>
</div>
<div class="filters">
  <?php foreach (['', 'draft','published','archived'] as $st): ?>
    <a class="chip <?= ($filter ?? null) === ($st ?: null) ? 'on' : '' ?>" href="/admin/articles<?= $st ? '?status='.$st : '' ?><?= $search ? '&q='.$e($search) : '' ?>"><?= $e($st ?: 'Tous') ?></a>
  <?php endforeach; ?>
  <span class="muted" style="margin-left:auto;font-size:12px"><?= $total ?> article(s)</span>
</div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Titre (FR)</th><th>Canal</th><th>Statut</th><th>Vues</th><th>Date</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($articles as $a): ?>
      <tr>
        <td><?= $e($a['title_fr']) ?></td>
        <td><span class="badge" style="background:<?= $e($a['accent_color'] ?? '') ?>;color:#fff"><?= $e($a['cat_slug']) ?></span></td>
        <td><span class="status status-<?= $e($a['status']) ?>"><?= $e($a['status']) ?></span></td>
        <td><?= (int)$a['views'] ?></td>
        <td><?= $e(substr((string)($a['published_at'] ?? $a['created_at']), 0, 10)) ?></td>
        <td><a href="/admin/articles/<?= (int)$a['id'] ?>/edit" class="btn btn-ghost sm">Éditer</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$articles): ?><tr><td colspan="6" class="muted">Aucun article.</td></tr><?php endif; ?>
    </tbody>
  </table>
  <?php if ($totalPages > 1): ?>
  <div style="display:flex;gap:8px;justify-content:center;margin-top:20px">
    <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="/admin/articles<?= $qs(['page' => $page - 1]) ?>">← Préc.</a><?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="/admin/articles<?= $qs(['page' => $i]) ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="/admin/articles<?= $qs(['page' => $page + 1]) ?>">Suiv. →</a><?php endif; ?>
  </div>
  <?php endif; ?>
</div>
