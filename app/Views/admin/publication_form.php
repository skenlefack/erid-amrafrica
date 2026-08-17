<?php
/** @var array|null $pub */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$p = $pub ?? null;
$types = ['peer_reviewed'=>'Peer-reviewed','whitepaper'=>'Whitepaper','field_blog'=>'Field blog','policy_brief'=>'Policy brief'];
?>
<a class="back" href="/admin/publications">&larr; Publications</a>
<div class="panel">
  <form method="post" action="<?= $p ? '/admin/publications/'.(int)$p['id'] : '/admin/publications' ?>" enctype="multipart/form-data">
    <?= Csrf::field() ?>
    <label>Type
      <select name="pub_type">
        <?php foreach ($types as $k => $label): ?>
          <option value="<?= $k ?>" <?= ($p && $p['pub_type'] === $k) ? 'selected' : '' ?>><?= $e($label) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <div class="form-grid">
      <label>Titre (FR) *<input type="text" name="title_fr" required value="<?= $e($p['title_fr'] ?? '') ?>"></label>
      <label>Title (EN) *<input type="text" name="title_en" required value="<?= $e($p['title_en'] ?? '') ?>"></label>
    </div>
    <label>Auteurs<input type="text" name="authors" value="<?= $e($p['authors'] ?? '') ?>" placeholder="Nom1, Nom2, ..."></label>
    <div class="form-grid">
      <label>R&eacute;sum&eacute; (FR)<textarea name="abstract_fr" rows="4"><?= $e($p['abstract_fr'] ?? '') ?></textarea></label>
      <label>Abstract (EN)<textarea name="abstract_en" rows="4"><?= $e($p['abstract_en'] ?? '') ?></textarea></label>
    </div>
    <label>Fichier PDF (max 20 Mo)
      <?php if ($p && $p['file_path']): ?>
        <div style="margin:8px 0"><a href="<?= $e($p['file_path']) ?>" target="_blank">Fichier actuel &rarr;</a></div>
      <?php endif; ?>
      <input type="file" name="file" accept="application/pdf">
    </label>
    <div class="form-grid">
      <label>Date de publication<input type="date" name="published_at" value="<?= $e($p['published_at'] ?? '') ?>"></label>
      <label class="chk"><input type="checkbox" name="is_gated" value="1" <?= ($p && $p['is_gated']) ? 'checked' : '' ?>> Contenu premium (acc&egrave;s restreint)</label>
    </div>
    <div style="display:flex;gap:12px;align-items:center;margin-top:8px">
      <button class="btn btn-gold lg" type="submit"><?= $p ? 'Mettre &agrave; jour' : 'Enregistrer' ?></button>
      <?php if ($p): ?>
        <a href="/admin/publications/<?= (int)$p['id'] ?>/delete" class="btn btn-ghost"
           onclick="event.preventDefault();if(confirm('Supprimer ?')){const f=document.createElement('form');f.method='POST';f.action=this.href;const t=document.createElement('input');t.type='hidden';t.name='csrf_token';t.value='<?= $e(\App\Core\Csrf::token()) ?>';f.appendChild(t);document.body.appendChild(f);f.submit();}">Supprimer</a>
      <?php endif; ?>
    </div>
  </form>
</div>
