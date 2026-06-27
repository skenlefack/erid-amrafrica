<?php
/** @var string $type */
use App\Core\Lang;
use App\Core\View;
$e = fn($s) => View::e($s);
?>
<section class="section narrow center">
  <div class="container">
    <div class="success-badge">✓</div>
    <h1 class="page-title"><?= $e(Lang::t('success_title')) ?></h1>
    <p class="lead"><?= $e(Lang::t('success_body')) ?></p>
    <p class="muted"><?= $e(Lang::t('success_routed')) ?> <code><?= $e($type) ?></code></p>
    <a class="btn btn-teal" href="/">← <?= $e(Lang::t('back_home')) ?></a>
  </div>
</section>
