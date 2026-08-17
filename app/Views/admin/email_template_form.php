<?php
/** @var array|null $tpl */
use App\Core\View; use App\Core\Csrf;
$e = fn($s) => View::e($s);
$t = $tpl ?? null;
?>
<a class="back" href="/admin/email-templates">&larr; Templates email</a>
<div class="panel">
  <div style="background:var(--bg-light);padding:12px 16px;border-radius:6px;margin-bottom:20px;font-size:.9rem">
    <strong>Variables disponibles :</strong>
    <code>{{name}}</code>, <code>{{email}}</code>, <code>{{organisation}}</code>,
    <code>{{project_title}}</code>, <code>{{routing_tag}}</code>, <code>{{date}}</code>
  </div>
  <form method="post" action="<?= $t ? '/admin/email-templates/'.(int)$t['id'] : '/admin/email-templates' ?>">
    <?= Csrf::field() ?>
    <label>Cl&eacute; du template *
      <input type="text" name="template_key" required value="<?= $e($t['template_key'] ?? '') ?>" <?= $t ? 'readonly style="background:#eee"' : '' ?> placeholder="triage_quant">
    </label>
    <div class="form-grid">
      <label>Sujet (FR) *<input type="text" name="subject_fr" required value="<?= $e($t['subject_fr'] ?? '') ?>"></label>
      <label>Subject (EN) *<input type="text" name="subject_en" required value="<?= $e($t['subject_en'] ?? '') ?>"></label>
    </div>
    <div class="form-grid">
      <label>Corps (FR) *<textarea name="body_fr" rows="10" required><?= $e($t['body_fr'] ?? '') ?></textarea></label>
      <label>Body (EN) *<textarea name="body_en" rows="10" required><?= $e($t['body_en'] ?? '') ?></textarea></label>
    </div>
    <div style="display:flex;gap:12px;align-items:center;margin-top:8px">
      <button class="btn btn-gold lg" type="submit"><?= $t ? 'Mettre &agrave; jour' : 'Enregistrer' ?></button>
      <?php if ($t): ?>
        <a href="/admin/email-templates/<?= (int)$t['id'] ?>/delete" class="btn btn-ghost"
           onclick="event.preventDefault();if(confirm('Supprimer ce template ?')){const f=document.createElement('form');f.method='POST';f.action=this.href;const t2=document.createElement('input');t2.type='hidden';t2.name='csrf_token';t2.value='<?= $e(\App\Core\Csrf::token()) ?>';f.appendChild(t2);document.body.appendChild(f);f.submit();}">Supprimer</a>
      <?php endif; ?>
    </div>
  </form>
</div>
