<?php
declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Lang;

/**
 * Recherche globale — articles, médias, services.
 */
final class SearchController extends Controller
{
    public function index(): void
    {
        $q = trim($this->input('q', ''));
        $results = [];

        if (mb_strlen($q) >= 2) {
            $like = "%{$q}%";

            // Articles
            $articles = Database::all(
                "SELECT a.id, a.slug, a.title_fr, a.title_en, a.excerpt_fr, a.excerpt_en,
                        a.cover_image, a.published_at, c.accent_color, c.name_fr AS cat_fr, c.name_en AS cat_en
                   FROM articles a JOIN categories c ON c.id = a.category_id
                  WHERE a.status = 'published'
                    AND (a.title_fr LIKE ? OR a.title_en LIKE ? OR a.excerpt_fr LIKE ? OR a.excerpt_en LIKE ? OR a.seo_keywords LIKE ?)
                  ORDER BY a.published_at DESC LIMIT 10",
                [$like, $like, $like, $like, $like]
            );
            foreach ($articles as $a) {
                $results[] = [
                    'type' => 'article',
                    'title' => Lang::pick($a, 'title'),
                    'desc' => Lang::pick($a, 'excerpt'),
                    'url' => '/news/' . $a['slug'],
                    'image' => $a['cover_image'] ?? '',
                    'cat' => Lang::pick($a, 'cat'),
                    'color' => $a['accent_color'],
                ];
            }

            // Media
            $media = Database::all(
                "SELECT id, title_fr, title_en, description_fr, description_en, thumbnail, type
                   FROM media_items
                  WHERE status = 'published'
                    AND (title_fr LIKE ? OR title_en LIKE ? OR description_fr LIKE ? OR description_en LIKE ?)
                  ORDER BY created_at DESC LIMIT 5",
                [$like, $like, $like, $like]
            );
            foreach ($media as $m) {
                $results[] = [
                    'type' => 'media',
                    'title' => Lang::pick($m, 'title'),
                    'desc' => Lang::pick($m, 'description'),
                    'url' => '/media',
                    'image' => $m['thumbnail'] ?? '',
                    'cat' => ucfirst($m['type']),
                    'color' => '#607D8B',
                ];
            }

            // Services
            $services = Database::all(
                "SELECT id, pillar, title_fr, title_en, summary_fr, summary_en
                   FROM services
                  WHERE is_active = 1
                    AND (title_fr LIKE ? OR title_en LIKE ? OR summary_fr LIKE ? OR summary_en LIKE ?)
                  ORDER BY sort_order LIMIT 5",
                [$like, $like, $like, $like]
            );
            foreach ($services as $s) {
                $results[] = [
                    'type' => 'service',
                    'title' => Lang::pick($s, 'title'),
                    'desc' => Lang::pick($s, 'summary'),
                    'url' => '/intake/' . $s['pillar'],
                    'image' => '',
                    'cat' => 'Service',
                    'color' => '#0E7C7B',
                ];
            }
        }

        // If AJAX (Accept: application/json), return JSON
        if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
            $this->json(['q' => $q, 'results' => $results, 'total' => count($results)]);
            return;
        }

        // Otherwise, render a full search results page
        $this->view('public/search', [
            'title' => ($q ? $q . ' — ' : '') . 'Search — ERID-AMRAfrica',
            'q' => $q,
            'results' => $results,
        ], 'public');
    }
}
