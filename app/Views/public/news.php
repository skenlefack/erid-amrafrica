<?php
/** @var array $articles @var array $categories @var ?string $activeCat */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
?>
<div class="container" style="padding-top:32px;padding-bottom:48px">
    <div class="td-block">
        <div class="td-block__header">
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
            <?php foreach ($articles as $a): ?>
            <article class="td-module td-module--list">
                <div class="td-module__thumb" style="background:<?= $e($a['accent_color'] ?? 'var(--teal)') ?>">
                    <span class="cat-badge" style="background:<?= $e($a['accent_color']) ?>"><?= $e($pick($a, 'cat')) ?></span>
                </div>
                <div class="td-module__meta">
                    <h3 class="td-module__title"><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                    <p class="td-module__excerpt"><?= $e($pick($a, 'excerpt')) ?></p>
                    <span class="td-module__date"><?= $e($a['published_at']) ?> &middot; <?= (int)$a['views'] ?> <?= $e(Lang::t('views')) ?></span>
                </div>
            </article>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="empty-state">
            <p><?= $e(Lang::t('no_content')) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
