<?php
/** @var array $categories @var array|null $article */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$a = $article ?? null;
?>
<a class="back" href="/admin/articles">← Articles</a>

<form method="post" action="<?= $a ? '/admin/articles/' . (int)$a['id'] : '/admin/articles' ?>" enctype="multipart/form-data">
<?= Csrf::field() ?>

<!-- Section: Infos générales -->
<div class="panel">
    <div class="panel__head"><h3>Informations générales</h3></div>
    <div class="form-grid">
        <label>Titre (FR) *<input type="text" name="title_fr" required value="<?= $e($a['title_fr'] ?? '') ?>"></label>
        <label>Title (EN) *<input type="text" name="title_en" required value="<?= $e($a['title_en'] ?? '') ?>"></label>
    </div>
    <div class="form-grid" style="margin-top:12px">
        <label>Canal / Catégorie
            <select name="category_id">
                <?php foreach ($categories as $c): ?>
                <option value="<?= (int)$c['id'] ?>" <?= ($a && (int)$a['category_id'] === (int)$c['id']) ? 'selected' : '' ?>><?= $e($c['name_fr']) ?> / <?= $e($c['name_en']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <div class="form-grid">
            <label>Statut
                <select name="status">
                    <option value="draft" <?= ($a && $a['status'] === 'draft') ? 'selected' : '' ?>>Brouillon</option>
                    <option value="published" <?= ($a && $a['status'] === 'published') ? 'selected' : '' ?>>Publié</option>
                    <option value="archived" <?= ($a && $a['status'] === 'archived') ? 'selected' : '' ?>>Archivé</option>
                </select>
            </label>
            <label class="chk" style="margin-top:28px"><input type="checkbox" name="is_featured" value="1" <?= ($a && $a['is_featured']) ? 'checked' : '' ?>> À la une</label>
        </div>
    </div>
</div>

<!-- Section: Extrait -->
<div class="panel">
    <div class="panel__head"><h3>Extrait / Résumé</h3></div>
    <div class="form-grid">
        <label>Extrait (FR) <small class="muted">— Affiché dans les listes et le slideshow</small>
            <textarea name="excerpt_fr" rows="3"><?= $e($a['excerpt_fr'] ?? '') ?></textarea>
        </label>
        <label>Excerpt (EN)
            <textarea name="excerpt_en" rows="3"><?= $e($a['excerpt_en'] ?? '') ?></textarea>
        </label>
    </div>
</div>

<!-- Section: Contenu -->
<div class="panel">
    <div class="panel__head"><h3>Contenu de l'article</h3></div>
    <label style="margin-top:0">Corps (FR)</label>
    <textarea name="body_fr" id="body_fr" rows="14" class="wysiwyg"><?= $a['body_fr'] ?? '' ?></textarea>
    <label style="margin-top:16px">Body (EN)</label>
    <textarea name="body_en" id="body_en" rows="14" class="wysiwyg"><?= $a['body_en'] ?? '' ?></textarea>
</div>

<!-- Section: Image -->
<div class="panel">
    <div class="panel__head"><h3>Image de couverture</h3></div>
    <?php if ($a && $a['cover_image']): ?>
    <div class="article-cover-preview">
        <img src="<?= $e($a['cover_image']) ?>" alt="Cover">
    </div>
    <?php endif; ?>
    <label style="margin-top:<?= ($a && $a['cover_image']) ? '12px' : '0' ?>">
        <span>Sélectionner une image (JPG, PNG, WebP — max 5 Mo)</span>
        <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" style="margin-top:6px">
    </label>
</div>

<!-- Actions -->
<div style="display:flex;gap:12px;align-items:center;margin-top:4px">
    <button class="btn btn-gold lg" type="submit"><?= $a ? 'Mettre à jour' : 'Enregistrer & publier' ?></button>
    <?php if ($a): ?>
    <a href="/admin/articles/<?= (int)$a['id'] ?>/delete" class="btn btn-ghost"
       onclick="event.preventDefault();if(confirm('Archiver cet article ?')){const f=document.createElement('form');f.method='POST';f.action=this.href;const t=document.createElement('input');t.type='hidden';t.name='_csrf';t.value='<?= $e(\App\Core\Csrf::token()) ?>';f.appendChild(t);document.body.appendChild(f);f.submit();}">Supprimer</a>
    <?php endif; ?>
    <a href="/admin/articles" class="btn btn-ghost">Annuler</a>
</div>
</form>

<script>
if (typeof tinymce !== 'undefined') {
    tinymce.init({
        selector: '.wysiwyg',
        height: 380,
        menubar: false,
        plugins: 'lists link image code table paste wordcount autolink',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | bullist numlist | link image | table | blockquote | removeformat code',
        content_style: 'body { font-family: "Source Sans 3", "Source Sans Pro", sans-serif; font-size: 15px; line-height: 1.75; color: #2C3E50; padding: 12px; } a { color: #148F77; }',
        branding: false,
        promotion: false,
        statusbar: true,
        elementpath: false,
        resize: true,
        paste_as_text: false,
        link_default_target: '_blank',
    });
}
</script>
