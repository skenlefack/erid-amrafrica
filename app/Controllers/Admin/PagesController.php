<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

final class PagesController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin', 'editor']);
        $pages = Database::all('SELECT * FROM pages ORDER BY slug');
        $this->view('admin/pages', ['title' => 'Pages CMS', 'pages' => $pages], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin', 'editor']);
        $this->view('admin/page_form', ['title' => 'Nouvelle page'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();

        $slug = $this->input('slug') ?: $this->slugify($this->input('title_fr', 'page'));
        $id = Database::exec(
            'INSERT INTO pages (slug, title_fr, title_en, body_fr, body_en, status) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $slug,
                $this->input('title_fr', ''),
                $this->input('title_en', ''),
                $this->input('body_fr'),
                $this->input('body_en'),
                $this->input('status', 'published'),
            ]
        );
        Audit::log('create', 'page', (string) $id);
        $this->redirect('/admin/pages');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        $page = Database::one('SELECT * FROM pages WHERE id = ?', [(int) $id]);
        if (!$page) { http_response_code(404); return; }
        $this->view('admin/page_form', ['title' => 'Éditer la page', 'page' => $page], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();

        Database::exec(
            'UPDATE pages SET slug=?, title_fr=?, title_en=?, body_fr=?, body_en=?, status=? WHERE id=?',
            [
                $this->input('slug', ''),
                $this->input('title_fr', ''),
                $this->input('title_en', ''),
                $this->input('body_fr'),
                $this->input('body_en'),
                $this->input('status', 'published'),
                (int) $id,
            ]
        );
        Audit::log('update', 'page', $id);
        $this->redirect('/admin/pages');
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();
        Database::exec('DELETE FROM pages WHERE id = ?', [(int) $id]);
        Audit::log('delete', 'page', $id);
        $this->redirect('/admin/pages');
    }

    private function slugify(string $s): string
    {
        $s = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s) ?: $s;
        $s = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $s) ?? '');
        return trim($s, '-') ?: 'page';
    }
}
