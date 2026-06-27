<?php
/** @var array $services */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
$labels = [
    'quant'   => ['icon' => '📈', 'tag' => 'Pillar A'],
    'qual'    => ['icon' => '🧠', 'tag' => 'Pillar B'],
    'systems' => ['icon' => '🔄', 'tag' => 'Pillar C'],
];
?>
<section class="section">
    <div class="container">
        <h1 class="page-title"><?= $e(Lang::t('services_title')) ?></h1>
        <p class="section-sub"><?= $e(Lang::t('services_sub')) ?></p>

        <?php foreach ($services as $s): $L = $labels[$s['pillar']] ?? ['icon' => '◆', 'tag' => '']; ?>
        <div class="service-row pillar-<?= $e($s['pillar']) ?>">
            <div class="service-icon"><?= $L['icon'] ?></div>
            <div class="service-body">
                <span class="tag"><?= $e($L['tag']) ?> · <?= $e($s['routing_tag']) ?></span>
                <h2><?= $e($pick($s, 'title')) ?></h2>
                <p><?= $e($pick($s, 'summary')) ?></p>
                <div class="service-meta">
                    <?php if ($s['price_from_usd']): ?>
                        <span class="price"><?= $e(Lang::t('from')) ?> $<?= number_format((float)$s['price_from_usd']) ?>
                        · <?= $e(Lang::t('model_' . $s['price_model'])) ?></span>
                    <?php endif; ?>
                    <a class="btn btn-gold" href="/intake/<?= $e($s['pillar']) ?>"><?= $e(Lang::t('request')) ?></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
