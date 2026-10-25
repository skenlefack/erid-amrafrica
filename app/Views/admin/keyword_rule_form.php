<?php
/** @var ?array $rule */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$r = $rule ?? [];
$isEdit = !empty($r);
?>
<a class="back" href="/admin/keyword-rules">&larr; Règles mots-clés</a>
<div class="panel" style="max-width:600px">
  <h2><?= $isEdit ? 'Éditer la règle' : 'Nouvelle règle de mots-clés' ?></h2>
  <form method="post" action="<?= $isEdit ? '/admin/keyword-rules/' . (int)$r['id'] : '/admin/keyword-rules' ?>">
    <?= Csrf::field() ?>

    <label>Nom de la règle *<input type="text" name="name" required value="<?= $e($r['name'] ?? '') ?>" placeholder="AMR core EN"></label>

    <label style="margin-top:12px">Mots-clés (séparés par des virgules) *
      <textarea name="pattern" rows="3" required placeholder="antimicrobial resistance,AMR,antibiotic resistance,drug resistant"><?= $e($r['pattern'] ?? '') ?></textarea>
    </label>

    <div class="form-grid" style="margin-top:12px">
      <label>Langue
        <select name="language">
          <?php foreach (['en' => 'English', 'fr' => 'Français', 'both' => 'Les deux'] as $v => $l): ?>
            <option value="<?= $v ?>" <?= ($r['language'] ?? 'both') === $v ? 'selected' : '' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Catégorie (secteur)
        <select name="category">
          <option value="">— Aucune —</option>
          <?php foreach (['human', 'animal', 'environment', 'agriculture', 'pharma', 'unknown'] as $cat): ?>
            <option value="<?= $cat ?>" <?= ($r['category'] ?? '') === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
    </div>

    <div class="form-grid" style="margin-top:12px">
      <label>Poids de risque (0-100)<input type="number" name="risk_weight" min="0" max="100" value="<?= (int)($r['risk_weight'] ?? 50) ?>"></label>
      <label style="display:flex;align-items:center;gap:8px;padding-top:24px">
        <input type="checkbox" name="is_active" value="1" <?= ($r['is_active'] ?? 1) ? 'checked' : '' ?>> Règle active
      </label>
    </div>

    <button class="btn btn-gold full" type="submit" style="margin-top:20px"><?= $isEdit ? 'Mettre à jour' : 'Créer la règle' ?></button>
  </form>
</div>
