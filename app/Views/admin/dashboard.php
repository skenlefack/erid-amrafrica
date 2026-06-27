<?php
/** @var array $stats @var array $byType @var array $recent */
use App\Core\View;
$e = fn($s) => View::e($s);
?>
<div class="kpi-cards">
    <div class="kpi-card teal">
        <span class="kpi-label">Pipeline (ouvert)</span>
        <span class="kpi-value">$<?= number_format($stats['pipeline_usd']) ?></span>
    </div>
    <div class="kpi-card gold">
        <span class="kpi-label">Contrats gagnés</span>
        <span class="kpi-value">$<?= number_format($stats['won_usd']) ?></span>
    </div>
    <div class="kpi-card navy">
        <span class="kpi-label">Nouveaux leads</span>
        <span class="kpi-value"><?= $stats['leads_new'] ?> / <?= $stats['leads_total'] ?></span>
    </div>
    <div class="kpi-card">
        <span class="kpi-label">Signaux à trier</span>
        <span class="kpi-value"><?= $stats['rumours_new'] ?></span>
    </div>
    <div class="kpi-card">
        <span class="kpi-label">Abonnés</span>
        <span class="kpi-value"><?= $stats['subscribers'] ?></span>
    </div>
    <div class="kpi-card">
        <span class="kpi-label">Articles publiés</span>
        <span class="kpi-value"><?= $stats['articles'] ?></span>
    </div>
</div>

<div class="panel">
    <h2>Pipeline par type d'intake</h2>
    <table class="data-table">
        <thead><tr><th>Type</th><th>Nombre</th><th>Valeur estimée</th></tr></thead>
        <tbody>
        <?php foreach ($byType as $r): ?>
            <tr>
                <td><span class="badge"><?= $e($r['intake_type']) ?></span></td>
                <td><?= (int)$r['n'] ?></td>
                <td>$<?= number_format((float)$r['v']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$byType): ?><tr><td colspan="3" class="muted">Aucune donnée.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>

<div class="panel">
    <h2>Derniers leads</h2>
    <table class="data-table">
        <thead><tr><th>#</th><th>Organisation</th><th>Type</th><th>Statut</th><th>Date</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($recent as $l): ?>
            <tr>
                <td><?= (int)$l['id'] ?></td>
                <td><?= $e($l['organisation']) ?></td>
                <td><span class="badge"><?= $e($l['intake_type']) ?></span></td>
                <td><span class="status status-<?= $e($l['status']) ?>"><?= $e($l['status']) ?></span></td>
                <td><?= $e(substr((string)$l['created_at'], 0, 10)) ?></td>
                <td><a class="link" href="/admin/leads/<?= (int)$l['id'] ?>">Ouvrir →</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$recent): ?><tr><td colspan="6" class="muted">Aucun lead pour le moment.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
