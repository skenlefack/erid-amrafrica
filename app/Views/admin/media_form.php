<?php
/** @var array|null $item */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$m = $item ?? null;
?>
<a class="back" href="/admin/media">← Médiathèque</a>
<div class="panel">
  <form method="post" action="<?= $m ? '/admin/media/'.(int)$m['id'] : '/admin/media' ?>" enctype="multipart/form-data">
    <?= Csrf::field() ?>
    <label>Type
      <select name="type">
        <?php foreach (['video','comic','podcast','image'] as $t): ?>
          <option value="<?= $t ?>" <?= ($m && $m['type'] === $t) ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <div class="form-grid">
      <label>Titre (FR) *<input type="text" name="title_fr" required value="<?= $e($m['title_fr'] ?? '') ?>"></label>
      <label>Title (EN) *<input type="text" name="title_en" required value="<?= $e($m['title_en'] ?? '') ?>"></label>
    </div>
    <div class="form-grid">
      <label>Description (FR)<textarea name="description_fr" rows="3"><?= $e($m['description_fr'] ?? '') ?></textarea></label>
      <label>Description (EN)<textarea name="description_en" rows="3"><?= $e($m['description_en'] ?? '') ?></textarea></label>
    </div>
    <label>URL embed (YouTube, podcast, etc.)
      <input type="url" name="embed_url" value="<?= $e($m['embed_url'] ?? '') ?>" placeholder="https://www.youtube.com/embed/...">
    </label>
    <label>Miniature (JPG, PNG, WebP)
      <?php if ($m && $m['thumbnail']): ?>
        <div style="margin:8px 0"><img src="<?= $e($m['thumbnail']) ?>" alt="" style="max-height:100px;border-radius:6px"></div>
      <?php endif; ?>
      <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp">
    </label>
    <label>Statut
      <select name="status">
        <option value="published" <?= ($m && $m['status'] === 'published') ? 'selected' : '' ?>>Publié</option>
        <option value="draft" <?= ($m && $m['status'] === 'draft') ? 'selected' : '' ?>>Brouillon</option>
      </select>
    </label>
    <div style="display:flex;gap:12px;align-items:center;margin-top:8px">
      <button class="btn btn-gold lg" type="submit"><?= $m ? 'Mettre à jour' : 'Enregistrer' ?></button>
      <?php if ($m): ?>
        <a href="/admin/media/<?= (int)$m['id'] ?>/delete" class="btn btn-ghost"
           onclick="event.preventDefault();if(confirm('Supprimer ce m\u00e9dia ?')){const f=document.createElement('form');f.method='POST';f.action=this.href;const t=document.createElement('input');t.type='hidden';t.name='_csrf';t.value='<?= $e(\App\Core\Csrf::token()) ?>';f.appendChild(t);document.body.appendChild(f);f.submit();}">Supprimer</a>
      <?php endif; ?>
    </div>
  </form>
</div>
