<?php
declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

/**
 * News Hub — taxonomie à 4 canaux, filtrage dynamique par catégorie.
 */
final class NewsController extends Controller
{
    public function index(): void
    {
        $cat = $this->input('cat');
        $params = [];
        $where = "a.status = 'published'";
        if ($cat) {
            $where .= ' AND c.slug = ?';
            $params[] = $cat;
        }

        $articles = Database::all(
            "SELECT a.*, c.slug AS cat_slug, c.name_fr AS cat_fr, c.name_en AS cat_en, c.accent_color
               FROM articles a JOIN categories c ON c.id = a.category_id
              WHERE {$where}
              ORDER BY a.published_at DESC",
            $params
        );

        $categories = Database::all('SELECT * FROM categories ORDER BY sort_order');

        $this->view('public/news', [
            'title'      => 'News Hub — ERID-AMRAfrica',
            'articles'   => $articles,
            'categories' => $categories,
            'activeCat'  => $cat,
        ], 'public');
    }

    public function show(string $slug): void
    {
        $article = Database::one(
            "SELECT a.*, c.slug AS cat_slug, c.accent_color
               FROM articles a JOIN categories c ON c.id = a.category_id
              WHERE a.slug = ? AND a.status = 'published'",
            [$slug]
        );
        if (!$article) {
            http_response_code(404);
            $this->view('public/404', ['title' => '404'], 'public');
            return;
        }
        Database::exec('UPDATE articles SET views = views + 1 WHERE id = ?', [$article['id']]);

        $this->view('public/article', [
            'title'   => $article['title_fr'],
            'article' => $article,
        ], 'public');
    }
}
