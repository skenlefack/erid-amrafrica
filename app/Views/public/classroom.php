<?php
/** @var array $courses @var array $categories @var ?string $activeCategory @var int $page @var int $totalPages */
use App\Core\View; use App\Core\Lang;
$e = fn($s) => View::e($s);
$lang = $_SESSION['locale'] ?? 'fr';
$pick = fn($row, $b) => Lang::pick($row, $b);
$levelLabels = [
    'beginner'     => $lang === 'fr' ? 'Débutant' : 'Beginner',
    'intermediate' => $lang === 'fr' ? 'Intermédiaire' : 'Intermediate',
    'advanced'     => $lang === 'fr' ? 'Avancé' : 'Advanced',
];
?>
<section class="section">
<div class="container">
    <h1 class="page-title"><?= $e($lang === 'fr' ? 'Académie de formation' : 'Training Academy') ?></h1>
    <p class="section-sub"><?= $e($lang === 'fr'
        ? 'Renforcez vos capacités One Health grâce à des formations expertes, webinaires et cours en ligne.'
        : 'Build One Health capacity through expert-led trainings, webinars, and online courses.') ?></p>

    <!-- Category filters -->
    <div class="filters" style="margin-bottom:24px">
        <a class="chip <?= !$activeCategory ? 'on' : '' ?>" href="/classroom"><?= $e($lang === 'fr' ? 'Toutes' : 'All') ?></a>
        <?php foreach ($categories as $cat): if (!$cat['category']) continue; ?>
            <a class="chip <?= $activeCategory === $cat['category'] ? 'on' : '' ?>" href="/classroom?category=<?= urlencode($cat['category']) ?>"><?= $e($cat['category']) ?></a>
        <?php endforeach; ?>
    </div>

    <?php if (!$courses): ?>
        <p class="muted"><?= $e($lang === 'fr' ? 'Aucune formation disponible pour le moment.' : 'No courses available at the moment.') ?></p>
    <?php else: ?>
    <div class="td-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px">
        <?php foreach ($courses as $c): ?>
        <div class="td-module" style="border-radius:8px;overflow:hidden;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,.08)">
            <?php if ($c['thumbnail']): ?>
                <div class="td-module__thumb"><img src="<?= $e($c['thumbnail']) ?>" alt="<?= $e($pick($c, 'title')) ?>" loading="lazy" style="width:100%;height:180px;object-fit:cover"></div>
            <?php else: ?>
                <div style="height:180px;background:linear-gradient(135deg,var(--navy),var(--accent));display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.5rem">🎓</div>
            <?php endif; ?>
            <div style="padding:16px">
                <?php if ($c['category']): ?>
                    <span class="badge" style="margin-bottom:8px"><?= $e($c['category']) ?></span>
                <?php endif; ?>
                <span class="badge" style="margin-bottom:8px;margin-left:4px"><?= $e($levelLabels[$c['level']] ?? $c['level']) ?></span>
                <h3 style="margin:8px 0 6px;font-size:1.05rem"><?= $e($pick($c, 'title')) ?></h3>
                <?php if ($c['instructor']): ?>
                    <p style="font-size:.85rem;color:var(--muted);margin:0 0 4px">👨‍🏫 <?= $e($c['instructor']) ?></p>
                <?php endif; ?>
                <?php if ($c['duration']): ?>
                    <p style="font-size:.85rem;color:var(--muted);margin:0 0 4px">⏱ <?= $e($c['duration']) ?></p>
                <?php endif; ?>
                <?php if ($c['schedule']): ?>
                    <p style="font-size:.85rem;color:var(--muted);margin:0 0 8px">📅 <?= $e($c['schedule']) ?></p>
                <?php endif; ?>
                <div style="display:flex;gap:8px;margin-top:12px">
                    <a class="btn btn-gold sm" href="/classroom/<?= (int)$c['id'] ?>"><?= $e($lang === 'fr' ? 'Détails' : 'Details') ?></a>
                    <?php if ($c['registration_url']): ?>
                        <a class="btn btn-teal sm" href="<?= $e($c['registration_url']) ?>" target="_blank"><?= $e($lang === 'fr' ? 'S\'inscrire' : 'Register') ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <?php $qs = fn($p) => '/classroom?' . http_build_query(array_filter(['category' => $activeCategory, 'page' => $p])); ?>
    <div style="display:flex;gap:8px;justify-content:center;margin-top:30px">
        <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="<?= $qs($page - 1) ?>"><?= $e(Lang::t('prev')) ?></a><?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="<?= $qs($i) ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="<?= $qs($page + 1) ?>"><?= $e(Lang::t('next')) ?></a><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
</section>
