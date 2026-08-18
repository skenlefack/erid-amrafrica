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
$sidebarLatest = array_slice($allArticles, 3, 5);
$block1 = array_slice($allArticles, 3, 4);
$block2 = array_slice($allArticles, 7, 6);
$block3 = array_slice($allArticles, 13, 6);
?>

<!-- ===== HERO: Slideshow 3/4 + Sidebar latest 1/4 ===== -->
<?php if ($hero): ?>
<section class="hero-section container">
  <div class="hero-grid">
    <!-- LEFT: Slideshow 3/4 -->
    <div class="hero-slideshow">
      <div class="slideshow-track">
        <?php foreach ($hero as $idx => $slide): ?>
        <div class="slide <?= $idx === 0 ? 'active' : '' ?>" style="background-image:url('<?= $e($slide['cover_image'] ?? '') ?>')">
          <div class="slide__overlay"></div>
          <div class="slide__content">
            <span class="cat-badge" style="background:<?= $e($slide['accent_color']) ?>"><?= $e($pick($slide, 'cat')) ?></span>
            <h2><?= $e($pick($slide, 'title')) ?></h2>
            <p><?= $e($pick($slide, 'excerpt')) ?></p>
            <a class="btn btn-gold" href="/news/<?= $e($slide['slug']) ?>"><?= $e($lang === 'fr' ? 'Lire la suite' : 'Read more') ?> &rarr;</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="slideshow-dots">
        <?php foreach ($hero as $idx => $slide): ?>
        <button class="dot <?= $idx === 0 ? 'active' : '' ?>" data-slide="<?= $idx ?>"></button>
        <?php endforeach; ?>
      </div>
      <button class="slide-arrow slide-prev">&lsaquo;</button>
      <button class="slide-arrow slide-next">&rsaquo;</button>
    </div>

    <!-- RIGHT: 5 dernieres publications 1/4 -->
    <div class="hero-sidebar">
      <div class="hero-sidebar__head"><?= $e($lang === 'fr' ? 'Derni&egrave;res publications' : 'Latest articles') ?></div>
      <?php foreach ($sidebarLatest as $sa): ?>
      <a href="/news/<?= $e($sa['slug']) ?>" class="hero-sidebar__item">
        <div class="hero-sidebar__thumb" style="background-image:url('<?= $e($sa['cover_image'] ?? '') ?>')"></div>
        <div class="hero-sidebar__text">
          <h4><?= $e($pick($sa, 'title')) ?></h4>
          <span class="hero-sidebar__date"><?= $e($sa['published_at'] ? date('d M Y', strtotime($sa['published_at'])) : '') ?></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
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

    <!-- BLOCK: Veille strategique (3 colonnes, image top + titre) -->
    <?php if ($block2): ?>
    <div class="td-block">
        <div class="block-title"><span><?= $e($lang === 'fr' ? 'Veille strat&eacute;gique' : 'Strategic intelligence') ?></span></div>
        <div class="module-row module-row--3">
            <?php foreach ($block2 as $a): ?>
            <article class="td-module">
                <a href="/news/<?= $e($a['slug']) ?>" class="td-thumb" style="background-image:url('<?= $e($a['cover_image'] ?? '') ?>')">
                    <span class="cat-badge" style="background:<?= $e($a['accent_color']) ?>"><?= $e($pick($a, 'cat')) ?></span>
                </a>
                <h3 class="td-mod-title"><a href="/news/<?= $e($a['slug']) ?>"><?= $e($pick($a, 'title')) ?></a></h3>
                <div class="td-mod-meta"><?= $e($a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '') ?> &middot; <?= (int)$a['views'] ?> <?= $e(Lang::t('views')) ?></div>
            </article>
            <?php endforeach; ?>
        </div>
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
<?php
$popularHome = \App\Core\Database::all(
    "SELECT a.*, c.slug AS cat_slug, c.accent_color
       FROM articles a JOIN categories c ON c.id = a.category_id
      WHERE a.status = 'published'
      ORDER BY a.views DESC LIMIT 5"
);
$interviews = \App\Core\Database::all(
    "SELECT a.*, c.slug AS cat_slug, c.accent_color
       FROM articles a JOIN categories c ON c.id = a.category_id
      WHERE a.status = 'published' AND c.slug = 'interviews'
      ORDER BY a.published_at DESC LIMIT 4"
);
$catCounts = \App\Core\Database::all(
    "SELECT c.*, COUNT(a.id) AS total
       FROM categories c LEFT JOIN articles a ON a.category_id = c.id AND a.status = 'published'
      GROUP BY c.id ORDER BY c.sort_order"
);
?>
<aside class="col-side">
    <!-- KPIs -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Tableau de bord' : 'Dashboard') ?></span></div>
        <div class="widget-body">
            <div class="kpi-row"><strong><?= number_format($kpis['signals']) ?></strong><span><?= $e(Lang::t('kpi_signals')) ?></span></div>
            <div class="kpi-row"><strong><?= number_format($kpis['articles']) ?></strong><span><?= $e(Lang::t('kpi_articles')) ?></span></div>
            <div class="kpi-row"><strong><?= number_format($kpis['leads']) ?></strong><span><?= $e(Lang::t('kpi_engagements')) ?></span></div>
        </div>
    </div>

    <!-- Interviews -->
    <div class="widget">
        <div class="widget-title" style="background:#E53935"><span>Interviews</span></div>
        <div class="widget-body">
            <?php if ($interviews): ?>
                <?php foreach ($interviews as $iv): ?>
                <a href="/news/<?= $e($iv['slug']) ?>" class="sidebar-article">
                    <div class="sidebar-article__img" style="background-image:url('<?= $e($iv['cover_image'] ?? '') ?>')"></div>
                    <div class="sidebar-article__text">
                        <h4><?= $e($pick($iv, 'title')) ?></h4>
                        <span class="td-mod-meta"><?= $e($iv['published_at'] ? date('d M Y', strtotime($iv['published_at'])) : '') ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="muted" style="font-size:13px;font-style:italic;margin:0"><?= $e($lang === 'fr' ? 'Interviews à venir...' : 'Coming soon...') ?></p>
            <?php endif; ?>
            <a href="/news?cat=interviews" class="btn btn-ghost full sm" style="margin-top:10px"><?= $e($lang === 'fr' ? 'Toutes les interviews' : 'All interviews') ?> &rarr;</a>
        </div>
    </div>

    <!-- Catégories -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Cat&eacute;gories' : 'Categories') ?></span></div>
        <div class="widget-body" style="padding:12px">
            <div class="cat-grid">
                <?php foreach ($catCounts as $cc): ?>
                <a href="/news?cat=<?= $e($cc['slug']) ?>" class="cat-card" style="--cat-color:<?= $e($cc['accent_color']) ?>">
                    <span class="cat-card__count"><?= (int)$cc['total'] ?></span>
                    <span class="cat-card__name"><?= $e($pick($cc, 'name')) ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Les plus lus -->
    <?php if ($popularHome): ?>
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Les plus lus' : 'Most read') ?></span></div>
        <div class="widget-body">
            <?php foreach ($popularHome as $i => $p): ?>
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

    <!-- R&eacute;seaux sociaux -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Suivez-nous' : 'Follow us') ?></span></div>
        <div class="widget-body">
            <div class="social-grid">
                <a href="https://www.youtube.com/@ERID-AMRAfrica" target="_blank" rel="noopener" class="social-btn social-btn--youtube">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31.5 31.5 0 0 0 0 12a31.5 31.5 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31.5 31.5 0 0 0 24 12a31.5 31.5 0 0 0-.5-5.8zM9.5 15.5V8.5l6.3 3.5-6.3 3.5z"/></svg>
                    YouTube
                </a>
                <a href="https://x.com/eridamrafrica" target="_blank" rel="noopener" class="social-btn social-btn--x">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.2 2.25h3.51l-7.67 8.77 9.02 11.92h-7.06l-5.54-7.24-6.34 7.24H.61l8.2-9.38L.2 2.25h7.24l5.01 6.62 5.75-6.62zm-1.23 18.56h1.94L7.16 4.23H5.08l11.89 16.58z"/></svg>
                    X / Twitter
                </a>
                <a href="https://www.linkedin.com/company/erid-amrafrica" target="_blank" rel="noopener" class="social-btn social-btn--linkedin">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.36V9h3.41v1.56h.05a3.74 3.74 0 0 1 3.37-1.85c3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77A1.75 1.75 0 0 0 0 1.73v20.54A1.75 1.75 0 0 0 1.77 24h20.45A1.75 1.75 0 0 0 24 22.27V1.73A1.75 1.75 0 0 0 22.22 0z"/></svg>
                    LinkedIn
                </a>
                <a href="https://www.facebook.com/eridamrafrica" target="_blank" rel="noopener" class="social-btn social-btn--facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.07C24 5.41 18.63 0 12 0S0 5.41 0 12.07c0 6.02 4.39 11.01 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.95.93-1.95 1.88v2.26h3.33l-.53 3.49h-2.8v8.44C19.61 23.08 24 18.09 24 12.07z"/></svg>
                    Facebook
                </a>
            </div>
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
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Consultation' : 'Consultation') ?></span></div>
        <div class="widget-body" style="text-align:center">
            <p style="font-size:13px;color:var(--muted);margin:0 0 12px"><?= $e($lang === 'fr' ? 'Expertise One Health sur mesure pour votre institution.' : 'Tailored One Health expertise for your institution.') ?></p>
            <a class="btn btn-gold full" href="/intake/advisory"><?= $e(Lang::t('hero_cta')) ?></a>
        </div>
    </div>

    <!-- Services / Nos expertises -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Nos expertises' : 'Our expertise') ?></span></div>
        <div class="widget-body" style="padding:0">
            <?php
            $pillarIcons = ['quant' => '📊', 'qual' => '🧠', 'systems' => '🔄'];
            $pillarColors = ['quant' => 'var(--navy)', 'qual' => 'var(--accent)', 'systems' => 'var(--gold)'];
            foreach ($services as $s):
            ?>
            <div class="sidebar-service" style="--svc-color:<?= $e($pillarColors[$s['pillar']] ?? 'var(--accent)') ?>">
                <div class="sidebar-service__icon"><?= $pillarIcons[$s['pillar']] ?? '💡' ?></div>
                <div class="sidebar-service__body">
                    <h4><?= $e($pick($s, 'title')) ?></h4>
                    <p><?= $e(mb_substr($pick($s, 'summary') ?? '', 0, 80)) ?>&hellip;</p>
                    <div class="sidebar-service__meta">
                        <?php if ($s['price_from_usd']): ?>
                        <span class="sidebar-service__price"><?= $e(Lang::t('from')) ?> $<?= number_format((float)$s['price_from_usd']) ?></span>
                        <?php endif; ?>
                        <span class="sidebar-service__model"><?= $e(Lang::t('model_' . $s['price_model'])) ?></span>
                    </div>
                    <a href="/intake/<?= $e($s['pillar']) ?>" class="sidebar-service__link"><?= $e(Lang::t('request')) ?> &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Partenaires -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Partenaires' : 'Partners') ?></span></div>
        <div class="widget-body trust-list">
            <span>Africa CDC</span><span>WHO AFRO</span><span>AU-IBAR</span><span>FAO</span><span>Wellcome</span><span>Institut Pasteur</span><span>KEMRI</span><span>GARDP</span>
        </div>
    </div>
</aside>
</div>
