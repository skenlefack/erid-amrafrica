<?php
/** @var array $items @var ?string $activeType @var int $page @var int $totalPages */
use App\Core\View; use App\Core\Lang;
$e = fn($s) => View::e($s);
$lang = $_SESSION['locale'] ?? 'fr';
?>
<div class="container" style="padding:40px 0">
  <h1 class="section-title"><?= $e($lang === 'fr' ? 'Médiathèque' : 'Media Gallery') ?></h1>
  <div class="filters" style="margin-bottom:24px">
    <a class="chip <?= !$activeType ? 'on' : '' ?>" href="/media"><?= $e($lang === 'fr' ? 'Tous' : 'All') ?></a>
    <?php foreach (['video'=>'Vidéo','podcast'=>'Podcast','comic'=>'BD / Comic','image'=>'Image'] as $k => $label): ?>
      <a class="chip <?= $activeType === $k ? 'on' : '' ?>" href="/media?type=<?= $k ?>"><?= $e($label) ?></a>
    <?php endforeach; ?>
  </div>

  <?php if (!$items): ?>
    <p class="muted"><?= $e($lang === 'fr' ? 'Aucun média pour le moment.' : 'No media yet.') ?></p>
  <?php else: ?>
    <div class="td-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px">
      <?php foreach ($items as $m):
        // Auto-generate YouTube thumbnail if none provided
        $thumb = $m['thumbnail'];
        if (!$thumb && $m['type'] === 'video' && $m['embed_url'] && preg_match('#youtube\.com/embed/([^?&]+)#', $m['embed_url'], $ytMatch)) {
            $thumb = 'https://img.youtube.com/vi/' . $ytMatch[1] . '/maxresdefault.jpg';
        }
      ?>
        <div class="td-module" style="border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.08)">
          <?php if ($thumb): ?>
            <div class="td-module__thumb"><img src="<?= $e($thumb) ?>" alt="<?= $e(Lang::pick($m, 'title')) ?>" loading="lazy" style="width:100%;height:180px;object-fit:cover"></div>
          <?php elseif ($m['type'] === 'video' && $m['embed_url']): ?>
            <div style="position:relative;padding-top:56.25%"><iframe src="<?= $e($m['embed_url']) ?>" style="position:absolute;top:0;left:0;width:100%;height:100%;border:0" loading="lazy" allowfullscreen></iframe></div>
          <?php else: ?>
            <div style="height:180px;background:var(--navy);display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem"><?= match($m['type']) { 'video'=>'&#9654;', 'podcast'=>'&#127911;', 'comic'=>'&#128214;', default=>'&#128247;' } ?></div>
          <?php endif; ?>
          <div style="padding:16px">
            <span class="badge" style="margin-bottom:8px"><?= $e(ucfirst($m['sub_type'] ?? $m['type'])) ?></span>
            <h3 style="margin:8px 0 4px;font-size:1.05rem"><?= $e(Lang::pick($m, 'title')) ?></h3>
            <?php $desc = Lang::pick($m, 'description'); if ($desc): ?>
              <p style="font-size:.9rem;color:var(--text-muted)"><?= $e(mb_substr($desc, 0, 120)) ?></p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <?php if ($totalPages > 1): ?>
    <?php $qs = fn($p) => '/media?' . http_build_query(array_filter(['type' => $activeType, 'page' => $p])); ?>
    <div style="display:flex;gap:8px;justify-content:center;margin-top:30px">
      <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="<?= $qs($page - 1) ?>">← Préc.</a><?php endif; ?>
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="<?= $qs($i) ?>"><?= $i ?></a>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="<?= $qs($page + 1) ?>">Suiv. →</a><?php endif; ?>
    </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
