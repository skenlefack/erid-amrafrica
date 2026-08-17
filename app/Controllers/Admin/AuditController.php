<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;

final class AuditController extends Controller
{
    private const PER_PAGE = 50;

    public function index(): void
    {
        Auth::require(['superadmin']);

        $entity = $this->input('entity');
        $action = $this->input('action');
        $from   = $this->input('from');
        $to     = $this->input('to');
        $page   = max(1, (int) ($this->input('page') ?: 1));

        $where  = '1=1';
        $params = [];
        if ($entity) { $where .= ' AND a.entity = ?'; $params[] = $entity; }
        if ($action) { $where .= ' AND a.action = ?'; $params[] = $action; }
        if ($from)   { $where .= ' AND a.created_at >= ?'; $params[] = $from . ' 00:00:00'; }
        if ($to)     { $where .= ' AND a.created_at <= ?'; $params[] = $to . ' 23:59:59'; }

        $total = (int) Database::one("SELECT COUNT(*) AS c FROM audit_logs a WHERE {$where}", $params)['c'];
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));
        $offset = ($page - 1) * self::PER_PAGE;

        $logs = Database::all(
            "SELECT a.*, u.full_name AS user_name
               FROM audit_logs a
               LEFT JOIN users u ON u.id = a.user_id
              WHERE {$where}
              ORDER BY a.created_at DESC
              LIMIT " . self::PER_PAGE . " OFFSET {$offset}",
            $params
        );

        $entities = Database::all('SELECT DISTINCT entity FROM audit_logs ORDER BY entity');
        $actions  = Database::all('SELECT DISTINCT action FROM audit_logs ORDER BY action');

        $this->view('admin/audit', [
            'title'      => 'Journal d\'audit',
            'logs'       => $logs,
            'entities'   => array_column($entities, 'entity'),
            'actions'    => array_column($actions, 'action'),
            'filters'    => compact('entity', 'action', 'from', 'to'),
            'page'       => $page,
            'totalPages' => $totalPages,
            'total'      => $total,
        ], 'admin');
    }
}
