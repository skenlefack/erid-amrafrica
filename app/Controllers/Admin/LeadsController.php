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
        $page   = max(1, (int) ($this->input('page') ?: 1));
        $params = [];
        $where  = '1=1';
        if ($filter) {
            $where .= ' AND status = ?';
            $params[] = $filter;
        }
        $total = (int) Database::one("SELECT COUNT(*) AS c FROM leads WHERE {$where}", $params)['c'];
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));
        $offset = ($page - 1) * self::PER_PAGE;
        $leads = Database::all("SELECT * FROM leads WHERE {$where} ORDER BY created_at DESC LIMIT " . self::PER_PAGE . " OFFSET {$offset}", $params);
        Audit::log('read', 'lead_list');
        $this->view('admin/leads', ['title' => 'Leads / CRM', 'leads' => $leads, 'filter' => $filter, 'page' => $page, 'totalPages' => $totalPages], 'admin');
    }

    public function show(string $id): void
    {
        Auth::require(['superadmin', 'consultant', 'analyst']);
        $lead = Database::one('SELECT * FROM leads WHERE id = ?', [(int) $id]);
        if (!$lead) {
            http_response_code(404);
            $this->view('public/404', ['title' => '404'], 'public');
            return;
        }
        Audit::log('read', 'lead', $id);
        $this->view('admin/lead_detail', ['title' => 'Lead #' . $id, 'lead' => $lead], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin', 'consultant']);
        Csrf::verify();
        Database::exec(
            'UPDATE leads SET status = ?, est_value_usd = ?, assigned_to = ? WHERE id = ?',
            [
                $this->input('status', 'new'),
                $this->input('est_value_usd') ?: null,
                Auth::user()['id'],
                (int) $id,
            ]
        );
        Audit::log('update', 'lead', $id, ['status' => $this->input('status')]);
        $this->redirect('/admin/leads/' . $id);
    }

    public function rumours(): void
    {
        Auth::require(['superadmin', 'analyst']);
        $filter = $this->input('status');
        $params = [];
        $where  = '1=1';
        if ($filter) {
            $where .= ' AND triage_status = ?';
            $params[] = $filter;
        }
        $page = max(1, (int) ($this->input('page') ?: 1));
        $total = (int) Database::one("SELECT COUNT(*) AS c FROM rumours WHERE {$where}", $params)['c'];
        $totalPages = max(1, (int) ceil($total / 30));
        $offset = ($page - 1) * 30;
        $rumours = Database::all("SELECT * FROM rumours WHERE {$where} ORDER BY created_at DESC LIMIT 30 OFFSET {$offset}", $params);
        $this->view('admin/rumours', ['title' => 'Surveillance — Rumeurs', 'rumours' => $rumours, 'filter' => $filter, 'page' => $page, 'totalPages' => $totalPages], 'admin');
    }

    public function rumourDetail(string $id): void
    {
        Auth::require(['superadmin', 'analyst']);
        $rumour = Database::one('SELECT * FROM rumours WHERE id = ?', [(int) $id]);
        if (!$rumour) {
            http_response_code(404);
            $this->view('public/404', ['title' => '404'], 'public');
            return;
        }
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
        $this->redirect('/admin/rumours/' . $id);
    }
}
