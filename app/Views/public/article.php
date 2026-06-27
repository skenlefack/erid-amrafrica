<?php
/** @var array $article */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
?>
<article class="section article-single">
  <div class="container narrow">
    <a class="back" href="/news">← <?= $e(Lang::t('news_hub')) ?></a>
    <h1 class="page-title"><?= $e($pick($article, 'title')) ?></h1>
    <p class="lead"><?= $e($pick($article, 'excerpt')) ?></p>
    <div class="article-body"><?= nl2br($e($pick($article, 'body') ?: $pick($article, 'excerpt'))) ?></div>
    <div class="cta-band rounded">
      <p><?= $e(Lang::t('article_cta')) ?></p>
      <a class="btn btn-gold" href="/intake/analytics"><?= $e(Lang::t('hero_cta')) ?></a>
    </div>
  </div>
</article>
