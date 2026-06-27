<?php
/** @var array $services */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => Lang::pick($row, $b);
?>
<section class="section">
    <div class="container">
        <h1 class="page-title"><?= $e(Lang::t('pricing_title')) ?></h1>
        <p class="section-sub"><?= $e(Lang::t('pricing_sub')) ?></p>

        <!-- Niveaux d'abonnement aux produits de données (revenu récurrent) -->
        <div class="grid-3 tiers">
            <div class="card tier">
                <h3><?= $e(Lang::t('tier_free')) ?></h3>
                <p class="price-big">$0</p>
                <ul>
                    <li><?= $e(Lang::t('tier_free_1')) ?></li>
                    <li><?= $e(Lang::t('tier_free_2')) ?></li>
                </ul>
                <a class="btn btn-outline" href="/#sub"><?= $e(Lang::t('subscribe')) ?></a>
            </div>
            <div class="card tier featured">
                <span class="ribbon"><?= $e(Lang::t('popular')) ?></span>
                <h3><?= $e(Lang::t('tier_intel')) ?></h3>
                <p class="price-big">$490<small>/<?= $e(Lang::t('month')) ?></small></p>
                <ul>
                    <li><?= $e(Lang::t('tier_intel_1')) ?></li>
                    <li><?= $e(Lang::t('tier_intel_2')) ?></li>
                    <li><?= $e(Lang::t('tier_intel_3')) ?></li>
                </ul>
                <a class="btn btn-gold" href="/intake/analytics"><?= $e(Lang::t('contact_sales')) ?></a>
            </div>
            <div class="card tier">
                <h3><?= $e(Lang::t('tier_ent')) ?></h3>
                <p class="price-big"><?= $e(Lang::t('custom')) ?></p>
                <ul>
                    <li><?= $e(Lang::t('tier_ent_1')) ?></li>
                    <li><?= $e(Lang::t('tier_ent_2')) ?></li>
                    <li><?= $e(Lang::t('tier_ent_3')) ?></li>
                </ul>
                <a class="btn btn-teal" href="/intake/advisory"><?= $e(Lang::t('contact_sales')) ?></a>
            </div>
        </div>

        <h2 class="section-title mt"><?= $e(Lang::t('consulting_packages')) ?></h2>
        <table class="price-table">
            <thead><tr>
                <th><?= $e(Lang::t('service')) ?></th>
                <th><?= $e(Lang::t('model')) ?></th>
                <th><?= $e(Lang::t('from')) ?></th>
                <th></th>
            </tr></thead>
            <tbody>
            <?php foreach ($services as $s): ?>
                <tr>
                    <td><?= $e($pick($s, 'title')) ?></td>
                    <td><?= $e(Lang::t('model_' . $s['price_model'])) ?></td>
                    <td><?= $s['price_from_usd'] ? '$' . number_format((float)$s['price_from_usd']) : '—' ?></td>
                    <td><a class="btn btn-teal sm" href="/intake/<?= $e($s['pillar']) ?>"><?= $e(Lang::t('request')) ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
