<?php
/** @var array $categories @var array|null $article */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$a = $article ?? null;
?>
<a class="back" href="/admin/articles">← Articles</a>
<div class="panel">
  <form method="post" action="<?= $a ? '/admin/articles/' . (int)$a['id'] : '/admin/articles' ?>" enctype="multipart/form-data">
    <?= Csrf::field() ?>
    <div class="form-grid">
      <label>Titre (FR) *<input type="text" name="title_fr" required value="<?= $e($a['title_fr'] ?? '') ?>"></label>
      <label>Title (EN) *<input type="text" name="title_en" required value="<?= $e($a['title_en'] ?? '') ?>"></label>
    </div>
    <label>Canal
      <select name="category_id">
        <?php foreach ($categories as $c): ?>
          <option value="<?= (int)$c['id'] ?>" <?= ($a && (int)$a['category_id'] === (int)$c['id']) ? 'selected' : '' ?>><?= $e($c['name_fr']) ?> / <?= $e($c['name_en']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <div class="form-grid">
      <label>Extrait (FR)<textarea name="excerpt_fr" rows="2"><?= $e($a['excerpt_fr'] ?? '') ?></textarea></label>
      <label>Excerpt (EN)<textarea name="excerpt_en" rows="2"><?= $e($a['excerpt_en'] ?? '') ?></textarea></label>
    </div>
    <div class="form-grid">
      <label>Corps (FR)<textarea name="body_fr" rows="8"><?= $e($a['body_fr'] ?? '') ?></textarea></label>
      <label>Body (EN)<textarea name="body_en" rows="8"><?= $e($a['body_en'] ?? '') ?></textarea></label>
    </div>
    <label>Image de couverture (JPG, PNG, WebP — max 5 Mo)
      <?php if ($a && $a['cover_image']): ?>
        <div style="margin:8px 0"><img src="<?= $e($a['cover_image']) ?>" alt="Cover" style="max-height:120px;border-radius:6px"></div>
      <?php endif; ?>
      <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp">
    </label>
    <div class="form-grid">
      <label>Statut
        <select name="status">
          <option value="draft" <?= ($a && $a['status'] === 'draft') ? 'selected' : '' ?>>Brouillon</option>
          <option value="published" <?= ($a && $a['status'] === 'published') ? 'selected' : '' ?>>Publié</option>
          <option value="archived" <?= ($a && $a['status'] === 'archived') ? 'selected' : '' ?>>Archivé</option>
        </select>
      </label>
      <label class="chk"><input type="checkbox" name="is_featured" value="1" <?= ($a && $a['is_featured']) ? 'checked' : '' ?>> À la une</label>
    </div>
    <div style="display:flex;gap:12px;align-items:center;margin-top:8px">
      <button class="btn btn-gold lg" type="submit"><?= $a ? 'Mettre à jour' : 'Enregistrer & publier' ?></button>
      <?php if ($a): ?>
        <a href="/admin/articles/<?= (int)$a['id'] ?>/delete" class="btn btn-ghost"
           onclick="event.preventDefault();if(confirm('Archiver cet article ?')){const f=document.createElement('form');f.method='POST';f.action=this.href;const t=document.createElement('input');t.type='hidden';t.name='csrf_token';t.value='<?= $e(\App\Core\Csrf::token()) ?>';f.appendChild(t);document.body.appendChild(f);f.submit();}">Supprimer</a>
      <?php endif; ?>
    </div>
  </form>
</div>
