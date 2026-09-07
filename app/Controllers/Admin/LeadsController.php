<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

/**
 * CRM — pipeline commercial (leads) et triage de surveillance (rumours).
 */
final class LeadsController extends Controller
{
    private const PER_PAGE = 20;

    public function index(): void
    {
        Auth::require(['superadmin', 'consultant', 'analyst']);
        $filter = $this->input('status');
        $search = $this->input('q');
        $page   = max(1, (int) ($this->input('page') ?: 1));
        $params = [];
        $where  = '1=1';
        if ($filter) {
            $where .= ' AND status = ?';
            $params[] = $filter;
        }
        if ($search) {
            $where .= ' AND (lead_name LIKE ? OR organisation LIKE ? OR email LIKE ? OR project_title LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        $total = (int) Database::one("SELECT COUNT(*) AS c FROM leads WHERE {$where}", $params)['c'];
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));
        $offset = ($page - 1) * self::PER_PAGE;
        $leads = Database::all("SELECT * FROM leads WHERE {$where} ORDER BY created_at DESC LIMIT " . self::PER_PAGE . " OFFSET {$offset}", $params);
        $this->view('admin/leads', [
            'title' => 'Leads / CRM', 'leads' => $leads, 'filter' => $filter, 'search' => $search,
            'page' => $page, 'totalPages' => $totalPages, 'total' => $total,
        ], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin', 'consultant']);
        $this->view('admin/lead_form', ['title' => 'Nouveau lead'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin', 'consultant']);
        Csrf::verify();
        $id = Database::exec(
            'INSERT INTO leads (intake_type, lead_name, organisation, email, phone, project_title, description, status, est_value_usd, assigned_to)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $this->input('intake_type', 'Advisory_Partnership'),
                $this->input('lead_name', ''),
                $this->input('organisation', ''),
                $this->input('email', ''),
                $this->input('phone'),
                $this->input('project_title'),
                $this->input('description'),
                $this->input('status', 'new'),
                $this->input('est_value_usd') ?: null,
                Auth::user()['id'],
            ]
        );
        Audit::log('create', 'lead', (string) $id);
        $this->redirect('/admin/leads/' . $id, 'Lead créé avec succès.');
    }

    public function show(string $id): void
    {
        Auth::require(['superadmin', 'consultant', 'analyst']);
        $lead = Database::one('SELECT * FROM leads WHERE id = ?', [(int) $id]);
        if (!$lead) { http_response_code(404); return; }
        $users = Database::all("SELECT id, full_name FROM users WHERE is_active = 1 ORDER BY full_name");
        Audit::log('read', 'lead', $id);
        $this->view('admin/lead_detail', ['title' => 'Lead #' . $id, 'lead' => $lead, 'users' => $users], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin', 'consultant']);
        Csrf::verify();
        Database::exec(
            'UPDATE leads SET lead_name=?, organisation=?, email=?, phone=?, project_title=?, description=?,
                    intake_type=?, status=?, est_value_usd=?, assigned_to=? WHERE id=?',
            [
                $this->input('lead_name', ''),
                $this->input('organisation', ''),
                $this->input('email', ''),
                $this->input('phone'),
                $this->input('project_title'),
                $this->input('description'),
                $this->input('intake_type', 'Advisory_Partnership'),
                $this->input('status', 'new'),
                $this->input('est_value_usd') ?: null,
                $this->input('assigned_to') ?: null,
                (int) $id,
            ]
        );
        Audit::log('update', 'lead', $id, ['status' => $this->input('status')]);
        $this->redirect('/admin/leads/' . $id, 'Lead mis à jour.');
    }

    public function export(): void
    {
        Auth::require(['superadmin', 'consultant']);
        $leads = Database::all('SELECT * FROM leads ORDER BY created_at DESC');
        Audit::log('export', 'leads');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="leads-' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Type', 'Nom', 'Organisation', 'Email', 'Téléphone', 'Projet', 'Description', 'Statut', 'Valeur USD', 'Créé le']);
        foreach ($leads as $l) {
            fputcsv($out, [$l['id'], $l['intake_type'], $l['lead_name'], $l['organisation'], $l['email'], $l['phone'], $l['project_title'], $l['description'], $l['status'], $l['est_value_usd'], $l['created_at']]);
        }
        fclose($out);
        exit;
    }

    // ---------- RUMOURS ----------

    public function rumours(): void
    {
        Auth::require(['superadmin', 'analyst']);
        $filter = $this->input('status');
        $search = $this->input('q');
        $params = [];
        $where  = '1=1';
        if ($filter) {
            $where .= ' AND triage_status = ?';
            $params[] = $filter;
        }
        if ($search) {
            $where .= ' AND (raw_signal LIKE ? OR country LIKE ? OR region LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        $page = max(1, (int) ($this->input('page') ?: 1));
        $total = (int) Database::one("SELECT COUNT(*) AS c FROM rumours WHERE {$where}", $params)['c'];
        $totalPages = max(1, (int) ceil($total / 30));
        $offset = ($page - 1) * 30;
        $rumours = Database::all("SELECT * FROM rumours WHERE {$where} ORDER BY created_at DESC LIMIT 30 OFFSET {$offset}", $params);
        $this->view('admin/rumours', [
            'title' => 'Surveillance — Rumeurs', 'rumours' => $rumours, 'filter' => $filter,
            'search' => $search, 'page' => $page, 'totalPages' => $totalPages, 'total' => $total,
        ], 'admin');
    }

    public function rumourDetail(string $id): void
    {
        Auth::require(['superadmin', 'analyst']);
        $rumour = Database::one('SELECT * FROM rumours WHERE id = ?', [(int) $id]);
        if (!$rumour) { http_response_code(404); return; }
        $analysts = Database::all("SELECT id, full_name FROM users WHERE role IN ('superadmin','analyst') AND is_active = 1 ORDER BY full_name");
        Audit::log('read', 'rumour', $id);
        $this->view('admin/rumour_detail', ['title' => 'Rumeur #' . $id, 'rumour' => $rumour, 'analysts' => $analysts], 'admin');
    }

    public function updateRumour(string $id): void
    {
        Auth::require(['superadmin', 'analyst']);
        Csrf::verify();
        Database::exec(
            'UPDATE rumours SET triage_status = ?, risk_score = ?, assigned_to = ?, nlp_keywords = ? WHERE id = ?',
            [
                $this->input('triage_status', 'new'),
                $this->input('risk_score') !== null && $this->input('risk_score') !== '' ? (int) $this->input('risk_score') : null,
                $this->input('assigned_to') ?: null,
                $this->input('nlp_keywords'),
                (int) $id,
            ]
        );
        Audit::log('update', 'rumour', $id, ['status' => $this->input('triage_status')]);
        $this->redirect('/admin/rumours/' . $id, 'Signal mis à jour.');
    }

    public function exportRumours(): void
    {
        Auth::require(['superadmin', 'analyst']);
        $rumours = Database::all('SELECT * FROM rumours ORDER BY created_at DESC');
        Audit::log('export', 'rumours');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="rumours-' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Canal', 'Pays', 'Région', 'Lieu', 'Secteur', 'Signal', 'Statut triage', 'Score risque', 'Créé le']);
        foreach ($rumours as $r) {
            fputcsv($out, [$r['id'], $r['source_channel'], $r['country'], $r['region'] ?? '', $r['setting_type'] ?? '', $r['sector'], $r['raw_signal'], $r['triage_status'], $r['risk_score'], $r['created_at']]);
        }
        fclose($out);
        exit;
    }
}
