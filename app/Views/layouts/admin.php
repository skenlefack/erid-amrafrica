<?php
/** @var string $content @var string $title */
use App\Core\Auth;
use App\Core\View;
$e = fn($s) => View::e($s);
$u = Auth::user();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($title ?? 'Console') ?> · ERID-AMRAfrica</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="admin">
<aside class="sidebar">
    <div class="brand brand-light"><span class="brand-mark">ERID</span><span class="brand-sub">Console</span></div>
    <nav>
        <a href="/admin">📊 Tableau de bord</a>
        <a href="/admin/leads">💼 Leads / CRM</a>
        <a href="/admin/rumours">📡 Surveillance</a>
        <a href="/admin/articles">📰 Articles</a>
        <a href="/admin/services">🧩 Services & Tarifs</a>
        <a href="/admin/settings">⚙️ Paramètres</a>
    </nav>
    <div class="sidebar-foot">
        <small><?= $e($u['name'] ?? '') ?> · <?= $e($u['role'] ?? '') ?></small>
        <a class="logout" href="/admin/logout">Déconnexion</a>
    </div>
</aside>
<div class="admin-main">
    <header class="admin-top">
        <h1><?= $e($title ?? '') ?></h1>
        <a class="btn btn-ghost" href="/" target="_blank">Voir le site ↗</a>
    </header>
    <div class="admin-content"><?= $content ?></div>
</div>
</body>
</html>
