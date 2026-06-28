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
$pillarColors = ['quant' => 'var(--navy)', 'qual' => 'var(--teal)', 'systems' => 'var(--gold)'];
?>

<!-- ========== SLIDESHOW ========== -->
<section class="slideshow" aria-roledescription="carousel" aria-label="<?= $e($lang === 'fr' ? 'Diaporama principal' : 'Main slideshow') ?>">

    <!-- Slide 1 — Mission -->
    <div class="slide slide--active" data-slide="0" aria-roledescription="slide" aria-label="1 / 3">
        <div class="slide__bg slide__bg--mission"></div>
        <div class="slide__overlay"></div>
        <div class="slide__content container">
            <div class="slide__body">
                <span class="slide__eyebrow"><?= $e(Lang::t('hero_eyebrow')) ?></span>
                <h1 class="slide__title slide__title--huge"><?= $e($setting('site_name') ?: 'ERID-AMRAfrica') ?></h1>
                <p class="slide__lead"><?= $e($setting('tagline')) ?></p>
                <div class="slide__actions">
                    <a class="btn btn-gold lg" href="/intake/analytics"><?= $e(Lang::t('hero_cta')) ?></a>
                    <a class="btn btn-outline lg" href="/services"><?= $e(Lang::t('hero_secondary')) ?></a>
                </div>
            </div>
            <div class="slide__kpis">
                <div class="kpi-glass">
                    <strong><?= number_format($kpis['signals']) ?></strong>
                    <span><?= $e(Lang::t('kpi_signals')) ?></span>
                </div>
                <div class="kpi-glass">
                    <strong><?= number_format($kpis['articles']) ?></strong>
                    <span><?= $e(Lang::t('kpi_articles')) ?></span>
                </div>
                <div class="kpi-glass">
                    <strong><?= number_format($kpis['leads']) ?></strong>
                    <span><?= $e(Lang::t('kpi_engagements')) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 2 — Expertise -->
    <div class="slide" data-slide="1" aria-roledescription="slide" aria-label="2 / 3">
        <div class="slide__bg slide__bg--expertise"></div>
        <div class="slide__overlay slide__overlay--teal"></div>
        <div class="slide__content container">
            <div class="slide__body">
                <span class="slide__eyebrow"><?= $e($lang === 'fr' ? 'Nos 3 piliers' : 'Our 3 pillars') ?></span>
                <h2 class="slide__title"><?= $e(Lang::t('home_services_title')) ?></h2>
                <p class="slide__lead"><?= $e(Lang::t('home_services_sub')) ?></p>
                <div class="slide__actions">
                    <a class="btn btn-gold lg" href="/services"><?= $e(Lang::t('hero_secondary')) ?></a>
                    <a class="btn btn-outline lg" href="/pricing"><?= $e(Lang::t('nav_pricing')) ?></a>
                </div>
            </div>
            <div class="slide__pillars">
                <?php foreach ($services as $i => $s): ?>
                <div class="pillar-mini" style="--accent:<?= $e($pillarColors[$s['pillar']] ?? 'var(--teal)') ?>">
                    <span class="pillar-mini__tag"><?= $e(strtoupper($s['pillar'])) ?></span>
                    <h3><?= $e($pick($s, 'title')) ?></h3>
                    <?php if ($s['price_from_usd']): ?>
                    <span class="pillar-mini__price"><?= $e(Lang::t('from')) ?> $<?= number_format((float)$s['price_from_usd']) ?></span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Slide 3 — Intelligence -->
    <div class="slide" data-slide="2" aria-roledescription="slide" aria-label="3 / 3">
        <div class="slide__bg slide__bg--intel"></div>
        <div class="slide__overlay slide__overlay--dark"></div>
        <div class="slide__content container">
            <div class="slide__body">
                <span class="slide__eyebrow"><?= $e($lang === 'fr' ? 'Veille stratégique' : 'Strategic intelligence') ?></span>
                <h2 class="slide__title"><?= $e(Lang::t('home_news_title')) ?></h2>
                <p class="slide__lead"><?= $e($lang === 'fr'
                    ? 'Surveillance RAM, spillover zoonotique, politique sanitaire et innovation — quatre canaux de veille alimentés en continu.'
                    : 'AMR surveillance, zoonotic spillover, health policy and innovation — four continuously updated intelligence channels.') ?></p>
                <div class="slide__actions">
                    <a class="btn btn-gold lg" href="/news"><?= $e(Lang::t('nav_news')) ?></a>
                    <a class="btn btn-outline lg" href="/intake/advisory"><?= $e(Lang::t('cta_band_btn')) ?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="slide-controls">
        <button class="slide-arrow slide-arrow--prev" aria-label="<?= $e($lang === 'fr' ? 'Précédent' : 'Previous') ?>">&#8592;</button>
        <div class="slide-dots">
            <button class="slide-dot slide-dot--active" data-goto="0" aria-label="Slide 1"></button>
            <button class="slide-dot" data-goto="1" aria-label="Slide 2"></button>
            <button class="slide-dot" data-goto="2" aria-label="Slide 3"></button>
        </div>
        <button class="slide-arrow slide-arrow--next" aria-label="<?= $e($lang === 'fr' ? 'Suivant' : 'Next') ?>">&#8594;</button>
    </div>

    <!-- Progress bar -->
    <div class="slide-progress"><div class="slide-progress__bar"></div></div>
</section>

<!-- ========== NEWS — editorial grid ========== -->
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
                <a href="/news?cat=<?= $e($c['slug']) ?>" class="channel"><?= $e($pick($c, 'name')) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== SERVICES — full-bleed pillar blocks ========== -->
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
