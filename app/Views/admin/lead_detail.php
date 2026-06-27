<?php
/** @var array $lead */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$extra = $lead['extra_json'] ? json_decode($lead['extra_json'], true) : [];
?>
<a class="back" href="/admin/leads">← Leads</a>
<div class="two-col">
  <div class="panel">
    <h2><?= $e($lead['project_title'] ?: 'Sans titre') ?></h2>
    <dl class="defs">
      <dt>Organisation</dt><dd><?= $e($lead['organisation']) ?></dd>
      <dt>Contact</dt><dd><?= $e($lead['lead_name']) ?> · <?= $e($lead['email']) ?> · <?= $e($lead['phone']) ?></dd>
      <dt>Type / routage</dt><dd><span class="badge"><?= $e($lead['intake_type']) ?></span></dd>
      <dt>Description</dt><dd><?= nl2br($e($lead['description'])) ?></dd>
      <?php foreach ($extra as $k => $v): if (!$v) continue; ?>
        <dt><?= $e($k) ?></dt><dd><?= $e(is_array($v) ? implode(', ', $v) : $v) ?></dd>
      <?php endforeach; ?>
      <dt>E-mail triage</dt><dd><?= $lead['triage_sent_at'] ? '✓ envoyé '.$e($lead['triage_sent_at']) : 'non envoyé' ?></dd>
    </dl>
  </div>
  <div class="panel">
    <h3>Qualifier le lead</h3>
    <form method="post" action="/admin/leads/<?= (int)$lead['id'] ?>">
      <?= Csrf::field() ?>
      <label>Statut
        <select name="status">
          <?php foreach (['new','reviewing','scoping','quoted','won','lost'] as $s): ?>
            <option value="<?= $s ?>" <?= $lead['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Valeur estimée (USD)
        <input type="number" step="100" name="est_value_usd" value="<?= $e($lead['est_value_usd']) ?>"></label>
      <button class="btn btn-gold full" type="submit">Enregistrer</button>
    </form>
  </div>
</div>
