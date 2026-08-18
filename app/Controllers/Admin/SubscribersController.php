<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

final class SubscribersController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin']);
        $tier      = $this->input('tier');
        $confirmed = $this->input('confirmed');
        $search    = $this->input('q');

        $where  = '1=1';
        $params = [];
        if ($tier) { $where .= ' AND tier = ?'; $params[] = $tier; }
        if ($confirmed !== null && $confirmed !== '') { $where .= ' AND confirmed = ?'; $params[] = (int) $confirmed; }
        if ($search) { $where .= ' AND (email LIKE ? OR full_name LIKE ?)'; $params[] = "%{$search}%"; $params[] = "%{$search}%"; }

        $page = max(1, (int) ($this->input('page') ?: 1));
        $total = (int) Database::one("SELECT COUNT(*) AS c FROM subscribers WHERE {$where}", $params)['c'];
        $totalPages = max(1, (int) ceil($total / 30));
        $offset = ($page - 1) * 30;
        $subs = Database::all("SELECT * FROM subscribers WHERE {$where} ORDER BY created_at DESC LIMIT 30 OFFSET {$offset}", $params);

        $stats = [
            'total'     => (int) Database::one('SELECT COUNT(*) c FROM subscribers')['c'],
            'free'      => (int) Database::one("SELECT COUNT(*) c FROM subscribers WHERE tier='free'")['c'],
            'intel'     => (int) Database::one("SELECT COUNT(*) c FROM subscribers WHERE tier='intelligence'")['c'],
            'enterprise'=> (int) Database::one("SELECT COUNT(*) c FROM subscribers WHERE tier='enterprise'")['c'],
            'confirmed' => (int) Database::one("SELECT COUNT(*) c FROM subscribers WHERE confirmed=1")['c'],
        ];

        $this->view('admin/subscribers', [
            'title'   => 'Abonnés',
            'subs'    => $subs,
            'stats'   => $stats,
            'filters' => ['tier' => $tier, 'confirmed' => $confirmed, 'q' => $search],
            'page' => $page, 'totalPages' => $totalPages,
        ], 'admin');
    }

    public function show(string $id): void
    {
        Auth::require(['superadmin']);
        $sub = Database::one('SELECT * FROM subscribers WHERE id = ?', [(int) $id]);
        if (!$sub) { http_response_code(404); return; }
        $this->view('admin/subscriber_detail', ['title' => 'Abonné #' . $id, 'sub' => $sub], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        Database::exec(
            'UPDATE subscribers SET tier = ?, confirmed = ? WHERE id = ?',
            [$this->input('tier', 'free'), (int) ($this->input('confirmed') ? 1 : 0), (int) $id]
        );
        Audit::log('update', 'subscriber', $id);
        $this->redirect('/admin/subscribers/' . $id);
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        Database::exec('DELETE FROM subscribers WHERE id = ?', [(int) $id]);
        Audit::log('delete', 'subscriber', $id);
        $this->redirect('/admin/subscribers');
    }

    public function export(): void
    {
        Auth::require(['superadmin']);
        $subs = Database::all('SELECT email, full_name, organisation, tier, locale, confirmed, created_at FROM subscribers ORDER BY created_at DESC');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="subscribers-' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Email', 'Nom', 'Organisation', 'Tier', 'Locale', 'Confirmé', 'Date']);
        foreach ($subs as $s) {
            fputcsv($out, array_values($s));
        }
        fclose($out);
        Audit::log('export', 'subscribers');
        exit;
    }
}
