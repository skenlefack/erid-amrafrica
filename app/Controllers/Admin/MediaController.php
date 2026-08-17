<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

final class MediaController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin', 'editor']);
        $filter = $this->input('type');
        $params = [];
        $where  = '1=1';
        if ($filter) {
            $where .= ' AND type = ?';
            $params[] = $filter;
        }
        $items = Database::all("SELECT * FROM media_items WHERE {$where} ORDER BY created_at DESC", $params);
        $this->view('admin/media', ['title' => 'Médiathèque', 'items' => $items, 'filter' => $filter], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin', 'editor']);
        $this->view('admin/media_form', ['title' => 'Nouveau média'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();

        $thumb = $this->uploadThumbnail();
        $id = Database::exec(
            'INSERT INTO media_items (type, title_fr, title_en, description_fr, description_en, embed_url, thumbnail, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $this->input('type', 'image'),
                $this->input('title_fr', ''),
                $this->input('title_en', ''),
                $this->input('description_fr'),
                $this->input('description_en'),
                $this->input('embed_url'),
                $thumb,
                $this->input('status', 'published'),
            ]
        );
        Audit::log('create', 'media_item', (string) $id);
        $this->redirect('/admin/media');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        $item = Database::one('SELECT * FROM media_items WHERE id = ?', [(int) $id]);
        if (!$item) { http_response_code(404); return; }
        $this->view('admin/media_form', ['title' => 'Éditer le média', 'item' => $item], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();

        $thumb = $this->uploadThumbnail();
        if (!$thumb) {
            $existing = Database::one('SELECT thumbnail FROM media_items WHERE id = ?', [(int) $id]);
            $thumb = $existing['thumbnail'] ?? null;
        }

        Database::exec(
            'UPDATE media_items SET type=?, title_fr=?, title_en=?, description_fr=?, description_en=?, embed_url=?, thumbnail=?, status=? WHERE id=?',
            [
                $this->input('type', 'image'),
                $this->input('title_fr', ''),
                $this->input('title_en', ''),
                $this->input('description_fr'),
                $this->input('description_en'),
                $this->input('embed_url'),
                $thumb,
                $this->input('status', 'published'),
                (int) $id,
            ]
        );
        Audit::log('update', 'media_item', $id);
        $this->redirect('/admin/media');
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();
        Database::exec('DELETE FROM media_items WHERE id = ?', [(int) $id]);
        Audit::log('delete', 'media_item', $id);
        $this->redirect('/admin/media');
    }

    private function uploadThumbnail(): ?string
    {
        if (empty($_FILES['thumbnail']['tmp_name'])) { return null; }
        $file = $_FILES['thumbnail'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed, true) || $file['size'] > 5 * 1024 * 1024) { return null; }
        $ext = match ($mime) { 'image/jpeg' => 'jpg', 'image/png' => 'png', default => 'webp' };
        $dir = APP_ROOT . '/public/uploads/media';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $filename = 'media-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        move_uploaded_file($file['tmp_name'], $dir . '/' . $filename);
        return '/uploads/media/' . $filename;
    }
}
