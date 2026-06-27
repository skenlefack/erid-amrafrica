<?php
/** @var array $articles @var array $categories @var ?string $activeCat */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
?>
<section class="section">
  <div class="container">
    <div class="section-divider">
      <span class="label"><?= $e(Lang::t('hero_eyebrow')) ?></span>
      <h2><?= $e(Lang::t('news_hub')) ?></h2>
    </div>

    <div class="news-channels">
      <a href="/news" class="channel <?= !$activeCat ? 'on' : '' ?>"><?= $e(Lang::t('all')) ?></a>
      <?php foreach ($categories as $c): ?>
        <a href="/news?cat=<?= $e($c['slug']) ?>"
           class="channel <?= $activeCat === $c['slug'] ? 'on' : '' ?>"><?= $e($pick($c, 'name')) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if ($articles): ?>
    <div class="news-list">
      <?php foreach ($articles as $a): ?>
        <article class="news-item" style="--accent:<?= $e($a['accent_color']) ?>">
          <div class="accent-bar"></div>
          <div>
            <span class="tag"><span class="tag-dot" style="background:<?= $e($a['accent_color']) ?>"></span><?= $e($pick($a, 'cat')) ?></span>
            <h3><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
            <p><?= $e($pick($a, 'excerpt')) ?></p>
          </div>
          <div class="meta"><?= $e($a['published_at']) ?><br><?= (int)$a['views'] ?> <?= $e(Lang::t('views')) ?></div>
        </article>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon">&#9998;</div>
        <p><?= $e(Lang::t('no_content')) ?></p>
    </div>
    <?php endif; ?>
  </div>
</section>
