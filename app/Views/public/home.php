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
$catColors = [];
foreach ($categories as $c) { $catColors[$c['slug']] = $c['accent_color']; }
?>

<!-- ===== BIG GRID — featured hero block ===== -->
<section class="big-grid">
    <div class="big-grid__main" style="background:var(--navy)">
        <div class="big-grid__overlay"></div>
        <div class="big-grid__content">
            <span class="cat-badge" style="background:var(--accent-color)"><?= $e(Lang::t('hero_eyebrow')) ?></span>
            <h1 class="big-grid__title"><?= $e($setting('site_name') ?: 'ERID-AMRAfrica') ?></h1>
            <p class="big-grid__excerpt"><?= $e($setting('tagline')) ?></p>
            <a class="btn btn-accent" href="/intake/analytics"><?= $e(Lang::t('hero_cta')) ?></a>
        </div>
    </div>
    <div class="big-grid__side">
        <?php if ($featured): foreach (array_slice($featured, 0, 2) as $a): ?>
        <a href="/news/<?= $e($a['slug']) ?>" class="big-grid__card" style="background:<?= $e($a['accent_color'] ?? 'var(--teal)') ?>">
            <div class="big-grid__card-inner">
                <span class="cat-badge" style="background:<?= $e($a['accent_color']) ?>"><?= $e($pick($a, 'cat') ?? '') ?></span>
                <h3><?= $e($pick($a, 'title')) ?></h3>
            </div>
        </a>
        <?php endforeach; else: ?>
        <a href="/news" class="big-grid__card" style="background:var(--teal)">
            <div class="big-grid__card-inner">
                <span class="cat-badge"><?= $e(Lang::t('nav_news')) ?></span>
                <h3><?= $e(Lang::t('home_news_title')) ?></h3>
            </div>
        </a>
        <a href="/services" class="big-grid__card" style="background:var(--gold-d)">
            <div class="big-grid__card-inner">
                <span class="cat-badge"><?= $e(Lang::t('nav_services')) ?></span>
                <h3><?= $e(Lang::t('home_services_title')) ?></h3>
            </div>
        </a>
        <?php endif; ?>
    </div>
</section>

