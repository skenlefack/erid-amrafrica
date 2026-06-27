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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="public">
<header class="site-header" role="banner">
    <div class="container nav-bar">
        <a class="brand" href="/" aria-label="ERID-AMRAfrica Home">
            <span class="brand-mark">ERID</span><span class="brand-sub">-AMRAfrica</span>
        </a>

        <button class="menu-toggle" id="menuToggle" aria-label="<?= $lang === 'fr' ? 'Ouvrir le menu' : 'Open menu' ?>" aria-expanded="false" aria-controls="mainMenu">
            <span></span>
        </button>

        <nav class="menu" id="mainMenu" role="navigation" aria-label="<?= $lang === 'fr' ? 'Navigation principale' : 'Main navigation' ?>">
            <a href="/news" <?= $uri === '/news' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_news')) ?></a>
            <a href="/services" <?= $uri === '/services' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_services')) ?></a>
            <a href="/pricing" <?= $uri === '/pricing' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_pricing')) ?></a>
            <a class="btn btn-gold" href="/intake/analytics"><?= $e(Lang::t('nav_cta')) ?></a>
            <span class="lang-switch">
                <a href="?lang=fr" class="<?= $lang === 'fr' ? 'on' : '' ?>" aria-label="Fran&ccedil;ais">FR</a>
                <a href="?lang=en" class="<?= $lang === 'en' ? 'on' : '' ?>" aria-label="English">EN</a>
            </span>
        </nav>
        <div class="menu-overlay" id="menuOverlay"></div>
    </div>
</header>

<?php if ($isHome): ?>
<div class="ticker" aria-label="<?= $e($lang === 'fr' ? 'Fil d\'actualité' : 'News ticker') ?>">
    <div class="container ticker-inner">
        <span class="ticker-label"><?= $e($lang === 'fr' ? 'Alerte' : 'Alert') ?></span>
        <span class="ticker-text"><?= $e($lang === 'fr'
            ? 'Surveillance active — Signaux RAM & maladies émergentes sur le continent africain'
            : 'Active surveillance — AMR & emerging disease signals across the African continent') ?></span>
    </div>
</div>
<?php endif; ?>

<main><?= $content ?></main>

<footer class="site-footer" role="contentinfo">
    <div class="container footer-grid">
        <div>
            <div class="brand footer-brand"><span class="brand-mark">ERID</span><span class="brand-sub">-AMRAfrica</span></div>
            <p class="footer-desc"><?= $e(Lang::t('footer_tagline')) ?></p>
        </div>
        <div>
            <h4>One Health Intelligence</h4>
            <a href="/news"><?= $e(Lang::t('nav_news')) ?></a>
            <a href="/services"><?= $e(Lang::t('nav_services')) ?></a>
            <a href="/pricing"><?= $e(Lang::t('nav_pricing')) ?></a>
        </div>
        <div>
            <h4><?= $e(Lang::t('footer_newsletter')) ?></h4>
            <form id="subForm" class="sub-form" aria-label="Newsletter">
                <?= \App\Core\Csrf::field() ?>
                <input type="email" name="email" placeholder="email@org.africa" required aria-label="Email">
                <button class="btn btn-teal" type="submit"><?= $e(Lang::t('subscribe')) ?></button>
            </form>
            <small class="muted" id="subMsg" aria-live="polite"></small>
        </div>
    </div>
    <div class="container copyright">&copy; <?= date('Y') ?> ERID-AMRAfrica &middot; <a href="/admin/login">Console</a></div>
</footer>
<script src="/assets/js/app.js"></script>
</body>
</html>
