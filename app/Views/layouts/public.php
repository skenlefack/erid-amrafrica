<?php
/** @var string $content @var string $title @var string $lang */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$uri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
$isHome = ($uri === '/' || $uri === '');
?>
<!DOCTYPE html>
<html lang="<?= $e($lang) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title ?? 'ERID-AMRAfrica') ?></title>
    <meta name="description" content="<?= $e(Lang::t('meta_desc')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&display=swap">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="public">

<!-- TOP BAR -->
<div class="top-bar">
    <div class="container top-bar__inner">
        <div class="top-bar__left">
            <span class="top-bar__date"><?= $e(date($lang === 'fr' ? 'l d F Y' : 'l, F d, Y')) ?></span>
        </div>
        <div class="top-bar__right">
            <span class="lang-switch">
                <a href="?lang=fr" class="<?= $lang === 'fr' ? 'on' : '' ?>">FR</a>
                <a href="?lang=en" class="<?= $lang === 'en' ? 'on' : '' ?>">EN</a>
            </span>
        </div>
    </div>
</div>

<!-- HEADER -->
<header class="site-header" role="banner">
    <div class="container header__inner">
        <button class="menu-toggle" id="menuToggle" aria-label="<?= $lang === 'fr' ? 'Menu' : 'Menu' ?>" aria-expanded="false" aria-controls="mainMenu">
            <span></span>
        </button>
        <a class="brand" href="/" aria-label="ERID-AMRAfrica">
            <span class="brand-mark">ERID</span><span class="brand-sub">-AMRAfrica</span>
            <span class="brand-tagline">One Health Intelligence</span>
        </a>
        <a class="header__cta btn btn-accent sm" href="/intake/analytics"><?= $e(Lang::t('nav_cta')) ?></a>
    </div>
</header>

<!-- NAV BAR -->
<nav class="nav-bar" role="navigation" aria-label="<?= $lang === 'fr' ? 'Navigation principale' : 'Main navigation' ?>">
    <div class="container nav-bar__inner">
        <div class="menu" id="mainMenu">
            <a href="/" <?= $isHome ? 'class="active"' : '' ?>><?= $e($lang === 'fr' ? 'Accueil' : 'Home') ?></a>
            <a href="/news" <?= $uri === '/news' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_news')) ?></a>
            <a href="/services" <?= $uri === '/services' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_services')) ?></a>
            <a href="/pricing" <?= $uri === '/pricing' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_pricing')) ?></a>
            <a href="/intake/advisory"><?= $e(Lang::t('cta_band_btn')) ?></a>
        </div>
        <div class="menu-overlay" id="menuOverlay"></div>
    </div>
</nav>

<?php if ($isHome): ?>
<div class="ticker">
    <div class="container ticker__inner">
        <span class="ticker__label"><?= $e($lang === 'fr' ? 'Alerte' : 'Alert') ?></span>
        <span class="ticker__text"><?= $e($lang === 'fr'
            ? 'Surveillance active — Signaux RAM & maladies infectieuses émergentes sur le continent africain'
            : 'Active surveillance — AMR & emerging infectious disease signals across the African continent') ?></span>
    </div>
</div>
<?php endif; ?>

<main class="site-main"><?= $content ?></main>

<!-- FOOTER -->
<footer class="site-footer" role="contentinfo">
    <div class="footer-top">
        <div class="container footer-grid">
            <div class="footer-col">
                <div class="brand footer-brand"><span class="brand-mark">ERID</span><span class="brand-sub">-AMRAfrica</span></div>
                <p class="footer-desc"><?= $e(Lang::t('footer_tagline')) ?></p>
            </div>
            <div class="footer-col">
                <h4><?= $e($lang === 'fr' ? 'Rubriques' : 'Sections') ?></h4>
                <a href="/news"><?= $e(Lang::t('nav_news')) ?></a>
                <a href="/services"><?= $e(Lang::t('nav_services')) ?></a>
                <a href="/pricing"><?= $e(Lang::t('nav_pricing')) ?></a>
                <a href="/intake/advisory"><?= $e(Lang::t('cta_band_btn')) ?></a>
            </div>
            <div class="footer-col">
                <h4><?= $e($lang === 'fr' ? 'Plateforme' : 'Platform') ?></h4>
                <a href="/admin/login"><?= $e($lang === 'fr' ? 'Console admin' : 'Admin console') ?></a>
                <a href="/intake/analytics"><?= $e(Lang::t('nav_cta')) ?></a>
            </div>
            <div class="footer-col">
                <h4><?= $e(Lang::t('footer_newsletter')) ?></h4>
                <p class="footer-desc" style="margin-bottom:12px"><?= $e($lang === 'fr' ? 'Recevez notre veille hebdomadaire.' : 'Get our weekly intelligence briefing.') ?></p>
                <form id="subForm" class="sub-form" aria-label="Newsletter">
                    <?= \App\Core\Csrf::field() ?>
                    <input type="email" name="email" placeholder="email@org.africa" required aria-label="Email">
                    <button class="btn btn-accent sm" type="submit"><?= $e(Lang::t('subscribe')) ?></button>
                </form>
                <small class="muted" id="subMsg" aria-live="polite"></small>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">&copy; <?= date('Y') ?> ERID-AMRAfrica &middot; One Health Intelligence Platform &middot; <a href="/admin/login">Console</a></div>
    </div>
</footer>
<script src="/assets/js/app.js"></script>
</body>
</html>
