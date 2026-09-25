<?php
/** @var string $q @var array $results */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$lang = $_SESSION['locale'] ?? 'fr';
?>
<section class="section">
<div class="container" style="padding-top:30px;padding-bottom:48px;max-width:900px">
    <h1 class="page-title"><?= $e($lang === 'fr' ? 'Recherche' : 'Search') ?></h1>
    <form method="get" action="/search" class="search-form-page" style="margin-bottom:32px">
        <div style="display:flex;gap:8px">
            <input type="search" name="q" value="<?= $e($q) ?>" placeholder="<?= $e($lang === 'fr' ? 'Rechercher des articles, médias, services...' : 'Search articles, media, services...') ?>" style="flex:1;padding:12px 16px;font-size:16px;border:2px solid var(--border);border-radius:var(--radius)" autofocus>
            <button class="btn btn-gold" type="submit"><?= $e($lang === 'fr' ? 'Rechercher' : 'Search') ?></button>
        </div>
    </form>

    <?php if ($q && $results): ?>
    <p class="muted" style="margin-bottom:20px"><?= count($results) ?> <?= $e($lang === 'fr' ? 'résultat(s) pour' : 'result(s) for') ?> « <?= $e($q) ?> »</p>
    <?php foreach ($results as $r): ?>
    <a href="<?= $e($r['url']) ?>" class="search-result" style="display:flex;gap:16px;padding:16px;border-bottom:1px solid var(--border);text-decoration:none;color:inherit;transition:background .15s" onmouseenter="this.style.background='var(--bg-alt)'" onmouseleave="this.style.background='transparent'">
        <?php if ($r['image']): ?>
        <div style="width:100px;height:70px;border-radius:var(--radius);background:center/cover no-repeat url('<?= $e($r['image']) ?>');flex-shrink:0"></div>
        <?php endif; ?>
        <div style="flex:1;min-width:0">
            <span class="cat-badge cat-badge--sm" style="background:<?= $e($r['color']) ?>;margin-bottom:4px"><?= $e($r['cat'] ?? $r['type']) ?></span>
            <h3 style="margin:4px 0;font-size:17px"><?= $e($r['title']) ?></h3>
            <p class="muted" style="font-size:13px;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= $e(mb_substr($r['desc'] ?? '', 0, 150)) ?></p>
        </div>
    </a>
    <?php endforeach; ?>

    <?php elseif ($q): ?>
    <div class="empty-state"><p><?= $e($lang === 'fr' ? 'Aucun résultat pour « ' . $q . ' ».' : 'No results for "' . $q . '".') ?></p></div>
    <?php endif; ?>
</div>
</section>
