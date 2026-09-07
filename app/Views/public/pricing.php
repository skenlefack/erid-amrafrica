<?php
/** @var array $services */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
$lang = $_SESSION['locale'] ?? 'fr';
?>
<section class="section">
    <div class="container">
        <h1 class="page-title"><?= $e(Lang::t('pricing_title')) ?></h1>
        <p class="section-sub"><?= $e(Lang::t('pricing_sub')) ?></p>

        <!-- Engagement tiers -->
        <div class="grid-3 tiers">
            <div class="card tier">
                <div class="tier-icon">🔍</div>
                <h3><?= $e(Lang::t('engage_scoping')) ?></h3>
                <p class="tier-desc"><?= $e(Lang::t('engage_scoping_desc')) ?></p>
                <ul>
                    <li><?= $e(Lang::t('engage_scoping_1')) ?></li>
                    <li><?= $e(Lang::t('engage_scoping_2')) ?></li>
                    <li><?= $e(Lang::t('engage_scoping_3')) ?></li>
                </ul>
                <a class="btn btn-gold" href="/intake/advisory"><?= $e(Lang::t('engage_cta')) ?></a>
            </div>
            <div class="card tier featured">
                <div class="tier-icon">📋</div>
                <h3><?= $e(Lang::t('engage_project')) ?></h3>
                <p class="tier-desc"><?= $e(Lang::t('engage_project_desc')) ?></p>
                <ul>
                    <li><?= $e(Lang::t('engage_project_1')) ?></li>
                    <li><?= $e(Lang::t('engage_project_2')) ?></li>
                    <li><?= $e(Lang::t('engage_project_3')) ?></li>
                </ul>
                <a class="btn btn-gold" href="/intake/advisory"><?= $e(Lang::t('engage_cta')) ?></a>
            </div>
            <div class="card tier">
                <div class="tier-icon">🤝</div>
                <h3><?= $e(Lang::t('engage_strategic')) ?></h3>
                <p class="tier-desc"><?= $e(Lang::t('engage_strategic_desc')) ?></p>
                <ul>
                    <li><?= $e(Lang::t('engage_strategic_1')) ?></li>
                    <li><?= $e(Lang::t('engage_strategic_2')) ?></li>
                    <li><?= $e(Lang::t('engage_strategic_3')) ?></li>
                </ul>
                <a class="btn btn-teal" href="/intake/advisory"><?= $e(Lang::t('engage_cta')) ?></a>
            </div>
        </div>

        <!-- How to engage -->
        <h2 class="section-title mt"><?= $e(Lang::t('engage_how_title')) ?></h2>
        <div class="engage-steps">
            <div class="engage-step">
                <span class="engage-step__num">1</span>
                <p><?= $e(Lang::t('engage_step_1')) ?></p>
            </div>
            <div class="engage-step">
                <span class="engage-step__num">2</span>
                <p><?= $e(Lang::t('engage_step_2')) ?></p>
            </div>
            <div class="engage-step">
                <span class="engage-step__num">3</span>
                <p><?= $e(Lang::t('engage_step_3')) ?></p>
            </div>
        </div>

        <div style="text-align:center;margin-top:32px">
            <a class="btn btn-gold lg" href="/intake/advisory"><?= $e(Lang::t('engage_cta')) ?></a>
        </div>
    </div>
</section>
