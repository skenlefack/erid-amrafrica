<?php
/** @var array $settings @var array $featured @var array $services @var array $categories @var array $kpis @var string $lang */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
$setting = function (string $key) use ($settings, $lang) {
    $row = $settings[$key] ?? null;
    return $row ? ($row['value_' . $lang] ?? $row['value_fr']) : '';
};
$allArticles = \App\Core\Database::all(
    "SELECT a.*, c.slug AS cat_slug, c.name_fr AS cat_fr, c.name_en AS cat_en, c.accent_color
       FROM articles a JOIN categories c ON c.id = a.category_id
      WHERE a.status = 'published'
      ORDER BY a.is_featured DESC, a.published_at DESC LIMIT 20"
);
$hero = array_slice($allArticles, 0, 3);
$block1 = array_slice($allArticles, 3, 4);
$block2 = array_slice($allArticles, 7, 6);
$block3 = array_slice($allArticles, 13, 6);
?>

<!-- ===== BIG GRID HERO ===== -->
<?php if (count($hero) >= 3): ?>
<section class="big-grid">
    <a href="/news/<?= $e($hero[0]['slug']) ?>" class="big-grid__main" style="background-image:url('<?= $e($hero[0]['cover_image'] ?? '') ?>')">
        <div class="big-grid__gradient"></div>
        <div class="big-grid__text">
            <span class="cat-badge" style="background:<?= $e($hero[0]['accent_color']) ?>"><?= $e($pick($hero[0], 'cat')) ?></span>
            <h2><?= $e($pick($hero[0], 'title')) ?></h2>
            <p><?= $e($pick($hero[0], 'excerpt')) ?></p>
        </div>
    </a>
    <div class="big-grid__side">
        <?php for ($i = 1; $i <= 2; $i++): if (isset($hero[$i])): ?>
        <a href="/news/<?= $e($hero[$i]['slug']) ?>" class="big-grid__card" style="background-image:url('<?= $e($hero[$i]['cover_image'] ?? '') ?>')">
            <div class="big-grid__gradient"></div>
            <div class="big-grid__text">
                <span class="cat-badge" style="background:<?= $e($hero[$i]['accent_color']) ?>"><?= $e($pick($hero[$i], 'cat')) ?></span>
                <h3><?= $e($pick($hero[$i], 'title')) ?></h3>
            </div>
        </a>
        <?php endif; endfor; ?>
    </div>
</section>
<?php endif; ?>

