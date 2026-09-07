<?php
/** @var string $content @var string $title */
use App\Core\Auth;
use App\Core\View;
$e = fn($s) => View::e($s);
$u = Auth::user();
$uri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
$isActive = fn($path) => $uri === $path ? 'active' : '';
$initials = implode('', array_map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)), explode(' ', $u['name'] ?? 'A')));

// Breadcrumb
$crumbs = [['Tableau de bord', '/admin']];
$segments = array_filter(explode('/', trim($uri, '/')));
array_shift($segments); // remove 'admin'
$breadcrumbLabels = [
    'articles' => 'Articles', 'media' => 'Médiathèque', 'publications' => 'Publications',
    'pages' => 'Pages', 'courses' => 'Classroom', 'leads' => 'Leads / CRM',
    'rumours' => 'Surveillance', 'subscribers' => 'Abonnés', 'users' => 'Utilisateurs',
    'services' => 'Services', 'email-templates' => 'Templates email', 'audit' => 'Audit',
    'settings' => 'Paramètres', 'new' => 'Nouveau', 'edit' => 'Éditer', 'export' => 'Export',
    'password' => 'Mot de passe',
];
$path = '/admin';
foreach ($segments as $seg) {
    $path .= '/' . $seg;
    $crumbs[] = [$breadcrumbLabels[$seg] ?? (is_numeric($seg) ? '#' . $seg : ucfirst($seg)), $path];
}

// Flash message
$flash = $_SESSION['_flash'] ?? null;
unset($_SESSION['_flash']);
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

<!-- Mobile toggle -->
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Menu">☰</button>

<aside class="sidebar" id="adminSidebar">
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
        <a href="/admin/courses" class="sidebar__link <?= str_starts_with($uri, '/admin/courses') ? 'active' : '' ?>">
            <span class="sidebar__icon">🎓</span> Classroom
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
        <?php if (($_SESSION['urole'] ?? '') === 'superadmin'): ?>
        <a href="/admin/users" class="sidebar__link <?= str_starts_with($uri, '/admin/users') ? 'active' : '' ?>">
            <span class="sidebar__icon">👤</span> Utilisateurs
        </a>
        <?php endif; ?>
        <a href="/admin/services" class="sidebar__link <?= str_starts_with($uri, '/admin/services') ? 'active' : '' ?>">
            <span class="sidebar__icon">🧩</span> Services
        </a>
        <a href="/admin/email-templates" class="sidebar__link <?= str_starts_with($uri, '/admin/email-templates') ? 'active' : '' ?>">
            <span class="sidebar__icon">✉️</span> Templates email
        </a>
        <a href="/admin/audit" class="sidebar__link <?= str_starts_with($uri, '/admin/audit') ? 'active' : '' ?>">
            <span class="sidebar__icon">🔍</span> Audit
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
            <!-- Breadcrumbs -->
            <nav class="breadcrumbs" aria-label="Fil d'Ariane">
                <?php foreach ($crumbs as $i => $c): ?>
                    <?php if ($i > 0): ?><span class="breadcrumbs__sep">/</span><?php endif; ?>
                    <?php if ($i === count($crumbs) - 1): ?>
                        <span class="breadcrumbs__current"><?= $e($c[0]) ?></span>
                    <?php else: ?>
                        <a href="<?= $e($c[1]) ?>" class="breadcrumbs__link"><?= $e($c[0]) ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
            <h1><?= $e($title ?? '') ?></h1>
        </div>
        <div class="admin-top__right">
            <a class="btn btn-ghost sm" href="/" target="_blank">🌐 Voir le site</a>
        </div>
    </header>

    <!-- Flash messages (toast) -->
    <?php if ($flash): ?>
    <div class="toast toast--<?= $e($flash['type'] ?? 'success') ?>" id="toastMsg">
        <?= $e($flash['message'] ?? '') ?>
    </div>
    <?php endif; ?>

    <div class="admin-content"><?= $content ?></div>
</div>

<script>
// Mobile sidebar toggle
document.getElementById('sidebarToggle')?.addEventListener('click', function() {
    document.getElementById('adminSidebar').classList.toggle('open');
});
// Auto-dismiss toast
const toast = document.getElementById('toastMsg');
if (toast) { setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 4000); }
</script>
</body>
</html>
