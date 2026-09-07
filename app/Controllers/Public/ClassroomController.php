<?php
declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Lang;

final class ClassroomController extends Controller
{
    public function index(): void
    {
        $filter = $this->input('category');
        $params = [];
        $where  = "status = 'published'";
        if ($filter) {
            $where .= ' AND category = ?';
            $params[] = $filter;
        }

        $page = max(1, (int) ($this->input('page') ?: 1));
        $perPage = 12;
        $total = (int) Database::one("SELECT COUNT(*) AS c FROM courses WHERE {$where}", $params)['c'];
        $totalPages = max(1, (int) ceil($total / $perPage));
        $offset = ($page - 1) * $perPage;

        $courses = Database::all(
            "SELECT * FROM courses WHERE {$where} ORDER BY sort_order, created_at DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $categories = Database::all(
            "SELECT DISTINCT category FROM courses WHERE status = 'published' AND category IS NOT NULL ORDER BY category"
        );

        $this->view('public/classroom', [
            'title'          => Lang::current() === 'fr' ? 'Académie — ERID-AMRAfrica' : 'Academy — ERID-AMRAfrica',
            'courses'        => $courses,
            'categories'     => $categories,
            'activeCategory' => $filter,
            'page'           => $page,
            'totalPages'     => $totalPages,
        ], 'public');
    }

    public function show(string $id): void
    {
        $course = Database::one("SELECT * FROM courses WHERE id = ? AND status = 'published'", [(int) $id]);
        if (!$course) {
            http_response_code(404);
            $this->view('public/404', ['title' => '404'], 'public');
            return;
        }
        $this->view('public/course', [
            'title'  => Lang::pick($course, 'title') . ' — ERID-AMRAfrica',
            'course' => $course,
        ], 'public');
    }
}
