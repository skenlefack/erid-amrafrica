<?php
/** @var array $lead @var array $users */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$extra = $lead['extra_json'] ? json_decode($lead['extra_json'], true) : [];
?>
<a class="back" href="/admin/leads">← Leads</a>
<div class="two-col">
  <!-- Left: Editable form -->
  <div class="panel">
    <h2>Lead #<?= (int)$lead['id'] ?> — <?= $e($lead['project_title'] ?: 'Sans titre') ?></h2>
    <form method="post" action="/admin/leads/<?= (int)$lead['id'] ?>">
      <?= Csrf::field() ?>
      <div class="form-grid">
        <label>Nom du contact
          <input type="text" name="lead_name" value="<?= $e($lead['lead_name']) ?>"></label>
        <label>Organisation
          <input type="text" name="organisation" value="<?= $e($lead['organisation']) ?>"></label>
        <label>E-mail
          <input type="email" name="email" value="<?= $e($lead['email']) ?>"></label>
        <label>Téléphone
          <input type="text" name="phone" value="<?= $e($lead['phone'] ?? '') ?>"></label>
      </div>
      <label>Type d'intake
        <select name="intake_type">
          <?php foreach (['Service_Quant','Service_Qual','Service_Systems','Data_Analytics','Advisory_Partnership'] as $t): ?>
            <option value="<?= $t ?>" <?= $lead['intake_type'] === $t ? 'selected' : '' ?>><?= $e($t) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Titre du projet
        <input type="text" name="project_title" value="<?= $e($lead['project_title'] ?? '') ?>"></label>
      <label>Description
        <textarea name="description" rows="4"><?= $e($lead['description'] ?? '') ?></textarea></label>
      <div class="form-grid">
        <label>Statut
          <select name="status">
            <?php foreach (['new','reviewing','scoping','quoted','won','lost'] as $s): ?>
              <option value="<?= $s ?>" <?= $lead['status'] === $s ? 'selected' : '' ?>><?= $e(ucfirst($s)) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>Valeur estimée (USD)
          <input type="number" step="100" name="est_value_usd" value="<?= $e($lead['est_value_usd'] ?? '') ?>"></label>
        <label>Assigné à
          <select name="assigned_to">
            <option value="">— Non assigné —</option>
            <?php foreach ($users as $u): ?>
              <option value="<?= (int)$u['id'] ?>" <?= (int)($lead['assigned_to'] ?? 0) === (int)$u['id'] ? 'selected' : '' ?>><?= $e($u['full_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
      </div>
      <button class="btn btn-gold full" type="submit" style="margin-top:12px">Enregistrer les modifications</button>
    </form>
  </div>

  <!-- Right: Info panel -->
  <div class="panel">
    <h3>Informations</h3>
    <dl class="defs">
      <dt>Créé le</dt><dd><?= $e($lead['created_at']) ?></dd>
      <dt>Mis à jour</dt><dd><?= $e($lead['updated_at'] ?? '—') ?></dd>
      <dt>E-mail triage</dt><dd><?= $lead['triage_sent_at'] ? '✓ envoyé ' . $e($lead['triage_sent_at']) : '✗ non envoyé' ?></dd>
      <?php if ($lead['uploaded_file']): ?>
        <dt>Fichier</dt><dd><a href="<?= $e($lead['uploaded_file']) ?>" target="_blank">Télécharger</a></dd>
      <?php endif; ?>
    </dl>
    <?php if ($extra): ?>
    <h4 style="margin-top:16px">Données complémentaires</h4>
    <dl class="defs">
      <?php foreach ($extra as $k => $v): if (!$v) continue; ?>
        <dt><?= $e($k) ?></dt><dd><?= $e(is_array($v) ? implode(', ', $v) : $v) ?></dd>
      <?php endforeach; ?>
    </dl>
    <?php endif; ?>
  </div>
</div>
