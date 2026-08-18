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

    <!-- Articles populaires -->
    <?php
    $popularHome = \App\Core\Database::all(
        "SELECT a.*, c.slug AS cat_slug, c.accent_color
           FROM articles a JOIN categories c ON c.id = a.category_id
          WHERE a.status = 'published'
          ORDER BY a.views DESC LIMIT 5"
    );
    ?>
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

    <!-- Partenaires -->
    <div class="widget">
        <div class="widget-title"><span><?= $e($lang === 'fr' ? 'Partenaires' : 'Partners') ?></span></div>
        <div class="widget-body trust-list">
            <span>Africa CDC</span><span>WHO AFRO</span><span>AU-IBAR</span><span>FAO</span><span>Wellcome</span><span>Institut Pasteur</span><span>KEMRI</span><span>GARDP</span>
        </div>
    </div>
</aside>
</div>