<!-- ===== CONTENT + SIDEBAR ===== -->
<div class="container layout-main">

    <!-- LEFT: content -->
    <div class="content-col">

        <!-- BLOCK: Latest news -->
        <div class="td-block">
            <div class="td-block__header">
                <h2><?= $e(Lang::t('home_news_title')) ?></h2>
            </div>
            <?php if ($featured): ?>
            <div class="module-grid module-grid--2">
                <?php foreach ($featured as $a): ?>
                <article class="td-module">
                    <div class="td-module__thumb" style="background:<?= $e($a['accent_color'] ?? 'var(--teal)') ?>">
                        <span class="cat-badge" style="background:<?= $e($a['accent_color']) ?>"><?= $e($pick($a, 'cat') ?? '') ?></span>
                    </div>
                    <div class="td-module__meta">
                        <h3 class="td-module__title"><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                        <span class="td-module__date"><?= $e($a['published_at'] ?? '') ?></span>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state"><p><?= $e(Lang::t('no_content')) ?></p></div>
            <?php endif; ?>
        </div>

        <!-- BLOCK: Category channels -->
        <div class="td-block">
            <div class="td-block__header">
                <h2><?= $e($lang === 'fr' ? 'Canaux de veille' : 'Intelligence channels') ?></h2>
            </div>
            <div class="channel-grid">
                <?php foreach ($categories as $c): ?>
                <a href="/news?cat=<?= $e($c['slug']) ?>" class="channel-card" style="--accent:<?= $e($c['accent_color']) ?>">
                    <span class="cat-badge" style="background:<?= $e($c['accent_color']) ?>"><?= $e($pick($c, 'name')) ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- BLOCK: Services / Consulting -->
        <div class="td-block">
            <div class="td-block__header">
                <h2><?= $e(Lang::t('home_services_title')) ?></h2>
            </div>
            <div class="module-grid module-grid--3">
                <?php foreach ($services as $s): ?>
                <article class="td-module td-module--service">
                    <div class="td-module__thumb td-module__thumb--pillar" style="background:<?= $e(['quant'=>'var(--navy)','qual'=>'var(--teal)','systems'=>'var(--gold-d)'][$s['pillar']] ?? 'var(--teal)') ?>">
                        <span class="cat-badge"><?= $e(strtoupper($s['pillar'])) ?></span>
                    </div>
                    <div class="td-module__meta">
                        <h3 class="td-module__title"><a href="/intake/<?= $e($s['pillar']) ?>"><?= $e($pick($s, 'title')) ?></a></h3>
                        <p class="td-module__excerpt"><?= $e($pick($s, 'summary')) ?></p>
                        <?php if ($s['price_from_usd']): ?>
                        <span class="td-module__price"><?= $e(Lang::t('from')) ?> $<?= number_format((float)$s['price_from_usd']) ?></span>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- BLOCK: CTA -->
        <div class="cta-block">
            <div class="cta-block__inner">
                <h2><?= $e(Lang::t('cta_band_title')) ?></h2>
                <p><?= $e(Lang::t('cta_band_sub')) ?></p>
                <a class="btn btn-accent lg" href="/intake/advisory"><?= $e(Lang::t('cta_band_btn')) ?></a>
            </div>
        </div>
    </div>

    <!-- RIGHT: sidebar -->
    <aside class="sidebar-col">
        <!-- KPI widget -->
        <div class="widget">
            <div class="widget__header"><h3><?= $e($lang === 'fr' ? 'Tableau de bord' : 'Dashboard') ?></h3></div>
            <div class="kpi-widget">
                <div class="kpi-widget__item">
                    <strong><?= number_format($kpis['signals']) ?></strong>
                    <span><?= $e(Lang::t('kpi_signals')) ?></span>
                </div>
                <div class="kpi-widget__item">
                    <strong><?= number_format($kpis['articles']) ?></strong>
                    <span><?= $e(Lang::t('kpi_articles')) ?></span>
                </div>
                <div class="kpi-widget__item">
                    <strong><?= number_format($kpis['leads']) ?></strong>
                    <span><?= $e(Lang::t('kpi_engagements')) ?></span>
                </div>
            </div>
        </div>

        <!-- Trust widget -->
        <div class="widget">
            <div class="widget__header"><h3><?= $e($lang === 'fr' ? 'Partenaires' : 'Partners') ?></h3></div>
            <div class="trust-widget">
                <span>Africa CDC</span><span>WHO AFRO</span><span>AU-IBAR</span><span>FAO</span><span>Wellcome</span>
            </div>
        </div>

        <!-- Newsletter widget -->
        <div class="widget widget--accent">
            <div class="widget__header"><h3><?= $e(Lang::t('footer_newsletter')) ?></h3></div>
            <p class="widget__desc"><?= $e($lang === 'fr' ? 'Recevez notre veille hebdomadaire directement dans votre boîte.' : 'Get our weekly intelligence briefing in your inbox.') ?></p>
            <a class="btn btn-accent full" href="/pricing"><?= $e(Lang::t('subscribe')) ?></a>
        </div>

        <!-- Pricing teaser -->
        <div class="widget">
            <div class="widget__header"><h3><?= $e(Lang::t('nav_pricing')) ?></h3></div>
            <div class="tier-mini">
                <div class="tier-mini__item"><strong><?= $e(Lang::t('tier_free')) ?></strong><span>$0</span></div>
                <div class="tier-mini__item tier-mini__item--pop"><strong><?= $e(Lang::t('tier_intel')) ?></strong><span>$490<small>/<?= $e(Lang::t('month')) ?></small></span></div>
                <div class="tier-mini__item"><strong><?= $e(Lang::t('tier_ent')) ?></strong><span><?= $e(Lang::t('custom')) ?></span></div>
            </div>
            <a class="btn btn-ghost full sm" href="/pricing" style="margin-top:12px"><?= $e($lang === 'fr' ? 'Voir les offres' : 'View plans') ?></a>
        </div>
    </aside>
</div>
