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
    private const PER_PAGE = 12;

    public function index(): void
    {
        $cat = $this->input('cat');
        $page = max(1, (int) ($this->input('page') ?: 1));
        $params = [];
        $where = "a.status = 'published'";
        if ($cat) {
            $where .= ' AND c.slug = ?';
            $params[] = $cat;
        }

        $total = (int) Database::one(
            "SELECT COUNT(*) AS c FROM articles a JOIN categories c ON c.id = a.category_id WHERE {$where}",
            $params
        )['c'];
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));
        $offset = ($page - 1) * self::PER_PAGE;

        $articles = Database::all(
            "SELECT a.*, c.slug AS cat_slug, c.name_fr AS cat_fr, c.name_en AS cat_en, c.accent_color
               FROM articles a JOIN categories c ON c.id = a.category_id
              WHERE {$where}
              ORDER BY a.published_at DESC
              LIMIT " . self::PER_PAGE . " OFFSET {$offset}",
            $params
        );

        $categories = Database::all('SELECT * FROM categories ORDER BY sort_order');

        $this->view('public/news', [
            'title'      => 'News Hub — ERID-AMRAfrica',
            'articles'   => $articles,
            'categories' => $categories,
            'activeCat'  => $cat,
            'page'       => $page,
            'totalPages' => $totalPages,
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
