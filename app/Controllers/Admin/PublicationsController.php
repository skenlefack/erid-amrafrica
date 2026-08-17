<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

final class PublicationsController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin', 'editor']);
        $filter = $this->input('type');
        $params = [];
        $where  = '1=1';
        if ($filter) {
            $where .= ' AND pub_type = ?';
            $params[] = $filter;
        }
        $pubs = Database::all("SELECT * FROM publications WHERE {$where} ORDER BY published_at DESC", $params);
        $this->view('admin/publications', ['title' => 'Publications', 'publications' => $pubs, 'filter' => $filter], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin', 'editor']);
        $this->view('admin/publication_form', ['title' => 'Nouvelle publication'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();

        $filePath = $this->uploadPdf();
        $id = Database::exec(
            'INSERT INTO publications (title_fr, title_en, pub_type, authors, abstract_fr, abstract_en, file_path, is_gated, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $this->input('title_fr', ''),
                $this->input('title_en', ''),
                $this->input('pub_type', 'whitepaper'),
                $this->input('authors'),
                $this->input('abstract_fr'),
                $this->input('abstract_en'),
                $filePath,
                (int) ($this->input('is_gated') ? 1 : 0),
                $this->input('published_at') ?: null,
            ]
        );
        Audit::log('create', 'publication', (string) $id);
        $this->redirect('/admin/publications');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        $pub = Database::one('SELECT * FROM publications WHERE id = ?', [(int) $id]);
        if (!$pub) { http_response_code(404); return; }
        $this->view('admin/publication_form', ['title' => 'Éditer la publication', 'pub' => $pub], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();

        $filePath = $this->uploadPdf();
        if (!$filePath) {
            $existing = Database::one('SELECT file_path FROM publications WHERE id = ?', [(int) $id]);
            $filePath = $existing['file_path'] ?? null;
        }

        Database::exec(
            'UPDATE publications SET title_fr=?, title_en=?, pub_type=?, authors=?, abstract_fr=?, abstract_en=?, file_path=?, is_gated=?, published_at=? WHERE id=?',
            [
                $this->input('title_fr', ''),
                $this->input('title_en', ''),
                $this->input('pub_type', 'whitepaper'),
                $this->input('authors'),
                $this->input('abstract_fr'),
                $this->input('abstract_en'),
                $filePath,
                (int) ($this->input('is_gated') ? 1 : 0),
                $this->input('published_at') ?: null,
                (int) $id,
            ]
        );
        Audit::log('update', 'publication', $id);
        $this->redirect('/admin/publications');
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();
        Database::exec('DELETE FROM publications WHERE id = ?', [(int) $id]);
        Audit::log('delete', 'publication', $id);
        $this->redirect('/admin/publications');
    }

    private function uploadPdf(): ?string
    {
        if (empty($_FILES['file']['tmp_name'])) { return null; }
        $file = $_FILES['file'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if ($mime !== 'application/pdf' || $file['size'] > 20 * 1024 * 1024) { return null; }
        $dir = APP_ROOT . '/public/uploads/publications';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $filename = 'pub-' . time() . '-' . bin2hex(random_bytes(4)) . '.pdf';
        move_uploaded_file($file['tmp_name'], $dir . '/' . $filename);
        return '/uploads/publications/' . $filename;
    }
}
