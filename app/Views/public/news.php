<?php
/** @var array $articles @var array $categories @var ?string $activeCat */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
?>
<div class="container" style="padding-top:30px;padding-bottom:48px">
<div class="layout-2col">
<div class="col-main">
    <div class="td-block">
        <div class="block-title"><span><?= $e(Lang::t('news_hub')) ?></span></div>

        <div class="news-tabs">
            <a href="/news" class="news-tab <?= !$activeCat ? 'active' : '' ?>"><?= $e(Lang::t('all')) ?></a>
            <?php foreach ($categories as $c): ?>
            <a href="/news?cat=<?= $e($c['slug']) ?>"
               class="news-tab <?= $activeCat === $c['slug'] ? 'active' : '' ?>"><?= $e($pick($c, 'name')) ?></a>
            <?php endforeach; ?>
        </div>

        <?php if ($articles): ?>
        <div class="module-row module-row--2">
            <?php foreach (array_slice($articles, 0, 2) as $a): ?>
            <article class="td-module td-module--lg">
                <a href="/news/<?= $e($a['slug']) ?>" class="td-thumb td-thumb--lg" style="background-image:url('<?= $e($a['cover_image'] ?? '') ?>')">
                    <span class="cat-badge" style="background:<?= $e($a['accent_color']) ?>"><?= $e($pick($a, 'cat')) ?></span>
                </a>
                <h3 class="td-mod-title td-mod-title--lg"><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                <p class="td-mod-excerpt"><?= $e($pick($a, 'excerpt')) ?></p>
                <div class="td-mod-meta"><?= $e($a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '') ?> &middot; <?= (int)$a['views'] ?> <?= $e(Lang::t('views')) ?></div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php foreach (array_slice($articles, 2) as $a): ?>
        <article class="td-module-horiz">
            <a href="/news/<?= $e($a['slug']) ?>" class="td-thumb-sm" style="background-image:url('<?= $e($a['cover_image'] ?? '') ?>')"></a>
            <div class="td-mod-right">
                <span class="cat-badge cat-badge--sm" style="background:<?= $e($a['accent_color']) ?>"><?= $e($pick($a, 'cat')) ?></span>
                <h3 class="td-mod-title"><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                <p class="td-mod-excerpt"><?= $e($pick($a, 'excerpt')) ?></p>
                <div class="td-mod-meta"><?= $e($a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '') ?> &middot; <?= (int)$a['views'] ?> <?= $e(Lang::t('views')) ?></div>
            </div>
        </article>
        <?php endforeach; ?>

        <?php else: ?>
        <div class="empty-state"><p><?= $e(Lang::t('no_content')) ?></p></div>
        <?php endif; ?>
    </div>
</div>
<aside class="col-side">
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
</aside>
</div>
</div>
