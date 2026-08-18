<?php
declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

final class PublicationsController extends Controller
{
    public function index(): void
    {
        $filter = $this->input('type');
        $params = [];
        $where  = "published_at IS NOT NULL";
        if ($filter) {
            $where .= ' AND pub_type = ?';
            $params[] = $filter;
        }
        $page = max(1, (int) ($this->input('page') ?: 1));
        $total = (int) Database::one("SELECT COUNT(*) AS c FROM publications WHERE {$where}", $params)['c'];
        $totalPages = max(1, (int) ceil($total / 12));
        $offset = ($page - 1) * 12;
        $pubs = Database::all("SELECT * FROM publications WHERE {$where} ORDER BY published_at DESC LIMIT 12 OFFSET {$offset}", $params);
        $this->view('public/publications', [
            'title'      => 'Publications — ERID-AMRAfrica',
            'publications' => $pubs,
            'activeType' => $filter,
            'page' => $page, 'totalPages' => $totalPages,
        ], 'public');
    }

    public function download(string $id): void
    {
        $pub = Database::one('SELECT * FROM publications WHERE id = ? AND published_at IS NOT NULL', [(int) $id]);
        if (!$pub || !$pub['file_path']) {
            http_response_code(404);
            $this->view('public/404', ['title' => '404'], 'public');
            return;
        }
        Database::exec('UPDATE publications SET downloads = downloads + 1 WHERE id = ?', [(int) $id]);
        $this->redirect($pub['file_path']);
    }
}
