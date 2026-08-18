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
                <div class="td-mod-meta"><?= $e($a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '') ?> · <?= (int)$a['views'] ?> <?= $e(Lang::t('views')) ?></div>
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
                <div class="td-mod-meta"><?= $e($a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '') ?> · <?= (int)$a['views'] ?> <?= $e(Lang::t('views')) ?></div>
            </div>
        </article>
        <?php endforeach; ?>

        <?php else: ?>
        <div class="empty-state"><p><?= $e(Lang::t('no_content')) ?></p></div>
        <?php endif; ?>

        <?php if (($totalPages ?? 1) > 1): ?>
        <div style="display:flex;gap:8px;justify-content:center;margin-top:30px">
          <?php $qs = fn($p) => '/news?' . http_build_query(array_filter(['cat' => $activeCat, 'page' => $p])); ?>
          <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="<?= $qs($page - 1) ?>">« <?= $e(Lang::t('prev')) ?></a><?php endif; ?>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="<?= $qs($i) ?>"><?= $i ?></a>
          <?php endfor; ?>
          <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="<?= $qs($page + 1) ?>"><?= $e(Lang::t('next')) ?> »</a><?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<aside class="col-side">
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

    <!-- Les plus lus -->
    <?php if (!empty($popular)): ?>
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
    <?php endif; ?>

    <!-- Newsletter -->
    <div class="widget widget--dark">
        <div class="widget-title"><span><?= $e(Lang::t('footer_newsletter')) ?></span></div>
        <div class="widget-body">
            <p><?= $e($lang === 'fr' ? 'Recevez notre veille hebdomadaire.' : 'Get our weekly intelligence briefing.') ?></p>
            <a class="btn btn-accent full" href="/pricing"><?= $e(Lang::t('subscribe')) ?></a>
        </div>
    </div>

    <!-- CTA -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Consultation' : 'Consultation') ?></span></div>
        <div class="widget-body" style="text-align:center">
            <p style="font-size:13px;color:var(--muted);margin:0 0 12px"><?= $e($lang === 'fr' ? 'Expertise One Health sur mesure.' : 'Tailored One Health expertise.') ?></p>
            <a class="btn btn-gold full" href="/intake/advisory"><?= $e(Lang::t('hero_cta')) ?></a>
        </div>
    </div>
</aside>
</div>
</div>
