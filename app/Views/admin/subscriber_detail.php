<?php
/** @var array $sub */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<a class="back" href="/admin/subscribers">&larr; Abonn&eacute;s</a>
<div class="two-col">
  <div class="panel">
    <h2><?= $e($sub['email']) ?></h2>
    <dl class="defs">
      <dt>Nom</dt><dd><?= $e($sub['full_name'] ?? '—') ?></dd>
      <dt>Organisation</dt><dd><?= $e($sub['organisation'] ?? '—') ?></dd>
      <dt>Tier</dt><dd><span class="badge"><?= $e($sub['tier']) ?></span></dd>
      <dt>Langue</dt><dd><?= $e(strtoupper($sub['locale'])) ?></dd>
      <dt>Confirm&eacute;</dt><dd><?= $sub['confirmed'] ? 'Oui' : 'Non' ?></dd>
      <dt>Inscrit le</dt><dd><?= $e($sub['created_at']) ?></dd>
    </dl>
  </div>
  <div class="panel">
    <h3>Modifier</h3>
    <form method="post" action="/admin/subscribers/<?= (int)$sub['id'] ?>">
      <?= Csrf::field() ?>
      <label>Tier
        <select name="tier">
          <?php foreach (['free','intelligence','enterprise'] as $t): ?>
            <option value="<?= $t ?>" <?= $sub['tier'] === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label class="chk"><input type="checkbox" name="confirmed" value="1" <?= $sub['confirmed'] ? 'checked' : '' ?>> Confirm&eacute;</label>
      <button class="btn btn-gold full" type="submit">Enregistrer</button>
    </form>
    <hr style="margin:20px 0">
    <form method="post" action="/admin/subscribers/<?= (int)$sub['id'] ?>/delete" onsubmit="return confirm('Supprimer cet abonn\u00e9 ?')">
      <?= Csrf::field() ?>
      <button class="btn btn-ghost full" type="submit">Supprimer l&rsquo;abonn&eacute;</button>
    </form>
  </div>
</div>
