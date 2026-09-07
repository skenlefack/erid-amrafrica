<?php
/** @var array $courses @var int $page @var int $totalPages */
use App\Core\View; $e = fn($s) => View::e($s);
?>
<div class="panel">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
    <h2>Formations / Classroom</h2>
    <a class="btn btn-gold sm" href="/admin/courses/new">+ Nouvelle formation</a>
  </div>
  <table class="data-table">
    <thead><tr><th>#</th><th>Titre</th><th>Formateur</th><th>Catégorie</th><th>Niveau</th><th>Statut</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($courses as $c): ?>
      <tr>
        <td><?= (int)$c['id'] ?></td>
        <td><?= $e($c['title_fr']) ?></td>
        <td><?= $e($c['instructor'] ?: '—') ?></td>
        <td><?= $e($c['category'] ?: '—') ?></td>
        <td><?= $e(ucfirst($c['level'])) ?></td>
        <td><span class="status status-<?= $e($c['status']) ?>"><?= $e($c['status']) ?></span></td>
        <td>
          <a class="link" href="/admin/courses/<?= (int)$c['id'] ?>/edit">Éditer</a>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$courses): ?><tr><td colspan="7" class="muted">Aucune formation.</td></tr><?php endif; ?>
    </tbody>
  </table>
  <?php if ($totalPages > 1): ?>
  <div style="display:flex;gap:8px;justify-content:center;margin-top:20px">
    <?php if ($page > 1): ?><a class="btn btn-ghost sm" href="/admin/courses?page=<?= $page - 1 ?>">← Préc.</a><?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a class="btn <?= $i === $page ? 'btn-gold' : 'btn-ghost' ?> sm" href="/admin/courses?page=<?= $i ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?><a class="btn btn-ghost sm" href="/admin/courses?page=<?= $page + 1 ?>">Suiv. →</a><?php endif; ?>
  </div>
  <?php endif; ?>
</div>
