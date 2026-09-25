<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

/**
 * CRUD des catégories d'articles — administrable dynamiquement.
 */
final class CategoriesController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin', 'editor']);
        $categories = Database::all('SELECT c.*, COUNT(a.id) AS article_count FROM categories c LEFT JOIN articles a ON a.category_id = c.id GROUP BY c.id ORDER BY c.sort_order');
        $this->view('admin/categories', ['title' => 'Catégories', 'categories' => $categories], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin']);
        $this->view('admin/category_form', ['title' => 'Nouvelle catégorie'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        $slug = trim($this->input('slug', ''));
        if (!$slug) {
            $slug = $this->slugify($this->input('name_en', '') ?: $this->input('name_fr', ''));
        }

        $maxOrder = (int) (Database::one('SELECT MAX(sort_order) AS m FROM categories')['m'] ?? 0);
        $id = Database::exec(
            'INSERT INTO categories (slug, name_fr, name_en, accent_color, sort_order) VALUES (?, ?, ?, ?, ?)',
            [
                $slug,
                $this->input('name_fr', ''),
                $this->input('name_en', ''),
                $this->input('accent_color', '#0E7C7B'),
                $maxOrder + 1,
            ]
        );
        Audit::log('create', 'category', (string) $id);
        $this->redirect('/admin/categories', 'Catégorie créée avec succès.');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin']);
        $category = Database::one('SELECT * FROM categories WHERE id = ?', [(int) $id]);
        if (!$category) { http_response_code(404); return; }
        $this->view('admin/category_form', ['title' => 'Éditer la catégorie', 'category' => $category], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        Database::exec(
            'UPDATE categories SET slug=?, name_fr=?, name_en=?, accent_color=?, sort_order=? WHERE id=?',
            [
                $this->input('slug', ''),
                $this->input('name_fr', ''),
                $this->input('name_en', ''),
                $this->input('accent_color', '#0E7C7B'),
                (int) $this->input('sort_order', '0'),
                (int) $id,
            ]
        );
        Audit::log('update', 'category', $id);
        $this->redirect('/admin/categories', 'Catégorie mise à jour.');
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        // Check if category has articles
        $count = (int) Database::one('SELECT COUNT(*) AS c FROM articles WHERE category_id = ?', [(int) $id])['c'];
        if ($count > 0) {
            $this->redirect('/admin/categories', 'Impossible de supprimer : ' . $count . ' article(s) associé(s).', 'error');
            return;
        }
        Database::exec('DELETE FROM categories WHERE id = ?', [(int) $id]);
        Audit::log('delete', 'category', $id);
        $this->redirect('/admin/categories', 'Catégorie supprimée.');
    }

    private function slugify(string $s): string
    {
        $s = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s) ?: $s;
        $s = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $s) ?? '');
        return trim($s, '_') ?: 'category';
    }
}
