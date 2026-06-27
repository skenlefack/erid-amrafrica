<?php
/** @var array $categories */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<a class="back" href="/admin/articles">← Articles</a>
<div class="panel">
  <form method="post" action="/admin/articles">
    <?= Csrf::field() ?>
    <div class="form-grid">
      <label>Titre (FR) *<input type="text" name="title_fr" required></label>
      <label>Title (EN) *<input type="text" name="title_en" required></label>
    </div>
    <label>Canal
      <select name="category_id">
        <?php foreach ($categories as $c): ?>
          <option value="<?= (int)$c['id'] ?>"><?= $e($c['name_fr']) ?> / <?= $e($c['name_en']) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <div class="form-grid">
      <label>Extrait (FR)<textarea name="excerpt_fr" rows="2"></textarea></label>
      <label>Excerpt (EN)<textarea name="excerpt_en" rows="2"></textarea></label>
    </div>
    <div class="form-grid">
      <label>Corps (FR)<textarea name="body_fr" rows="8"></textarea></label>
      <label>Body (EN)<textarea name="body_en" rows="8"></textarea></label>
    </div>
    <div class="form-grid">
      <label>Statut
        <select name="status"><option value="draft">Brouillon</option><option value="published">Publié</option></select>
      </label>
      <label class="chk"><input type="checkbox" name="is_featured" value="1"> À la une</label>
    </div>
    <button class="btn btn-gold lg" type="submit">Enregistrer & publier</button>
  </form>
</div>
