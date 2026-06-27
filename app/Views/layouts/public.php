<?php
/** @var string $content @var string $title @var string $lang */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
?>
<!DOCTYPE html>
<html lang="<?= $e($lang) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title ?? 'ERID-AMRAfrica') ?></title>
    <meta name="description" content="<?= $e(Lang::t('meta_desc')) ?>">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="public">
<header class="site-header">
    <div class="container nav">
        <a class="brand" href="/">
            <span class="brand-mark">ERID</span><span class="brand-sub">-AMRAfrica</span>
        </a>
        <nav class="menu">
            <a href="/news"><?= $e(Lang::t('nav_news')) ?></a>
            <a href="/services"><?= $e(Lang::t('nav_services')) ?></a>
            <a href="/pricing"><?= $e(Lang::t('nav_pricing')) ?></a>
            <a class="btn btn-gold" href="/intake/analytics"><?= $e(Lang::t('nav_cta')) ?></a>
            <span class="lang-switch">
                <a href="?lang=fr" class="<?= $lang === 'fr' ? 'on' : '' ?>">FR</a>
                <a href="?lang=en" class="<?= $lang === 'en' ? 'on' : '' ?>">EN</a>
            </span>
        </nav>
    </div>
</header>

<main><?= $content ?></main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <div class="brand"><span class="brand-mark">ERID</span><span class="brand-sub">-AMRAfrica</span></div>
            <p class="muted"><?= $e(Lang::t('footer_tagline')) ?></p>
        </div>
        <div>
            <h4>One Health Intelligence</h4>
            <a href="/news"><?= $e(Lang::t('nav_news')) ?></a>
            <a href="/services"><?= $e(Lang::t('nav_services')) ?></a>
            <a href="/pricing"><?= $e(Lang::t('nav_pricing')) ?></a>
        </div>
        <div>
            <h4><?= $e(Lang::t('footer_newsletter')) ?></h4>
            <form id="subForm" class="sub-form">
                <?= \App\Core\Csrf::field() ?>
                <input type="email" name="email" placeholder="email@org.africa" required>
                <button class="btn btn-teal" type="submit"><?= $e(Lang::t('subscribe')) ?></button>
            </form>
            <small class="muted" id="subMsg"></small>
        </div>
    </div>
    <div class="container copyright">© <?= date('Y') ?> ERID-AMRAfrica · <a href="/admin/login">Console</a></div>
</footer>
<script src="/assets/js/app.js"></script>
</body>
</html>
