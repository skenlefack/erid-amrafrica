<?php
/** @var ?array $user @var ?array $errors @var ?array $old */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$u = $user ?? $old ?? [];
$isEdit = !empty($user['id']);
$val = fn($k, $d = '') => $e($u[$k] ?? $d);
?>
<a class="back" href="/admin/users">← Utilisateurs</a>
<div class="panel" style="max-width:600px">
  <h2><?= $isEdit ? 'Éditer l\'utilisateur' : 'Nouvel utilisateur' ?></h2>

  <?php if (!empty($errors)): ?>
  <div style="background:#fce4ec;border:1px solid #ef9a9a;border-radius:6px;padding:12px 16px;margin-bottom:20px">
    <?php foreach ($errors as $err): ?>
      <p style="margin:4px 0;color:#c62828;font-size:14px">⚠ <?= $e($err) ?></p>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <form method="post" action="<?= $isEdit ? '/admin/users/' . (int)$user['id'] : '/admin/users' ?>">
    <?= Csrf::field() ?>

    <div class="form-grid">
      <label>Nom complet *
        <input type="text" name="full_name" required value="<?= $val('full_name') ?>" placeholder="Dr. Nom Prénom">
      </label>
      <label>Adresse e-mail *
        <input type="email" name="email" required value="<?= $val('email') ?>" placeholder="email@erid-amrafrica.org">
      </label>
      <label>Rôle *
        <select name="role" required>
          <?php foreach (['superadmin' => 'Super Admin', 'editor' => 'Éditeur', 'consultant' => 'Consultant', 'analyst' => 'Analyste'] as $k => $label): ?>
            <option value="<?= $k ?>" <?= ($u['role'] ?? 'consultant') === $k ? 'selected' : '' ?>><?= $e($label) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Langue
        <select name="locale">
          <option value="fr" <?= ($u['locale'] ?? 'fr') === 'fr' ? 'selected' : '' ?>>Français</option>
          <option value="en" <?= ($u['locale'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
        </select>
      </label>
    </div>

    <label>Mot de passe <?= $isEdit ? '(laisser vide pour ne pas changer)' : '*' ?>
      <input type="password" name="password" minlength="8" <?= $isEdit ? '' : 'required' ?> placeholder="Minimum 8 caractères" autocomplete="new-password">
    </label>

    <?php if ($isEdit): ?>
    <label>Statut du compte
      <select name="is_active">
        <option value="1" <?= ($u['is_active'] ?? 1) ? 'selected' : '' ?>>Actif</option>
        <option value="0" <?= !($u['is_active'] ?? 1) ? 'selected' : '' ?>>Inactif</option>
      </select>
    </label>

    <?php if (!empty($user['last_login_at'])): ?>
    <p class="muted small" style="margin-top:8px">Dernière connexion : <?= $e(date('d/m/Y à H:i', strtotime($user['last_login_at']))) ?></p>
    <?php endif; ?>
    <?php if (!empty($user['created_at'])): ?>
    <p class="muted small">Compte créé le : <?= $e(date('d/m/Y', strtotime($user['created_at']))) ?></p>
    <?php endif; ?>
    <?php endif; ?>

    <div style="margin-top:20px">
      <button class="btn btn-gold" type="submit"><?= $isEdit ? 'Mettre à jour' : 'Créer l\'utilisateur' ?></button>
    </div>
  </form>
</div>
