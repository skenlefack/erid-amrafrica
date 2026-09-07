<?php
/** @var array $stats @var array $byType @var array $recent @var array $recentArticles @var array $recentRumours */
use App\Core\View;
use App\Core\Auth;
$e = fn($s) => View::e($s);
$u = Auth::user();
?>
<!-- Welcome -->
<div class="dash-welcome">
    <div>
        <h2 class="dash-welcome__title">Bienvenue, <?= $e($u['name'] ?? 'Admin') ?></h2>
        <p class="dash-welcome__sub"><?= date('l d F Y') ?> — Vue d'ensemble de la plateforme</p>
    </div>
    <div class="dash-welcome__actions">
        <a href="/admin/articles/new" class="btn btn-gold sm">+ Nouvel article</a>
        <a href="/admin/leads" class="btn btn-accent sm">Voir les leads</a>
    </div>
</div>

<!-- KPI Row 1: Commercial -->
<div class="dash-kpis">
    <div class="dash-kpi dash-kpi--pipeline">
        <div class="dash-kpi__icon">💰</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value">$<?= number_format($stats['pipeline_usd']) ?></span>
            <span class="dash-kpi__label">Pipeline ouvert</span>
        </div>
    </div>
    <div class="dash-kpi dash-kpi--won">
        <div class="dash-kpi__icon">🏆</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value">$<?= number_format($stats['won_usd']) ?></span>
            <span class="dash-kpi__label">Contrats gagnés</span>
        </div>
    </div>
    <div class="dash-kpi dash-kpi--leads">
        <div class="dash-kpi__icon">💼</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value"><?= $stats['leads_new'] ?><small> / <?= $stats['leads_total'] ?></small></span>
            <span class="dash-kpi__label">Nouveaux leads</span>
        </div>
    </div>
    <div class="dash-kpi dash-kpi--signals">
        <div class="dash-kpi__icon">📡</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value"><?= $stats['rumours_new'] ?><small> / <?= $stats['rumours_total'] ?></small></span>
            <span class="dash-kpi__label">Signaux à trier</span>
        </div>
    </div>
</div>

<!-- KPI Row 2: Contenu -->
<div class="dash-kpis">
    <div class="dash-kpi">
        <div class="dash-kpi__icon">📰</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value"><?= $stats['articles'] ?></span>
            <span class="dash-kpi__label">Articles publiés</span>
        </div>
    </div>
    <div class="dash-kpi">
        <div class="dash-kpi__icon">👁️</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value"><?= number_format($stats['views_total']) ?></span>
            <span class="dash-kpi__label">Lectures totales</span>
        </div>
    </div>
    <div class="dash-kpi">
        <div class="dash-kpi__icon">👥</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value"><?= $stats['subscribers'] ?></span>
            <span class="dash-kpi__label">Abonnés</span>
        </div>
    </div>
    <div class="dash-kpi">
        <div class="dash-kpi__icon">🎬</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value"><?= $stats['media_count'] + $stats['pages_count'] ?></span>
            <span class="dash-kpi__label">Médias & Pages</span>
        </div>
    </div>
    <div class="dash-kpi">
        <div class="dash-kpi__icon">👤</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value"><?= $stats['users_active'] ?><small> / <?= $stats['users_total'] ?></small></span>
            <span class="dash-kpi__label">Utilisateurs actifs</span>
        </div>
    </div>
    <div class="dash-kpi">
        <div class="dash-kpi__icon">🎓</div>
        <div class="dash-kpi__data">
            <span class="dash-kpi__value"><?= $stats['courses_count'] ?></span>
            <span class="dash-kpi__label">Formations</span>
        </div>
    </div>
</div>

<!-- Two-column layout -->
<div class="dash-grid">
    <!-- Left column -->
    <div class="dash-col-main">
        <!-- Pipeline -->
        <div class="panel">
            <div class="panel__head">
                <h3>Pipeline par type d'intake</h3>
            </div>
            <table class="data-table">
                <thead><tr><th>Type</th><th>Nombre</th><th>Valeur estimée</th></tr></thead>
                <tbody>
                <?php foreach ($byType as $r): ?>
                    <tr>
                        <td><span class="badge"><?= $e($r['intake_type']) ?></span></td>
                        <td><?= (int)$r['n'] ?></td>
                        <td><strong>$<?= number_format((float)$r['v']) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$byType): ?><tr><td colspan="3" class="muted">Aucune donnée.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Recent leads -->
        <div class="panel">
            <div class="panel__head">
                <h3>Derniers leads</h3>
                <a href="/admin/leads" class="btn btn-ghost sm">Voir tout →</a>
            </div>
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
                <?php if (!$recent): ?><tr><td colspan="6" class="muted">Aucun lead.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right column -->
    <div class="dash-col-side">
        <!-- Recent articles -->
        <div class="panel">
            <div class="panel__head"><h3>Derniers articles</h3></div>
            <div class="dash-mini-list">
                <?php foreach ($recentArticles as $ra): ?>
                <a href="/admin/articles/<?= (int)$ra['id'] ?>/edit" class="dash-mini-item">
                    <div class="dash-mini-item__text">
                        <span class="dash-mini-item__title"><?= $e($ra['title_fr']) ?></span>
                        <span class="dash-mini-item__meta"><?= (int)$ra['views'] ?> vues · <?= $e(substr($ra['published_at'] ?? $ra['created_at'], 0, 10)) ?></span>
                    </div>
                    <span class="status status-<?= $e($ra['status']) ?>"><?= $e($ra['status']) ?></span>
                </a>
                <?php endforeach; ?>
                <?php if (!$recentArticles): ?><p class="muted" style="padding:12px;font-size:13px">Aucun article.</p><?php endif; ?>
            </div>
        </div>

        <!-- Recent signals -->
        <div class="panel">
            <div class="panel__head"><h3>Derniers signaux</h3></div>
            <div class="dash-mini-list">
                <?php foreach ($recentRumours as $rr): ?>
                <a href="/admin/rumours/<?= (int)$rr['id'] ?>" class="dash-mini-item">
                    <div class="dash-mini-item__text">
                        <span class="badge" style="font-size:9px"><?= $e($rr['source_channel']) ?></span>
                        <span class="dash-mini-item__meta"><?= $e($rr['sector']) ?> · <?= $e(substr($rr['created_at'], 0, 10)) ?></span>
                    </div>
                    <span class="status status-<?= $e($rr['triage_status']) ?>"><?= $e($rr['triage_status']) ?></span>
                </a>
                <?php endforeach; ?>
                <?php if (!$recentRumours): ?><p class="muted" style="padding:12px;font-size:13px">Aucun signal.</p><?php endif; ?>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="panel">
            <div class="panel__head"><h3>Actions rapides</h3></div>
            <div style="padding:4px 0;display:flex;flex-direction:column;gap:8px">
                <a href="/admin/articles/new" class="btn btn-gold full sm">+ Nouvel article</a>
                <a href="/admin/media/new" class="btn btn-accent full sm">+ Nouveau média</a>
                <a href="/admin/publications/new" class="btn btn-dark full sm">+ Nouvelle publication</a>
                <a href="/admin/subscribers/export" class="btn btn-ghost full sm">📥 Exporter abonnés CSV</a>
            </div>
        </div>
    </div>
</div>
