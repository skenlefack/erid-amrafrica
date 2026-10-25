<?php
/** @var ?array $partner */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$p = $partner ?? [];
$isEdit = !empty($p);
?>
<a class="back" href="/admin/partners">&larr; Partenaires</a>
<div class="panel" style="max-width:600px">
  <h2><?= $isEdit ? 'Éditer le partenaire' : 'Nouveau partenaire' ?></h2>
  <form method="post" action="<?= $isEdit ? '/admin/partners/' . (int)$p['id'] : '/admin/partners' ?>" enctype="multipart/form-data">
    <?= Csrf::field() ?>

    <label>Nom du partenaire *<input type="text" name="name" required value="<?= $e($p['name'] ?? '') ?>" placeholder="Africa CDC"></label>

    <label style="margin-top:12px">Logo (JPEG, PNG, WebP, SVG — max 2 Mo)
      <?php if ($isEdit && ($p['logo_path'] ?? null)): ?>
        <div style="margin:8px 0"><img src="<?= $e($p['logo_path']) ?>" alt="Logo actuel" style="height:40px;border-radius:4px;background:#f5f5f5;padding:4px"></div>
      <?php endif; ?>
      <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/svg+xml">
    </label>

    <label style="margin-top:12px">Site web (URL)<input type="url" name="website_url" value="<?= $e($p['website_url'] ?? '') ?>" placeholder="https://africacdc.org"></label>

    <label style="margin-top:12px;display:flex;align-items:center;gap:8px">
      <input type="checkbox" name="is_active" value="1" <?= ($p['is_active'] ?? 1) ? 'checked' : '' ?>> Actif (visible dans le footer)
    </label>

    <?php if ($isEdit): ?>
    <label style="margin-top:12px">Ordre d'affichage<input type="number" name="sort_order" value="<?= (int)($p['sort_order'] ?? 0) ?>"></label>
    <?php endif; ?>

    <button class="btn btn-gold full" type="submit" style="margin-top:20px"><?= $isEdit ? 'Mettre à jour' : 'Créer le partenaire' ?></button>
  </form>
</div>
