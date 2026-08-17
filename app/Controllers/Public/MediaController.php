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
        $items = Database::all("SELECT * FROM media_items WHERE {$where} ORDER BY created_at DESC", $params);
        $this->view('public/media', [
            'title'      => 'Médiathèque — ERID-AMRAfrica',
            'items'      => $items,
            'activeType' => $filter,
        ], 'public');
    }
}
