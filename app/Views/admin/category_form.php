<?php
/** @var ?array $category */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$c = $category ?? [];
$isEdit = !empty($c);
?>
<a class="back" href="/admin/categories">← Catégories</a>
<div class="panel" style="max-width:600px">
  <h2><?= $isEdit ? 'Éditer la catégorie' : 'Nouvelle catégorie' ?></h2>
  <form method="post" action="<?= $isEdit ? '/admin/categories/' . (int)$c['id'] : '/admin/categories' ?>">
    <?= Csrf::field() ?>
    <div class="form-grid">
      <label>Nom (FR) *<input type="text" name="name_fr" required value="<?= $e($c['name_fr'] ?? '') ?>" placeholder="RAM"></label>
      <label>Name (EN) *<input type="text" name="name_en" required value="<?= $e($c['name_en'] ?? '') ?>" placeholder="AMR"></label>
    </div>
    <div class="form-grid">
      <label>Slug (auto-généré si vide)<input type="text" name="slug" value="<?= $e($c['slug'] ?? '') ?>" placeholder="amr"></label>
      <label>Couleur d'accent<input type="color" name="accent_color" value="<?= $e($c['accent_color'] ?? '#0E7C7B') ?>" style="height:42px;padding:4px"></label>
    </div>
    <?php if ($isEdit): ?>
    <label>Ordre d'affichage<input type="number" name="sort_order" value="<?= (int)($c['sort_order'] ?? 0) ?>"></label>
    <?php endif; ?>
    <button class="btn btn-gold full" type="submit" style="margin-top:16px"><?= $isEdit ? 'Mettre à jour' : 'Créer la catégorie' ?></button>
  </form>
</div>
