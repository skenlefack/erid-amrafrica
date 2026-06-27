<?php
/** @var array $settings */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<p class="muted">Paramètres globaux du site public (bilingues).</p>
<div class="panel">
  <form method="post" action="/admin/settings">
    <?= Csrf::field() ?>
    <?php foreach ($settings as $s): ?>
      <div class="setting-row">
        <code><?= $e($s['setting_key']) ?></code>
        <div class="form-grid">
          <label>FR<textarea name="fr[<?= $e($s['setting_key']) ?>]" rows="2"><?= $e($s['value_fr']) ?></textarea></label>
          <label>EN<textarea name="en[<?= $e($s['setting_key']) ?>]" rows="2"><?= $e($s['value_en']) ?></textarea></label>
        </div>
      </div>
    <?php endforeach; ?>
    <button class="btn btn-gold lg" type="submit">Enregistrer</button>
  </form>
</div>
