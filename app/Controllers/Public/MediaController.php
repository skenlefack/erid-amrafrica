<?php
declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

final class MediaController extends Controller
{
    public function index(): void
    {
        $filter = $this->input('type');
        $params = [];
        $where  = "status = 'published'";
        if ($filter) {
            $where .= ' AND type = ?';
            $params[] = $filter;
        }
        $page = max(1, (int) ($this->input('page') ?: 1));
        $total = (int) Database::one("SELECT COUNT(*) AS c FROM media_items WHERE {$where}", $params)['c'];
        $totalPages = max(1, (int) ceil($total / 12));
        $offset = ($page - 1) * 12;
        $items = Database::all("SELECT * FROM media_items WHERE {$where} ORDER BY created_at DESC LIMIT 12 OFFSET {$offset}", $params);
        $this->view('public/media', [
            'title'      => 'Médiathèque — ERID-AMRAfrica',
            'items'      => $items,
            'activeType' => $filter,
            'page' => $page, 'totalPages' => $totalPages,
        ], 'public');
    }
}
