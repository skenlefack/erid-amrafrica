<?php
/** @var string $content @var string $title */
use App\Core\Auth;
use App\Core\View;
$e = fn($s) => View::e($s);
$u = Auth::user();
$uri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
$isActive = fn($path) => $uri === $path ? 'active' : '';
$initials = implode('', array_map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)), explode(' ', $u['name'] ?? 'A')));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title ?? 'Console') ?> · ERID-AMRAfrica</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Source+Sans+3:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="/assets/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body class="admin">
<aside class="sidebar">
    <div class="sidebar__logo">
        <a href="/admin"><img src="/assets/logo.png" alt="ERID-AMRAfrica" class="sidebar__logo-img"></a>
    </div>

    <nav class="sidebar__nav">
        <a href="/admin" class="sidebar__link <?= $isActive('/admin') ?>">
            <span class="sidebar__icon">📊</span> Tableau de bord
        </a>

        <div class="sidebar__section">Contenu</div>
        <a href="/admin/articles" class="sidebar__link <?= str_starts_with($uri, '/admin/articles') ? 'active' : '' ?>">
            <span class="sidebar__icon">📰</span> Articles
        </a>
        <a href="/admin/media" class="sidebar__link <?= str_starts_with($uri, '/admin/media') ? 'active' : '' ?>">
            <span class="sidebar__icon">🎬</span> Médiathèque
        </a>
        <a href="/admin/publications" class="sidebar__link <?= str_starts_with($uri, '/admin/publications') ? 'active' : '' ?>">
            <span class="sidebar__icon">📚</span> Publications
        </a>
        <a href="/admin/pages" class="sidebar__link <?= str_starts_with($uri, '/admin/pages') ? 'active' : '' ?>">
            <span class="sidebar__icon">📄</span> Pages
        </a>

        <div class="sidebar__section">CRM & Surveillance</div>
        <a href="/admin/leads" class="sidebar__link <?= str_starts_with($uri, '/admin/leads') ? 'active' : '' ?>">
            <span class="sidebar__icon">💼</span> Leads / CRM
        </a>
        <a href="/admin/rumours" class="sidebar__link <?= str_starts_with($uri, '/admin/rumours') ? 'active' : '' ?>">
            <span class="sidebar__icon">📡</span> Surveillance
        </a>
        <a href="/admin/subscribers" class="sidebar__link <?= str_starts_with($uri, '/admin/subscribers') ? 'active' : '' ?>">
            <span class="sidebar__icon">👥</span> Abonnés
        </a>

        <div class="sidebar__section">Système</div>
        <a href="/admin/services" class="sidebar__link <?= str_starts_with($uri, '/admin/services') ? 'active' : '' ?>">
            <span class="sidebar__icon">🧩</span> Services & Tarifs
        </a>
        <a href="/admin/email-templates" class="sidebar__link <?= str_starts_with($uri, '/admin/email-templates') ? 'active' : '' ?>">
            <span class="sidebar__icon">✉️</span> Templates email
        </a>
        <a href="/admin/audit" class="sidebar__link <?= str_starts_with($uri, '/admin/audit') ? 'active' : '' ?>">
            <span class="sidebar__icon">🔍</span> Journal d'audit
        </a>
        <a href="/admin/settings" class="sidebar__link <?= str_starts_with($uri, '/admin/settings') ? 'active' : '' ?>">
            <span class="sidebar__icon">⚙️</span> Paramètres
        </a>
    </nav>

    <div class="sidebar__user">
        <div class="sidebar__avatar"><?= $e(mb_substr($initials, 0, 2)) ?></div>
        <div class="sidebar__user-info">
            <strong><?= $e($u['name'] ?? '') ?></strong>
            <small><?= $e(ucfirst($u['role'] ?? '')) ?></small>
        </div>
    </div>
    <div class="sidebar__foot">
        <a href="/admin/password">🔑 Mot de passe</a>
        <a href="/admin/logout">Déconnexion</a>
    </div>
</aside>

<div class="admin-main">
    <header class="admin-top">
        <div class="admin-top__left">
            <h1><?= $e($title ?? '') ?></h1>
        </div>
        <div class="admin-top__right">
            <a class="btn btn-ghost sm" href="/" target="_blank">🌐 Voir le site</a>
        </div>
    </header>
    <div class="admin-content"><?= $content ?></div>
</div>
</body>
</html>
