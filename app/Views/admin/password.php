<?php
/** @var string|null $error @var string|null $success */
use App\Core\View; use App\Core\Csrf; use App\Core\Lang;
$e = fn($s) => View::e($s);
?>
<div class="panel" style="max-width:480px">
  <?php if (!empty($error)): ?><div class="alert alert-error"><?= $e($error) ?></div><?php endif; ?>
  <?php if (!empty($success)): ?><div class="alert alert-success"><?= $e($success) ?></div><?php endif; ?>
  <form method="post" action="/admin/password">
    <?= Csrf::field() ?>
    <label><?= $e(Lang::t('current_password')) ?> *
      <input type="password" name="current_password" required>
    </label>
    <label><?= $e(Lang::t('new_password')) ?> * <small class="muted">(min. 8 caract&egrave;res)</small>
      <input type="password" name="new_password" required minlength="8">
    </label>
    <label><?= $e(Lang::t('confirm_password')) ?> *
      <input type="password" name="confirm_password" required minlength="8">
    </label>
    <button class="btn btn-gold lg" type="submit"><?= $e(Lang::t('password_title')) ?></button>
  </form>
</div>
