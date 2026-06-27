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
?>
<section class="hero">
    <div class="container">
        <p class="eyebrow"><?= $e(Lang::t('hero_eyebrow')) ?></p>
        <h1><?= $e($setting('site_name') ?: 'ERID-AMRAfrica') ?></h1>
        <p class="lead"><?= $e($setting('tagline')) ?></p>
        <div class="hero-cta">
            <a class="btn btn-gold lg" href="/intake/analytics"><?= $e(Lang::t('hero_cta')) ?></a>
            <a class="btn btn-outline lg" href="/services"><?= $e(Lang::t('hero_secondary')) ?></a>
        </div>
        <div class="kpi-strip">
            <div><strong><?= number_format($kpis['signals']) ?></strong><span><?= $e(Lang::t('kpi_signals')) ?></span></div>
            <div><strong><?= number_format($kpis['articles']) ?></strong><span><?= $e(Lang::t('kpi_articles')) ?></span></div>
            <div><strong><?= number_format($kpis['leads']) ?></strong><span><?= $e(Lang::t('kpi_engagements')) ?></span></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section-title"><?= $e(Lang::t('home_services_title')) ?></h2>
        <p class="section-sub"><?= $e(Lang::t('home_services_sub')) ?></p>
        <div class="grid-3">
            <?php foreach ($services as $s): ?>
            <article class="card pillar pillar-<?= $e($s['pillar']) ?>">
                <span class="tag"><?= $e(strtoupper($s['pillar'])) ?></span>
                <h3><?= $e($pick($s, 'title')) ?></h3>
                <p><?= $e($pick($s, 'summary')) ?></p>
                <div class="card-foot">
                    <?php if ($s['price_from_usd']): ?>
                        <span class="price"><?= $e(Lang::t('from')) ?> $<?= number_format((float)$s['price_from_usd']) ?></span>
                    <?php endif; ?>
                    <a class="btn btn-teal sm" href="/intake/<?= $e($s['pillar']) ?>"><?= $e(Lang::t('request')) ?></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <h2 class="section-title"><?= $e(Lang::t('home_news_title')) ?></h2>
        <div class="news-channels">
            <?php foreach ($categories as $c): ?>
                <a href="/news?cat=<?= $e($c['slug']) ?>" class="channel" style="--accent:<?= $e($c['accent_color']) ?>">
                    <?= $e($pick($c, 'name')) ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="grid-2">
            <?php foreach ($featured as $a): ?>
            <article class="card news-card" style="--accent:<?= $e($a['accent_color']) ?>">
                <span class="channel-dot"></span>
                <h3><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                <p><?= $e($pick($a, 'excerpt')) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section cta-band">
    <div class="container cta-inner">
        <div>
            <h2><?= $e(Lang::t('cta_band_title')) ?></h2>
            <p><?= $e(Lang::t('cta_band_sub')) ?></p>
        </div>
        <a class="btn btn-gold lg" href="/intake/advisory"><?= $e(Lang::t('cta_band_btn')) ?></a>
    </div>
</section>
