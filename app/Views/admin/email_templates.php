<?php
/** @var array $templates */
use App\Core\View; $e = fn($s) => View::e($s);
?>
<div class="toolbar"><a class="btn btn-gold" href="/admin/email-templates/new">+ Nouveau template</a></div>
<div class="panel">
  <table class="data-table">
    <thead><tr><th>Cl&eacute;</th><th>Sujet (FR)</th><th>Sujet (EN)</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($templates as $t): ?>
      <tr>
        <td><code><?= $e($t['template_key']) ?></code></td>
        <td><?= $e($t['subject_fr']) ?></td>
        <td><?= $e($t['subject_en']) ?></td>
        <td><a href="/admin/email-templates/<?= (int)$t['id'] ?>/edit" class="btn btn-ghost sm">&Eacute;diter</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$templates): ?><tr><td colspan="4" class="muted">Aucun template.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
