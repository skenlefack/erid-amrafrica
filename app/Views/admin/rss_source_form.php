<?php
/** @var ?array $source */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$s = $source ?? [];
$isEdit = !empty($s);
?>
<a class="back" href="/admin/rss-sources">&larr; Sources RSS</a>
<div class="panel" style="max-width:600px">
  <h2><?= $isEdit ? 'Éditer la source RSS' : 'Nouvelle source RSS' ?></h2>
  <form method="post" action="<?= $isEdit ? '/admin/rss-sources/' . (int)$s['id'] : '/admin/rss-sources' ?>">
    <?= Csrf::field() ?>

    <label>Nom *<input type="text" name="name" required value="<?= $e($s['name'] ?? '') ?>" placeholder="WHO Disease Outbreak News"></label>

    <label style="margin-top:12px">URL du flux *<input type="url" name="url" required value="<?= $e($s['url'] ?? '') ?>" placeholder="https://www.who.int/feeds/entity/don/en/rss.xml"></label>

    <div class="form-grid" style="margin-top:12px">
      <label>Type de source
        <select name="source_type">
          <?php foreach (['rss', 'atom', 'custom'] as $t): ?>
            <option value="<?= $t ?>" <?= ($s['source_type'] ?? 'rss') === $t ? 'selected' : '' ?>><?= strtoupper($t) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Intervalle de polling (minutes)<input type="number" name="poll_interval" min="30" value="<?= (int)($s['poll_interval'] ?? 360) ?>"></label>
    </div>

    <label style="margin-top:12px;display:flex;align-items:center;gap:8px">
      <input type="checkbox" name="is_active" value="1" <?= ($s['is_active'] ?? 1) ? 'checked' : '' ?>> Source active
    </label>

    <button class="btn btn-gold full" type="submit" style="margin-top:20px"><?= $isEdit ? 'Mettre à jour' : 'Créer la source' ?></button>
  </form>
</div>
