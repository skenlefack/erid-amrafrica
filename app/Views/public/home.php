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

<!-- HERO — editorial masthead -->
<section class="hero">
    <div class="container">
        <div class="hero-editorial">
            <div class="hero-text">
                <div class="dateline"><?= $e(date($lang === 'fr' ? 'd F Y' : 'F d, Y')) ?></div>
                <h1><?= $e($setting('site_name') ?: 'ERID-AMRAfrica') ?></h1>
                <p class="lead"><?= $e($setting('tagline')) ?></p>
                <div class="hero-cta">
                    <a class="btn btn-gold lg" href="/intake/analytics"><?= $e(Lang::t('hero_cta')) ?></a>
                    <a class="btn btn-dark lg" href="/services"><?= $e(Lang::t('hero_secondary')) ?></a>
                </div>
            </div>

            <aside class="hero-sidebar">
                <h2><?= $e($lang === 'fr' ? 'Tableau de bord' : 'Dashboard') ?></h2>
                <div class="kpi-editorial">
                    <strong><?= number_format($kpis['signals']) ?></strong>
                    <span><?= $e(Lang::t('kpi_signals')) ?></span>
                </div>
                <div class="kpi-editorial">
                    <strong><?= number_format($kpis['articles']) ?></strong>
                    <span><?= $e(Lang::t('kpi_articles')) ?></span>
                </div>
                <div class="kpi-editorial">
                    <strong><?= number_format($kpis['leads']) ?></strong>
                    <span><?= $e(Lang::t('kpi_engagements')) ?></span>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- NEWS — editorial magazine grid -->
<section class="section" aria-labelledby="news-heading">
    <div class="container">
        <div class="section-divider">
            <span class="label"><?= $e($lang === 'fr' ? 'Veille' : 'Watch') ?></span>
            <h2 id="news-heading"><?= $e(Lang::t('home_news_title')) ?></h2>
        </div>

        <?php if ($featured): ?>
        <div class="editorial-grid">
            <div class="feature">
                <?php $f = $featured[0]; ?>
                <article class="feature-article">
                    <span class="tag"><span class="tag-dot" style="background:<?= $e($f['accent_color']) ?>"></span><?= $e($pick($f, 'cat') ?? '') ?></span>
                    <h3><a href="/news/<?= $e($f['slug']) ?>"><?= $e($pick($f, 'title')) ?></a></h3>
                    <p><?= $e($pick($f, 'excerpt')) ?></p>
                    <div class="meta"><?= $e($f['published_at'] ?? '') ?></div>
                </article>
            </div>
            <div class="sidebar-articles">
                <?php foreach (array_slice($featured, 1) as $a): ?>
                <article class="side-article">
                    <span class="tag"><span class="tag-dot" style="background:<?= $e($a['accent_color']) ?>"></span><?= $e($pick($a, 'cat') ?? '') ?></span>
                    <h3><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                    <p><?= $e($pick($a, 'excerpt')) ?></p>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">&#9998;</div>
            <p><?= $e(Lang::t('no_content')) ?></p>
        </div>
        <?php endif; ?>

        <div class="news-channels" style="margin-top:var(--sp-7)">
            <?php foreach ($categories as $c): ?>
                <a href="/news?cat=<?= $e($c['slug']) ?>" class="channel" style="--accent:<?= $e($c['accent_color']) ?>">
                    <?= $e($pick($c, 'name')) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SERVICES — pillar blocks -->
<section class="section section-warm" aria-labelledby="pillars-heading">
    <div class="container">
        <div class="section-divider">
            <span class="label"><?= $e($lang === 'fr' ? 'Expertises' : 'Expertise') ?></span>
            <h2 id="pillars-heading"><?= $e(Lang::t('home_services_title')) ?></h2>
        </div>
        <div class="pillars-strip">
            <?php foreach ($services as $s): ?>
            <div class="pillar-block p-<?= $e($s['pillar']) ?>">
                <span class="tag"><?= $e(strtoupper($s['pillar'])) ?></span>
                <h3><?= $e($pick($s, 'title')) ?></h3>
                <p><?= $e($pick($s, 'summary')) ?></p>
                <div class="pillar-foot">
                    <?php if ($s['price_from_usd']): ?>
                        <span class="price"><?= $e(Lang::t('from')) ?> $<?= number_format((float)$s['price_from_usd']) ?></span>
                    <?php else: ?>
                        <span></span>
                    <?php endif; ?>
                    <a class="btn btn-teal sm" href="/intake/<?= $e($s['pillar']) ?>"><?= $e(Lang::t('request')) ?></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- TRUST -->
<div class="trust-band">
    <div class="container">
        <span class="eyebrow"><?= $e($lang === 'fr' ? 'Confiance institutionnelle' : 'Institutional trust') ?></span>
        <div class="trust-logos">
            <span>Africa CDC</span>
            <span>WHO AFRO</span>
            <span>AU-IBAR</span>
            <span>FAO</span>
            <span>Wellcome</span>
        </div>
    </div>
</div>

<!-- CTA -->
<section class="section section-navy" aria-labelledby="cta-heading">
    <div class="container cta-inner">
        <div>
            <span class="eyebrow"><?= $e($lang === 'fr' ? 'Partenariat' : 'Partnership') ?></span>
            <h2 id="cta-heading"><?= $e(Lang::t('cta_band_title')) ?></h2>
            <p><?= $e(Lang::t('cta_band_sub')) ?></p>
        </div>
        <a class="btn btn-gold lg" href="/intake/advisory"><?= $e(Lang::t('cta_band_btn')) ?></a>
    </div>
</section>
