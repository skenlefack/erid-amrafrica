<?php
/** @var array $page */
use App\Core\View; use App\Core\Lang;
$e = fn($s) => View::e($s);
$lang = $_SESSION['locale'] ?? 'fr';
?>
<div class="container" style="padding:40px 0;max-width:800px;margin:0 auto">
  <h1 class="section-title"><?= $e(Lang::pick($page, 'title')) ?></h1>
  <div class="page-body" style="line-height:1.8;font-size:1.05rem">
    <?= nl2br($e(Lang::pick($page, 'body'))) ?>
  </div>
</div>
