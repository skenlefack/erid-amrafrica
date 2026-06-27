<?php
/** @var array $articles @var array $categories @var ?string $activeCat */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
?>
<section class="section">
  <div class="container">
    <h1 class="page-title"><?= $e(Lang::t('news_hub')) ?></h1>
    <div class="news-channels">
      <a href="/news" class="channel <?= !$activeCat ? 'on' : '' ?>"><?= $e(Lang::t('all')) ?></a>
      <?php foreach ($categories as $c): ?>
        <a href="/news?cat=<?= $e($c['slug']) ?>"
           class="channel <?= $activeCat === $c['slug'] ? 'on' : '' ?>"
           style="--accent:<?= $e($c['accent_color']) ?>"><?= $e($pick($c, 'name')) ?></a>
      <?php endforeach; ?>
    </div>
    <div class="grid-2">
      <?php foreach ($articles as $a): ?>
        <article class="card news-card" style="--accent:<?= $e($a['accent_color']) ?>">
          <span class="tag"><?= $e($pick($a, 'cat')) ?></span>
          <h3><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
          <p><?= $e($pick($a, 'excerpt')) ?></p>
          <small class="muted"><?= $e($a['published_at']) ?> · <?= (int)$a['views'] ?> <?= $e(Lang::t('views')) ?></small>
        </article>
      <?php endforeach; ?>
      <?php if (!$articles): ?><p class="muted"><?= $e(Lang::t('no_content')) ?></p><?php endif; ?>
    </div>
  </div>
</section>
