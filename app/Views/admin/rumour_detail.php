<?php
/** @var array $rumour @var array $analysts */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
?>
<a class="back" href="/admin/rumours">← Surveillance</a>
<div class="two-col">
  <div class="panel">
    <h2>Signal #<?= (int)$rumour['id'] ?></h2>
    <dl class="defs">
      <dt>Canal source</dt><dd><span class="badge"><?= $e($rumour['source_channel']) ?></span></dd>
      <dt>Secteur</dt><dd><?= $e($rumour['sector']) ?></dd>
      <dt>Pays</dt><dd><?= $e($rumour['country'] ?: '—') ?></dd>
      <dt>Anonyme</dt><dd><?= $rumour['is_anonymous'] ? 'Oui' : 'Non' ?></dd>
      <?php if (!$rumour['is_anonymous'] && $rumour['reporter_contact']): ?>
        <dt>Contact</dt><dd><?= $e($rumour['reporter_contact']) ?></dd>
      <?php endif; ?>
      <dt>Date de réception</dt><dd><?= $e($rumour['created_at']) ?></dd>
      <dt>Signal brut</dt><dd style="white-space:pre-wrap"><?= $e($rumour['raw_signal']) ?></dd>
      <?php if ($rumour['nlp_keywords']): ?>
        <dt>Mots-clés NLP</dt><dd><?= $e($rumour['nlp_keywords']) ?></dd>
      <?php endif; ?>
    </dl>
  </div>
  <div class="panel">
    <h3>Triage du signal</h3>
    <form method="post" action="/admin/rumours/<?= (int)$rumour['id'] ?>">
      <?= Csrf::field() ?>
      <label>Statut de triage
        <select name="triage_status">
          <?php foreach (['new','triaged','escalated','dismissed'] as $s): ?>
            <option value="<?= $s ?>" <?= $rumour['triage_status'] === $s ? 'selected' : '' ?>><?= $e($s) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Score de risque (0–100)
        <input type="number" name="risk_score" min="0" max="100" value="<?= $rumour['risk_score'] !== null ? (int)$rumour['risk_score'] : '' ?>">
      </label>
      <label>Assigné à
        <select name="assigned_to">
          <option value="">-- Non assigné --</option>
          <?php foreach ($analysts as $u): ?>
            <option value="<?= (int)$u['id'] ?>" <?= (int)($rumour['assigned_to'] ?? 0) === (int)$u['id'] ? 'selected' : '' ?>><?= $e($u['full_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>
      <label>Mots-clés NLP
        <textarea name="nlp_keywords" rows="2"><?= $e($rumour['nlp_keywords'] ?? '') ?></textarea>
      </label>
      <button class="btn btn-gold full" type="submit">Enregistrer le triage</button>
    </form>
  </div>
</div>
