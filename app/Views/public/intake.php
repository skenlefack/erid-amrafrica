<?php
/** @var string $pillar @var string $type @var ?array $service */
use App\Core\Lang;
use App\Core\View;
use App\Core\Csrf;
$e = fn($s) => View::e($s);
$pick = fn($row, $b) => \App\Core\Lang::pick($row, $b);
$isSystems = $pillar === 'systems';
$isQuant   = $pillar === 'quant';
?>
<section class="section">
    <div class="container narrow">
        <a class="back" href="/services">← <?= $e(Lang::t('nav_services')) ?></a>
        <h1 class="page-title"><?= $e(Lang::t('intake_title')) ?></h1>
        <?php if ($service): ?>
            <p class="lead"><?= $e($pick($service, 'title')) ?></p>
        <?php endif; ?>
        <p class="muted small"><?= $e(Lang::t('intake_routing')) ?> <code><?= $e($type) ?></code></p>

        <form class="intake-form" method="post" action="/intake" enctype="multipart/form-data">
            <?= Csrf::field() ?>
            <input type="hidden" name="pillar" value="<?= $e($pillar) ?>">

            <div class="form-grid">
                <label><?= $e(Lang::t('f_lead')) ?> *
                    <input type="text" name="lead_name" required></label>
                <label><?= $e(Lang::t('f_org')) ?> *
                    <input type="text" name="organisation" required></label>
                <label><?= $e(Lang::t('f_email')) ?> *
                    <input type="email" name="email" required></label>
                <label><?= $e(Lang::t('f_phone')) ?>
                    <input type="text" name="phone" placeholder="+xxx ... (WhatsApp)"></label>
            </div>

            <label><?= $e(Lang::t('f_project')) ?> *
                <input type="text" name="project_title" required></label>
            <label><?= $e(Lang::t('f_desc')) ?> *
                <textarea name="description" rows="5" required></textarea></label>

            <?php if ($isQuant): ?>
            <label><?= $e(Lang::t('f_dap')) ?>
                <textarea name="dap" rows="3"></textarea></label>
            <?php endif; ?>

            <?php if ($isSystems): ?>
            <fieldset class="sectors">
                <legend><?= $e(Lang::t('f_sectors')) ?></legend>
                <?php foreach (['human','animal','environment','agriculture','pharma'] as $sec): ?>
                    <label class="chk"><input type="checkbox" name="sectors[]" value="<?= $sec ?>"> <?= $e(Lang::t('sector_' . $sec)) ?></label>
                <?php endforeach; ?>
            </fieldset>
            <?php endif; ?>

            <div class="form-grid">
                <label><?= $e(Lang::t('f_timeline')) ?>
                    <input type="date" name="timeline"></label>
                <label><?= $e(Lang::t('f_upload')) ?>
                    <input type="file" name="upload" accept=".csv,.xls,.xlsx,.pdf,.doc,.docx"></label>
            </div>

            <button class="btn btn-gold lg" type="submit"><?= $e(Lang::t('submit_request')) ?></button>
            <p class="muted small"><?= $e(Lang::t('intake_privacy')) ?></p>
        </form>
    </div>
</section>
