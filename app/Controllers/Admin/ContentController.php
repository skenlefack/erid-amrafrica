<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

/**
 * CMS — pilote l'intégralité du site public depuis la console.
 * Articles (News Hub), Services (3 piliers + tarifs), Paramètres globaux.
 */
final class ContentController extends Controller
{
    // ---------- ARTICLES ----------
    public function articles(): void
    {
        Auth::require(['superadmin', 'editor']);
        $articles = Database::all(
            'SELECT a.*, c.slug cat_slug FROM articles a
               JOIN categories c ON c.id = a.category_id
              ORDER BY a.created_at DESC'
        );
        $this->view('admin/articles', ['title' => 'Articles', 'articles' => $articles], 'admin');
    }

    public function createArticle(): void
    {
        Auth::require(['superadmin', 'editor']);
        $categories = Database::all('SELECT * FROM categories ORDER BY sort_order');
        $this->view('admin/article_form', ['title' => 'Nouvel article', 'categories' => $categories], 'admin');
    }

    public function storeArticle(): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();

        $title = $this->input('title_fr', 'Sans titre');
        $slug  = $this->slugify($title) . '-' . substr(bin2hex(random_bytes(3)), 0, 5);

        $id = Database::exec(
            'INSERT INTO articles
                (category_id, author_id, slug, title_fr, title_en, excerpt_fr, excerpt_en, body_fr, body_en, status, is_featured, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                (int) $this->input('category_id', '1'),
                Auth::user()['id'],
                $slug,
                $title,
                $this->input('title_en', $title),
                $this->input('excerpt_fr'),
                $this->input('excerpt_en'),
                $this->input('body_fr'),
                $this->input('body_en'),
                $this->input('status', 'draft'),
                (int) ($this->input('is_featured') ? 1 : 0),
                $this->input('status') === 'published' ? date('Y-m-d H:i:s') : null,
            ]
        );
        Audit::log('create', 'article', (string) $id);
        $this->redirect('/admin/articles');
    }

    // ---------- SERVICES (3 piliers + monétisation) ----------
    public function services(): void
    {
        Auth::require(['superadmin', 'editor']);
        $services = Database::all('SELECT * FROM services ORDER BY sort_order');
        $this->view('admin/services', ['title' => 'Services & Tarifs', 'services' => $services], 'admin');
    }

    public function updateService(string $id): void
    {
        Auth::require(['superadmin', 'editor']);
        Csrf::verify();
        Database::exec(
            'UPDATE services SET title_fr=?, title_en=?, summary_fr=?, summary_en=?,
                    price_model=?, price_from_usd=?, is_active=? WHERE id=?',
            [
                $this->input('title_fr'),
                $this->input('title_en'),
                $this->input('summary_fr'),
                $this->input('summary_en'),
                $this->input('price_model', 'quote'),
                $this->input('price_from_usd') ?: null,
                (int) ($this->input('is_active') ? 1 : 0),
                (int) $id,
            ]
        );
        Audit::log('update', 'service', $id);
        $this->redirect('/admin/services');
    }

    // ---------- PARAMÈTRES GLOBAUX ----------
    public function settings(): void
    {
        Auth::require(['superadmin']);
        $settings = Database::all('SELECT * FROM settings ORDER BY setting_key');
        $this->view('admin/settings', ['title' => 'Paramètres', 'settings' => $settings], 'admin');
    }

    public function updateSettings(): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        foreach (($_POST['fr'] ?? []) as $key => $val) {
            Database::exec(
                'UPDATE settings SET value_fr=?, value_en=? WHERE setting_key=?',
                [$val, $_POST['en'][$key] ?? null, $key]
            );
        }
        Audit::log('update', 'settings');
        $this->redirect('/admin/settings');
    }

    private function slugify(string $s): string
    {
        $s = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $s) ?: $s;
        $s = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $s) ?? '');
        return trim($s, '-') ?: 'article';
    }
}
