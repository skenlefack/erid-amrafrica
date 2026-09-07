<?php
/** @var ?array $service */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$s = $service ?? [];
$isEdit = !empty($s);
?>
<a class="back" href="/admin/services">← Services</a>
<div class="panel" style="max-width:640px">
  <h2><?= $isEdit ? 'Éditer le service' : 'Nouveau service' ?></h2>
  <form method="post" action="<?= $isEdit ? '/admin/services/' . (int)$s['id'] : '/admin/services' ?>">
    <?= Csrf::field() ?>
    <div class="form-grid">
      <label>Pilier *
        <select name="pillar" required>
          <option value="quant" <?= ($s['pillar'] ?? '') === 'quant' ? 'selected' : '' ?>>Pillar A — Quantitative</option>
          <option value="qual" <?= ($s['pillar'] ?? '') === 'qual' ? 'selected' : '' ?>>Pillar B — Qualitative</option>
          <option value="systems" <?= ($s['pillar'] ?? '') === 'systems' ? 'selected' : '' ?>>Pillar C — Systems</option>
        </select>
      </label>
      <label>Tag de routage *
        <input type="text" name="routing_tag" required value="<?= $e($s['routing_tag'] ?? 'Service_Custom') ?>" placeholder="Service_Quant"></label>
    </div>
    <div class="form-grid">
      <label>Titre (FR) *<input type="text" name="title_fr" required value="<?= $e($s['title_fr'] ?? '') ?>"></label>
      <label>Title (EN) *<input type="text" name="title_en" required value="<?= $e($s['title_en'] ?? '') ?>"></label>
    </div>
    <div class="form-grid">
      <label>Résumé (FR)<textarea name="summary_fr" rows="3"><?= $e($s['summary_fr'] ?? '') ?></textarea></label>
      <label>Summary (EN)<textarea name="summary_en" rows="3"><?= $e($s['summary_en'] ?? '') ?></textarea></label>
    </div>
    <div class="form-grid">
      <label>Modèle tarifaire
        <select name="price_model">
          <?php foreach (['quote' => 'Sur devis', 'fixed' => 'Forfait', 'retainer' => 'Retainer', 'sta' => 'STTA'] as $k => $v): ?>
            <option value="<?= $k ?>" <?= ($s['price_model'] ?? 'quote') === $k ? 'selected' : '' ?>><?= $v ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Prix à partir de (USD)
        <input type="number" step="100" name="price_from_usd" value="<?= $e($s['price_from_usd'] ?? '') ?>"></label>
      <label>Ordre d'affichage
        <input type="number" name="sort_order" value="<?= (int)($s['sort_order'] ?? 0) ?>"></label>
    </div>
    <label class="chk" style="margin-top:8px">
      <input type="checkbox" name="is_active" value="1" <?= ($s['is_active'] ?? 1) ? 'checked' : '' ?>> Actif sur le site
    </label>
    <button class="btn btn-gold full" type="submit" style="margin-top:16px"><?= $isEdit ? 'Mettre à jour' : 'Créer le service' ?></button>
  </form>
</div>
