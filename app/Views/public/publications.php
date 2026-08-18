<?php
/** @var array $publications @var ?string $activeType */
use App\Core\View; use App\Core\Lang;
$e = fn($s) => View::e($s);
$lang = $_SESSION['locale'] ?? 'fr';
$types = ['peer_reviewed'=>'Peer-reviewed','whitepaper'=>'Whitepaper','field_blog'=>'Field blog','policy_brief'=>'Policy brief'];
?>
<div class="container" style="padding:40px 0">
  <h1 class="section-title"><?= $e($lang === 'fr' ? 'Publications & Rapports' : 'Publications & Reports') ?></h1>
  <div class="filters" style="margin-bottom:24px">
    <a class="chip <?= !$activeType ? 'on' : '' ?>" href="/publications"><?= $e($lang === 'fr' ? 'Tous' : 'All') ?></a>
    <?php foreach ($types as $k => $label): ?>
      <a class="chip <?= $activeType === $k ? 'on' : '' ?>" href="/publications?type=<?= $k ?>"><?= $e($label) ?></a>
    <?php endforeach; ?>
  </div>

  <?php if (!$publications): ?>
    <p class="muted"><?= $e($lang === 'fr' ? 'Aucune publication pour le moment.' : 'No publications yet.') ?></p>
  <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:20px">
      <?php foreach ($publications as $p): ?>
        <div class="td-module-horiz" style="display:flex;gap:20px;padding:20px;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08)">
          <div style="flex:0 0 60px;display:flex;align-items:flex-start;justify-content:center;padding-top:4px;font-size:2rem;color:var(--navy)">&#128196;</div>
          <div style="flex:1">
            <div style="display:flex;gap:8px;align-items:center;margin-bottom:6px">
              <span class="badge"><?= $e($types[$p['pub_type']] ?? $p['pub_type']) ?></span>
              <?php if ($p['is_gated']): ?><span class="badge" style="background:var(--gold);color:#fff">&#128274; Premium</span><?php endif; ?>
            </div>
            <h3 style="margin:0 0 6px;font-size:1.1rem"><?= $e(Lang::pick($p, 'title')) ?></h3>
            <?php if ($p['authors']): ?><p style="font-size:.85rem;color:var(--text-muted);margin:0 0 6px"><?= $e($p['authors']) ?></p><?php endif; ?>
            <?php $abstract = Lang::pick($p, 'abstract'); if ($abstract): ?>
              <p style="font-size:.9rem;margin:0 0 10px"><?= $e(mb_substr($abstract, 0, 200)) ?>…</p>
            <?php endif; ?>
            <div style="display:flex;gap:16px;align-items:center;font-size:.85rem">
              <?php if ($p['published_at']): ?><span class="muted"><?= $e($p['published_at']) ?></span><?php endif; ?>
              <span class="muted"><?= (int)$p['downloads'] ?> <?= $e($lang === 'fr' ? 'téléchargements' : 'downloads') ?></span>
              <?php if ($p['file_path']): ?>
                <a href="/publications/<?= (int)$p['id'] ?>/download" class="btn btn-accent sm"><?= $e($lang === 'fr' ? 'Télécharger' : 'Download') ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
