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
    <meta property="og:title" content="<?= $e($title ?? 'ERID-AMRAfrica') ?>">
    <meta property="og:description" content="<?= $e(Lang::t('meta_desc')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ERID-AMRAfrica">
    <?php if (!empty($ogImage)): ?><meta property="og:image" content="<?= $e($ogImage) ?>"><?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Source+Sans+3:wght@400;500;600;700;800&display=swap">
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
            <a class="top-bar__btn" href="/intake/analytics"><?= $e(Lang::t('nav_cta')) ?></a>
            <span class="top-bar__sep">|</span>
            <a class="top-bar__link" href="/admin/login"><?= $e($lang === 'fr' ? 'Connexion' : 'Login') ?></a>
            <a class="top-bar__link top-bar__link--accent" href="/intake/analytics"><?= $e($lang === 'fr' ? 'Inscription' : 'Sign up') ?></a>
            <span class="top-bar__sep">|</span>
            <span class="lang-switch">
                <a href="?lang=fr" class="<?= $lang === 'fr' ? 'on' : '' ?>">FR</a>
                <a href="?lang=en" class="<?= $lang === 'en' ? 'on' : '' ?>">EN</a>
            </span>
        </div>
    </div>
</div>

<!-- HEADER — logo left + banner right -->
<header class="site-header" role="banner">
    <div class="container header__inner">
        <button class="menu-toggle" id="menuToggle" aria-label="<?= $lang === 'fr' ? 'Menu' : 'Menu' ?>" aria-expanded="false" aria-controls="mainMenu">
            <span></span>
        </button>
        <a class="brand" href="/" aria-label="ERID-AMRAfrica">
            <img src="/assets/logo.png" alt="ERID-AMRAfrica — One Health Intelligence Afrique" class="brand-logo">
        </a>
        <div class="header-banner">
            <div class="header-banner__inner">
                <span class="header-banner__label">Ad</span>
                <div class="header-banner__content">
                    <strong>ERID-AMRAfrica Intelligence</strong>
                    <span><?= $e($lang === 'fr' ? 'Abonnez-vous aux alertes RAM en temps réel' : 'Subscribe to real-time AMR alerts') ?></span>
                </div>
                <a class="btn btn-accent sm" href="/pricing"><?= $e(Lang::t('subscribe')) ?></a>
            </div>
        </div>
    </div>
</header>

<!-- NAV BAR -->
<nav class="nav-bar" role="navigation" aria-label="<?= $lang === 'fr' ? 'Navigation principale' : 'Main navigation' ?>">
    <div class="container nav-bar__inner">
        <div class="menu" id="mainMenu">
            <a href="/" <?= $isHome ? 'class="active"' : '' ?>><?= $e($lang === 'fr' ? 'Accueil' : 'Home') ?></a>
            <a href="/news" <?= $uri === '/news' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_news')) ?></a>
            <a href="/services" <?= $uri === '/services' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_services')) ?></a>
            <a href="/media" <?= $uri === '/media' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_media')) ?></a>
            <a href="/publications" <?= $uri === '/publications' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_publications')) ?></a>
            <a href="/pricing" <?= $uri === '/pricing' ? 'class="active"' : '' ?>><?= $e(Lang::t('nav_pricing')) ?></a>
            <a href="/intake/advisory"><?= $e(Lang::t('cta_band_btn')) ?></a>
        </div>
        <div class="menu-overlay" id="menuOverlay"></div>
    </div>
</nav>

<?php if ($isHome): ?>
<div class="ticker">
    <div class="ticker__inner">
        <span class="ticker__label"><?= $e($lang === 'fr' ? 'Alerte' : 'Alert') ?></span>
        <div class="ticker__scroll">
            <span class="ticker__text"><?= $e($lang === 'fr'
                ? 'Surveillance active — Signaux RAM & maladies infectieuses émergentes sur le continent africain — Veille épidémiologique continue — Réseau continental One Health — Alertes en temps réel'
                : 'Active surveillance — AMR & emerging infectious disease signals across the African continent — Continuous epidemiological monitoring — Continental One Health network — Real-time alerts') ?></span>
        </div>
    </div>
</div>
<?php endif; ?>

<main class="site-main"><?= $content ?></main>

