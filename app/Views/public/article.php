<?php
/** @var array $article */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
$img = $article['cover_image'] ?? '';
?>
<?php if ($img): ?>
<div class="article-hero" style="background-image:url('<?= $e($img) ?>')">
    <div class="article-hero__gradient"></div>
    <div class="container article-hero__inner">
        <span class="cat-badge" style="background:<?= $e($article['accent_color'] ?? 'var(--teal)') ?>"><?= $e($pick($article, 'cat') ?? '') ?></span>
        <h1><?= $e($pick($article, 'title')) ?></h1>
        <div class="article-hero__meta"><?= $e($article['published_at'] ? date('d M Y', strtotime($article['published_at'])) : '') ?> &middot; <?= (int)$article['views'] ?> <?= $e(Lang::t('views')) ?></div>
    </div>
</div>
<?php endif; ?>
<article class="section article-single">
  <div class="container narrow">
    <a class="back" href="/news">&larr; <?= $e(Lang::t('news_hub')) ?></a>
    <?php if (!$img): ?>
    <h1 class="page-title"><?= $e($pick($article, 'title')) ?></h1>
    <?php endif; ?>
    <p class="lead"><?= $e($pick($article, 'excerpt')) ?></p>
    <div class="article-body"><?= nl2br($e($pick($article, 'body') ?: $pick($article, 'excerpt'))) ?></div>
    <div class="cta-band rounded">
      <p><?= $e(Lang::t('article_cta')) ?></p>
      <a class="btn btn-accent" href="/intake/analytics"><?= $e(Lang::t('hero_cta')) ?></a>
    </div>
  </div>
</article>
