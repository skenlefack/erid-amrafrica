<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

final class ClassroomController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin', 'editor']);
        $page = max(1, (int) ($this->input('page') ?: 1));
        $total = (int) Database::one("SELECT COUNT(*) AS c FROM courses")['c'];
        $totalPages = max(1, (int) ceil($total / 20));
        $offset = ($page - 1) * 20;
        $courses = Database::all("SELECT * FROM courses ORDER BY sort_order, created_at DESC LIMIT 20 OFFSET {$offset}");
        $this->view('admin/courses', [
            'title' => 'Classroom',
            'courses' => $courses,
            'page' => $page,
            'totalPages' => $totalPages,
        ], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin', 'editor']);
        $this->view('admin/course_form', ['title' => 'Nouvelle formation'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();

        $thumb = $this->uploadFile('thumbnail', 'courses', ['image/jpeg', 'image/png', 'image/webp'], 5);
        $materials = $this->uploadFile('materials_file', 'courses', ['application/pdf'], 20);

        $id = Database::exec(
            'INSERT INTO courses (title_fr, title_en, description_fr, description_en, instructor, duration, level, category, thumbnail, schedule, registration_url, materials_file, status, sort_order)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $this->input('title_fr', ''),
                $this->input('title_en', ''),
                $this->input('description_fr'),
                $this->input('description_en'),
                $this->input('instructor'),
                $this->input('duration'),
                $this->input('level', 'beginner'),
                $this->input('category'),
                $thumb,
                $this->input('schedule'),
                $this->input('registration_url'),
                $materials,
                $this->input('status', 'draft'),
                (int) $this->input('sort_order', '0'),
            ]
        );
        Audit::log('create', 'course', (string) $id);
        $this->redirect('/admin/courses');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        $course = Database::one('SELECT * FROM courses WHERE id = ?', [(int) $id]);
        if (!$course) { http_response_code(404); return; }
        $this->view('admin/course_form', ['title' => 'Éditer la formation', 'course' => $course], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();

        $existing = Database::one('SELECT thumbnail, materials_file FROM courses WHERE id = ?', [(int) $id]);
        $thumb = $this->uploadFile('thumbnail', 'courses', ['image/jpeg', 'image/png', 'image/webp'], 5) ?: ($existing['thumbnail'] ?? null);
        $materials = $this->uploadFile('materials_file', 'courses', ['application/pdf'], 20) ?: ($existing['materials_file'] ?? null);

        Database::exec(
            'UPDATE courses SET title_fr=?, title_en=?, description_fr=?, description_en=?, instructor=?, duration=?, level=?, category=?, thumbnail=?, schedule=?, registration_url=?, materials_file=?, status=?, sort_order=? WHERE id=?',
            [
                $this->input('title_fr', ''),
                $this->input('title_en', ''),
                $this->input('description_fr'),
                $this->input('description_en'),
                $this->input('instructor'),
                $this->input('duration'),
                $this->input('level', 'beginner'),
                $this->input('category'),
                $thumb,
                $this->input('schedule'),
                $this->input('registration_url'),
                $materials,
                $this->input('status', 'draft'),
                (int) $this->input('sort_order', '0'),
                (int) $id,
            ]
        );
        Audit::log('update', 'course', $id);
        $this->redirect('/admin/courses');
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();
        Database::exec("UPDATE courses SET status = 'archived' WHERE id = ?", [(int) $id]);
        Audit::log('delete', 'course', $id);
        $this->redirect('/admin/courses');
    }

    private function uploadFile(string $field, string $subdir, array $allowedMimes, int $maxMb): ?string
    {
        if (empty($_FILES[$field]['tmp_name'])) { return null; }
        $file  = $_FILES[$field];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowedMimes, true) || $file['size'] > $maxMb * 1024 * 1024) { return null; }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $dir = APP_ROOT . '/public/uploads/' . $subdir;
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $filename = $subdir . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        move_uploaded_file($file['tmp_name'], $dir . '/' . $filename);
        return '/uploads/' . $subdir . '/' . $filename;
    }
}