<!-- PRE-FOOTER CTA -->
<div class="pre-footer">
    <div class="container pre-footer__inner">
        <div class="pre-footer__icon">🌍</div>
        <div class="pre-footer__text">
            <h3><?= $e($lang === 'fr' ? 'Prêt à renforcer votre capacité One Health ?' : 'Ready to strengthen your One Health capacity?') ?></h3>
            <p><?= $e($lang === 'fr' ? 'Nos consultants sont disponibles pour accompagner votre institution dans la lutte contre la RAM et les maladies émergentes.' : 'Our consultants are ready to support your institution in the fight against AMR and emerging diseases.') ?></p>
        </div>
        <div class="pre-footer__actions">
            <a class="btn btn-gold lg" href="/intake/advisory"><?= $e(Lang::t('hero_cta')) ?></a>
            <a class="btn btn-outline lg" href="/pricing"><?= $e(Lang::t('nav_pricing')) ?></a>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="site-footer" role="contentinfo">
    <div class="footer-wave"></div>
    <div class="footer-top">
        <div class="container footer-grid">
            <div class="footer-col footer-col--brand">
                <div class="brand footer-brand"><img src="/assets/logo.png" alt="ERID-AMRAfrica" class="footer-logo"></div>
                <p class="footer-desc"><?= $e(Lang::t('footer_tagline')) ?></p>
                <div class="footer-social">
                    <a href="https://www.youtube.com/@ERID-AMRAfrica" target="_blank" rel="noopener" title="YouTube" class="fs-youtube">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31.5 31.5 0 0 0 0 12a31.5 31.5 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31.5 31.5 0 0 0 24 12a31.5 31.5 0 0 0-.5-5.8zM9.5 15.5V8.5l6.3 3.5-6.3 3.5z"/></svg>
                    </a>
                    <a href="https://x.com/eridamrafrica" target="_blank" rel="noopener" title="X" class="fs-x">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.2 2.25h3.51l-7.67 8.77 9.02 11.92h-7.06l-5.54-7.24-6.34 7.24H.61l8.2-9.38L.2 2.25h7.24l5.01 6.62 5.75-6.62zm-1.23 18.56h1.94L7.16 4.23H5.08l11.89 16.58z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/company/erid-amrafrica" target="_blank" rel="noopener" title="LinkedIn" class="fs-linkedin">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.36V9h3.41v1.56h.05a3.74 3.74 0 0 1 3.37-1.85c3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77A1.75 1.75 0 0 0 0 1.73v20.54A1.75 1.75 0 0 0 1.77 24h20.45A1.75 1.75 0 0 0 24 22.27V1.73A1.75 1.75 0 0 0 22.22 0z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/eridamrafrica" target="_blank" rel="noopener" title="Facebook" class="fs-facebook">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.07C24 5.41 18.63 0 12 0S0 5.41 0 12.07c0 6.02 4.39 11.01 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.95.93-1.95 1.88v2.26h3.33l-.53 3.49h-2.8v8.44C19.61 23.08 24 18.09 24 12.07z"/></svg>
                    </a>
                </div>
            </div>
            <div class="footer-col">
                <h4><?= $e($lang === 'fr' ? 'Rubriques' : 'Sections') ?></h4>
                <a href="/news"><?= $e(Lang::t('nav_news')) ?></a>
                <a href="/services"><?= $e(Lang::t('nav_services')) ?></a>
                <a href="/media"><?= $e(Lang::t('nav_media')) ?></a>
                <a href="/publications"><?= $e(Lang::t('nav_publications')) ?></a>
                <a href="/pricing"><?= $e(Lang::t('nav_pricing')) ?></a>
            </div>
            <div class="footer-col">
                <h4><?= $e($lang === 'fr' ? 'Services' : 'Services') ?></h4>
                <a href="/intake/quant"><?= $e($lang === 'fr' ? 'Data Science & Épidémiologie' : 'Data Science & Epidemiology') ?></a>
                <a href="/intake/qual"><?= $e($lang === 'fr' ? 'Intelligence comportementale' : 'Behavioral Intelligence') ?></a>
                <a href="/intake/systems"><?= $e($lang === 'fr' ? 'Simulation & Systèmes' : 'Simulation & Systems') ?></a>
                <a href="/intake/advisory"><?= $e(Lang::t('cta_band_btn')) ?></a>
            </div>
            <div class="footer-col">
                <h4><?= $e($lang === 'fr' ? 'Plateforme' : 'Platform') ?></h4>
                <a href="/admin/login"><?= $e($lang === 'fr' ? 'Console admin' : 'Admin console') ?></a>
                <a href="/intake/analytics"><?= $e(Lang::t('nav_cta')) ?></a>
                <a href="/page/vision-mission"><?= $e($lang === 'fr' ? 'Vision & Mission' : 'Vision & Mission') ?></a>
            </div>
            <div class="footer-col footer-col--newsletter">
                <h4><?= $e(Lang::t('footer_newsletter')) ?></h4>
                <p class="footer-desc"><?= $e($lang === 'fr' ? 'Recevez notre veille hebdomadaire sur la RAM et les maladies émergentes en Afrique.' : 'Get our weekly AMR and emerging disease intelligence briefing for Africa.') ?></p>
                <form id="subForm" class="sub-form" aria-label="Newsletter">
                    <?= \App\Core\Csrf::field() ?>
                    <input type="email" name="email" placeholder="email@org.africa" required aria-label="Email">
                    <button class="btn btn-gold sm" type="submit"><?= $e(Lang::t('subscribe')) ?></button>
                </form>
                <small class="muted" id="subMsg" aria-live="polite"></small>
            </div>
        </div>
    </div>
    <div class="footer-mid">
        <div class="container footer-partners">
            <span class="footer-partners__label"><?= $e($lang === 'fr' ? 'Réseau & Partenaires' : 'Network & Partners') ?></span>
            <div class="footer-partners__list">
                <span>Africa CDC</span><span>WHO AFRO</span><span>AU-IBAR</span><span>FAO</span><span>Wellcome</span><span>Institut Pasteur</span><span>KEMRI</span><span>GARDP</span>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container footer-bottom__inner">
            <span>&copy; <?= date('Y') ?> ERID-AMRAfrica. <?= $e($lang === 'fr' ? 'Tous droits réservés.' : 'All rights reserved.') ?></span>
            <span class="footer-bottom__links">
                <a href="/page/vision-mission"><?= $e($lang === 'fr' ? 'À propos' : 'About') ?></a>
                <a href="/intake/advisory"><?= $e($lang === 'fr' ? 'Contact' : 'Contact') ?></a>
                <a href="/admin/login">Console</a>
            </span>
            <span class="footer-bottom__brand"><img src="/assets/logo.png" alt="ERID-AMRAfrica" class="footer-bottom__logo"></span>
        </div>
    </div>
</footer>
<script src="/assets/js/app.js"></script>
</body>
</html>
