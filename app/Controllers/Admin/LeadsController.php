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
    public function index(): void
    {
        Auth::require(['superadmin', 'consultant', 'analyst']);
        $filter = $this->input('status');
        $params = [];
        $where  = '1=1';
        if ($filter) {
            $where .= ' AND status = ?';
            $params[] = $filter;
        }
        $leads = Database::all("SELECT * FROM leads WHERE {$where} ORDER BY created_at DESC", $params);
        Audit::log('read', 'lead_list');
        $this->view('admin/leads', ['title' => 'Leads / CRM', 'leads' => $leads, 'filter' => $filter], 'admin');
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
        $rumours = Database::all('SELECT * FROM rumours ORDER BY created_at DESC LIMIT 200');
        $this->view('admin/rumours', ['title' => 'Surveillance — Rumeurs', 'rumours' => $rumours], 'admin');
    }
}
