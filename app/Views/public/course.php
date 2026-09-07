<?php
/** @var array $course */
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
<div class="container" style="max-width:800px">
    <a class="back" href="/classroom">← <?= $e($lang === 'fr' ? 'Académie' : 'Academy') ?></a>

    <?php if ($course['thumbnail']): ?>
        <img src="<?= $e($course['thumbnail']) ?>" alt="<?= $e($pick($course, 'title')) ?>" style="width:100%;max-height:360px;object-fit:cover;border-radius:10px;margin-bottom:24px">
    <?php endif; ?>

    <h1 style="font-family:var(--font-d);font-size:1.8rem;margin-bottom:12px"><?= $e($pick($course, 'title')) ?></h1>

    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:24px">
        <?php if ($course['category']): ?><span class="badge"><?= $e($course['category']) ?></span><?php endif; ?>
        <span class="badge"><?= $e($levelLabels[$course['level']] ?? $course['level']) ?></span>
        <?php if ($course['duration']): ?><span style="font-size:.9rem;color:var(--muted)">⏱ <?= $e($course['duration']) ?></span><?php endif; ?>
        <?php if ($course['schedule']): ?><span style="font-size:.9rem;color:var(--muted)">📅 <?= $e($course['schedule']) ?></span><?php endif; ?>
    </div>

    <?php if ($course['instructor']): ?>
        <p style="font-size:.95rem;margin-bottom:20px"><strong><?= $e($lang === 'fr' ? 'Formateur :' : 'Instructor:') ?></strong> <?= $e($course['instructor']) ?></p>
    <?php endif; ?>

    <div class="article-body" style="line-height:1.8;margin-bottom:32px">
        <?= nl2br($e($pick($course, 'description'))) ?>
    </div>

    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <?php if ($course['registration_url']): ?>
            <a class="btn btn-gold lg" href="<?= $e($course['registration_url']) ?>" target="_blank"><?= $e($lang === 'fr' ? 'S\'inscrire à cette formation' : 'Register for this course') ?></a>
        <?php endif; ?>
        <?php if ($course['materials_file']): ?>
            <a class="btn btn-outline lg" href="<?= $e($course['materials_file']) ?>" download><?= $e($lang === 'fr' ? 'Télécharger les supports' : 'Download materials') ?></a>
        <?php endif; ?>
    </div>
</div>
</section>
