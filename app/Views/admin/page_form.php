<?php
/** @var array|null $page */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$p = $page ?? null;
?>
<a class="back" href="/admin/pages">← Pages</a>
<div class="panel">
  <form method="post" action="<?= $p ? '/admin/pages/'.(int)$p['id'] : '/admin/pages' ?>">
    <?= Csrf::field() ?>
    <label>Slug (URL)
      <input type="text" name="slug" value="<?= $e($p['slug'] ?? '') ?>" placeholder="vision-mission" pattern="[a-z0-9\-]+" title="Lettres minuscules, chiffres et tirets uniquement">
    </label>
    <div class="form-grid">
      <label>Titre (FR) *<input type="text" name="title_fr" required value="<?= $e($p['title_fr'] ?? '') ?>"></label>
      <label>Title (EN) *<input type="text" name="title_en" required value="<?= $e($p['title_en'] ?? '') ?>"></label>
    </div>
    <div class="form-grid">
      <label>Contenu (FR)<textarea name="body_fr" rows="12"><?= $e($p['body_fr'] ?? '') ?></textarea></label>
      <label>Content (EN)<textarea name="body_en" rows="12"><?= $e($p['body_en'] ?? '') ?></textarea></label>
    </div>
    <label>Statut
      <select name="status">
        <option value="published" <?= ($p && $p['status'] === 'published') ? 'selected' : '' ?>>Publié</option>
        <option value="draft" <?= ($p && $p['status'] === 'draft') ? 'selected' : '' ?>>Brouillon</option>
      </select>
    </label>
    <div style="display:flex;gap:12px;align-items:center;margin-top:8px">
      <button class="btn btn-gold lg" type="submit"><?= $p ? 'Mettre à jour' : 'Enregistrer' ?></button>
      <?php if ($p): ?>
        <a href="/admin/pages/<?= (int)$p['id'] ?>/delete" class="btn btn-ghost"
           onclick="event.preventDefault();if(confirm('Supprimer cette page ?')){const f=document.createElement('form');f.method='POST';f.action=this.href;const t=document.createElement('input');t.type='hidden';t.name='csrf_token';t.value='<?= $e(\App\Core\Csrf::token()) ?>';f.appendChild(t);document.body.appendChild(f);f.submit();}">Supprimer</a>
      <?php endif; ?>
    </div>
  </form>
</div>
