<?php
/** @var ?array $course */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$c = $course ?? [];
$isEdit = !empty($c);
?>
<a class="back" href="/admin/courses">← Formations</a>
<div class="panel" style="max-width:720px">
  <h2><?= $isEdit ? 'Éditer la formation' : 'Nouvelle formation' ?></h2>
  <form method="post" action="<?= $isEdit ? '/admin/courses/' . (int)$c['id'] : '/admin/courses' ?>" enctype="multipart/form-data">
    <?= Csrf::field() ?>
    <div class="form-grid">
      <label>Titre (FR) *
        <input type="text" name="title_fr" required value="<?= $e($c['title_fr'] ?? '') ?>"></label>
      <label>Titre (EN) *
        <input type="text" name="title_en" required value="<?= $e($c['title_en'] ?? '') ?>"></label>
    </div>
    <label>Description (FR)
      <textarea name="description_fr" rows="5"><?= $e($c['description_fr'] ?? '') ?></textarea></label>
    <label>Description (EN)
      <textarea name="description_en" rows="5"><?= $e($c['description_en'] ?? '') ?></textarea></label>
    <div class="form-grid">
      <label>Formateur
        <input type="text" name="instructor" value="<?= $e($c['instructor'] ?? '') ?>" placeholder="Dr. Nom Prénom"></label>
      <label>Durée
        <input type="text" name="duration" value="<?= $e($c['duration'] ?? '') ?>" placeholder="3 jours / 40 heures"></label>
      <label>Niveau
        <select name="level">
          <option value="beginner" <?= ($c['level'] ?? '') === 'beginner' ? 'selected' : '' ?>>Débutant</option>
          <option value="intermediate" <?= ($c['level'] ?? '') === 'intermediate' ? 'selected' : '' ?>>Intermédiaire</option>
          <option value="advanced" <?= ($c['level'] ?? '') === 'advanced' ? 'selected' : '' ?>>Avancé</option>
        </select>
      </label>
      <label>Catégorie
        <input type="text" name="category" value="<?= $e($c['category'] ?? '') ?>" placeholder="AMR Surveillance, Data Science..."></label>
      <label>Planning / Dates
        <input type="text" name="schedule" value="<?= $e($c['schedule'] ?? '') ?>" placeholder="Oct 15-17, 2026 / À votre rythme"></label>
      <label>URL d'inscription
        <input type="url" name="registration_url" value="<?= $e($c['registration_url'] ?? '') ?>" placeholder="https://..."></label>
      <label>Ordre d'affichage
        <input type="number" name="sort_order" value="<?= (int)($c['sort_order'] ?? 0) ?>"></label>
      <label>Statut
        <select name="status">
          <option value="draft" <?= ($c['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Brouillon</option>
          <option value="published" <?= ($c['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publié</option>
          <option value="archived" <?= ($c['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archivé</option>
        </select>
      </label>
    </div>
    <label>Image de couverture (JPG, PNG, WebP — max 5 MB)
      <input type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp">
      <?php if (!empty($c['thumbnail'])): ?><p class="muted small">Actuel : <?= $e($c['thumbnail']) ?></p><?php endif; ?>
    </label>
    <label>Supports de formation (PDF — max 20 MB)
      <input type="file" name="materials_file" accept=".pdf">
      <?php if (!empty($c['materials_file'])): ?><p class="muted small">Actuel : <?= $e($c['materials_file']) ?></p><?php endif; ?>
    </label>
    <div style="display:flex;gap:12px;margin-top:16px">
      <button class="btn btn-gold" type="submit"><?= $isEdit ? 'Mettre à jour' : 'Créer' ?></button>
      <?php if ($isEdit): ?>
        <form method="post" action="/admin/courses/<?= (int)$c['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Archiver cette formation ?')">
          <?= Csrf::field() ?>
          <button class="btn btn-ghost" type="submit">Archiver</button>
        </form>
      <?php endif; ?>
    </div>
  </form>
</div>
