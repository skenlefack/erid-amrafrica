<?php
/** @var array $article @var array $popular @var array $categories */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
$lang = $_SESSION['locale'] ?? 'fr';
$img = $article['cover_image'] ?? '';
?>
<div class="container layout-2col" style="padding-top:30px;padding-bottom:48px">
<div class="col-main">
  <article class="article-single">
    <a class="back" href="/news">← <?= $e(Lang::t('news_hub')) ?></a>

    <span class="cat-badge" style="background:<?= $e($article['accent_color'] ?? 'var(--teal)') ?>;margin-bottom:12px"><?= $e($pick($article, 'cat') ?? '') ?></span>
    <h1 class="page-title" style="margin-bottom:10px"><?= $e($pick($article, 'title')) ?></h1>
    <div class="td-mod-meta" style="margin-bottom:20px"><?= $e($article['published_at'] ? date('d M Y', strtotime($article['published_at'])) : '') ?> · <?= (int)$article['views'] ?> <?= $e(Lang::t('views')) ?></div>

    <?php if ($img): ?>
    <div class="article-cover">
      <img src="<?= $e($img) ?>" alt="<?= $e($pick($article, 'title')) ?>" style="width:100%;border-radius:var(--radius);margin-bottom:24px;box-shadow:var(--shadow)">
    </div>
    <?php endif; ?>

    <p class="lead"><?= $e($pick($article, 'excerpt')) ?></p>
    <div class="article-body"><?= $pick($article, 'body') ?: nl2br($e($pick($article, 'excerpt'))) ?></div>

    <div class="cta-band rounded" style="margin-top:32px">
      <p><?= $e(Lang::t('article_cta')) ?></p>
      <a class="btn btn-accent" href="/intake/analytics"><?= $e(Lang::t('hero_cta')) ?></a>
    </div>
  </article>
</div>

<aside class="col-side">
    <!-- Populaires -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Les plus lus' : 'Most read') ?></span></div>
        <div class="widget-body">
            <?php foreach ($popular as $i => $p): ?>
            <a href="/news/<?= $e($p['slug']) ?>" class="sidebar-article">
                <span class="sidebar-article__num"><?= $i + 1 ?></span>
                <div class="sidebar-article__img" style="background-image:url('<?= $e($p['cover_image'] ?? '') ?>')"></div>
                <div class="sidebar-article__text">
                    <h4><?= $e($pick($p, 'title')) ?></h4>
                    <span class="td-mod-meta"><?= (int)$p['views'] ?> <?= $e(Lang::t('views')) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Canaux -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Canaux' : 'Channels') ?></span></div>
        <div class="widget-body">
            <?php foreach ($categories as $c): ?>
            <a href="/news?cat=<?= $e($c['slug']) ?>" class="widget-cat" style="border-color:<?= $e($c['accent_color']) ?>">
                <span class="cat-dot" style="background:<?= $e($c['accent_color']) ?>"></span>
                <?= $e($pick($c, 'name')) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Newsletter -->
    <div class="widget widget--dark">
        <div class="widget-title"><span><?= $e(Lang::t('footer_newsletter')) ?></span></div>
        <div class="widget-body">
            <p><?= $e($lang === 'fr' ? 'Recevez notre veille hebdomadaire.' : 'Get our weekly intelligence briefing.') ?></p>
            <a class="btn btn-accent full" href="/pricing"><?= $e(Lang::t('subscribe')) ?></a>
        </div>
    </div>

    <!-- CTA Consultation -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Besoin d\'expertise ?' : 'Need expertise?') ?></span></div>
        <div class="widget-body" style="text-align:center">
            <p style="font-size:13px;color:var(--muted);margin:0 0 12px"><?= $e($lang === 'fr' ? 'Nos consultants One Health sont disponibles.' : 'Our One Health consultants are available.') ?></p>
            <a class="btn btn-gold full" href="/intake/advisory"><?= $e(Lang::t('hero_cta')) ?></a>
        </div>
    </div>
</aside>
</div>