<!-- ===== CONTENT + SIDEBAR ===== -->
<div class="container layout-2col">
<div class="col-main">

    <!-- BLOCK: Latest -->
    <?php if ($block1): ?>
    <div class="td-block">
        <div class="block-title"><span><?= $e($lang === 'fr' ? 'Dernières publications' : 'Latest articles') ?></span></div>
        <div class="module-row">
            <?php foreach ($block1 as $a): ?>
            <article class="td-module">
                <a href="/news/<?= $e($a['slug']) ?>" class="td-thumb" style="background-image:url('<?= $e($a['cover_image'] ?? '') ?>')">
                    <span class="cat-badge" style="background:<?= $e($a['accent_color']) ?>"><?= $e($pick($a, 'cat')) ?></span>
                </a>
                <h3 class="td-mod-title"><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                <div class="td-mod-meta"><?= $e($a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '') ?></div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- BLOCK: By channel (2 col with image left) -->
    <?php if ($block2): ?>
    <div class="td-block">
        <div class="block-title"><span><?= $e($lang === 'fr' ? 'Veille stratégique' : 'Strategic intelligence') ?></span></div>
        <?php foreach ($block2 as $a): ?>
        <article class="td-module-horiz">
            <a href="/news/<?= $e($a['slug']) ?>" class="td-thumb-sm" style="background-image:url('<?= $e($a['cover_image'] ?? '') ?>')"></a>
            <div class="td-mod-right">
                <span class="cat-badge cat-badge--sm" style="background:<?= $e($a['accent_color']) ?>"><?= $e($pick($a, 'cat')) ?></span>
                <h3 class="td-mod-title"><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                <div class="td-mod-meta"><?= $e($a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '') ?> &middot; <?= (int)$a['views'] ?> <?= $e(Lang::t('views')) ?></div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- BLOCK: More -->
    <?php if ($block3): ?>
    <div class="td-block">
        <div class="block-title"><span><?= $e($lang === 'fr' ? 'RAM & Politiques' : 'AMR & Policy') ?></span></div>
        <div class="module-row module-row--3">
            <?php foreach ($block3 as $a): ?>
            <article class="td-module">
                <a href="/news/<?= $e($a['slug']) ?>" class="td-thumb" style="background-image:url('<?= $e($a['cover_image'] ?? '') ?>')">
                    <span class="cat-badge" style="background:<?= $e($a['accent_color']) ?>"><?= $e($pick($a, 'cat')) ?></span>
                </a>
                <h3 class="td-mod-title"><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                <div class="td-mod-meta"><?= $e($a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '') ?></div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- SERVICES -->
    <div class="td-block">
        <div class="block-title"><span><?= $e(Lang::t('home_services_title')) ?></span></div>
        <div class="module-row module-row--3">
            <?php foreach ($services as $s): $colors = ['quant'=>'var(--navy)','qual'=>'var(--teal)','systems'=>'var(--gold-d)']; ?>
            <article class="td-module">
                <div class="td-thumb td-thumb--color" style="background:<?= $e($colors[$s['pillar']] ?? 'var(--teal)') ?>">
                    <span class="cat-badge"><?= $e(strtoupper($s['pillar'])) ?></span>
                </div>
                <h3 class="td-mod-title"><a href="/intake/<?= $e($s['pillar']) ?>"><?= $e($pick($s, 'title')) ?></a></h3>
                <p class="td-mod-excerpt"><?= $e($pick($s, 'summary')) ?></p>
                <?php if ($s['price_from_usd']): ?>
                <span class="td-mod-price"><?= $e(Lang::t('from')) ?> $<?= number_format((float)$s['price_from_usd']) ?></span>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-block">
        <h2><?= $e(Lang::t('cta_band_title')) ?></h2>
        <p><?= $e(Lang::t('cta_band_sub')) ?></p>
        <a class="btn btn-accent lg" href="/intake/advisory"><?= $e(Lang::t('cta_band_btn')) ?></a>
    </div>
</div>

<!-- SIDEBAR -->
<aside class="col-side">
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Tableau de bord' : 'Dashboard') ?></span></div>
        <div class="widget-body">
            <div class="kpi-row"><strong><?= number_format($kpis['signals']) ?></strong><span><?= $e(Lang::t('kpi_signals')) ?></span></div>
            <div class="kpi-row"><strong><?= number_format($kpis['articles']) ?></strong><span><?= $e(Lang::t('kpi_articles')) ?></span></div>
            <div class="kpi-row"><strong><?= number_format($kpis['leads']) ?></strong><span><?= $e(Lang::t('kpi_engagements')) ?></span></div>
        </div>
    </div>
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
    <div class="widget widget--dark">
        <div class="widget-title"><span><?= $e(Lang::t('footer_newsletter')) ?></span></div>
        <div class="widget-body">
            <p><?= $e($lang === 'fr' ? 'Recevez notre veille hebdomadaire.' : 'Get our weekly intelligence briefing.') ?></p>
            <a class="btn btn-accent full" href="/pricing"><?= $e(Lang::t('subscribe')) ?></a>
        </div>
    </div>
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Partenaires' : 'Partners') ?></span></div>
        <div class="widget-body trust-list">
            <span>Africa CDC</span><span>WHO AFRO</span><span>AU-IBAR</span><span>FAO</span><span>Wellcome</span>
        </div>
    </div>
</aside>
</div>
