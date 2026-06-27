<?php
/** @var array $services */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<p class="muted">Modifiez les services et leur tarification : tout est répercuté en direct sur le site public.</p>
<?php foreach ($services as $s): ?>
<div class="panel">
  <form method="post" action="/admin/services/<?= (int)$s['id'] ?>">
    <?= Csrf::field() ?>
    <h3><span class="badge"><?= $e($s['routing_tag']) ?></span></h3>
    <div class="form-grid">
      <label>Titre (FR)<input type="text" name="title_fr" value="<?= $e($s['title_fr']) ?>"></label>
      <label>Title (EN)<input type="text" name="title_en" value="<?= $e($s['title_en']) ?>"></label>
    </div>
    <div class="form-grid">
      <label>Résumé (FR)<textarea name="summary_fr" rows="2"><?= $e($s['summary_fr']) ?></textarea></label>
      <label>Summary (EN)<textarea name="summary_en" rows="2"><?= $e($s['summary_en']) ?></textarea></label>
    </div>
    <div class="form-grid">
      <label>Modèle tarifaire
        <select name="price_model">
          <?php foreach (['quote'=>'Sur devis','fixed'=>'Forfait','retainer'=>'Retainer','sta'=>'STTA'] as $k=>$v): ?>
            <option value="<?= $k ?>" <?= $s['price_model']===$k?'selected':'' ?>><?= $v ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>À partir de (USD)<input type="number" step="100" name="price_from_usd" value="<?= $e($s['price_from_usd']) ?>"></label>
      <label class="chk"><input type="checkbox" name="is_active" value="1" <?= $s['is_active']?'checked':'' ?>> Actif</label>
    </div>
    <button class="btn btn-teal" type="submit">Mettre à jour</button>
  </form>
</div>
<?php endforeach; ?>
