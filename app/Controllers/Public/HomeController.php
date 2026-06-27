<?php
declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

/**
 * Page d'accueil — tout le contenu provient de la BDD (piloté par l'admin).
 */
final class HomeController extends Controller
{
    public function index(): void
    {
        $settings = [];
        foreach (Database::all('SELECT * FROM settings') as $s) {
            $settings[$s['setting_key']] = $s;
        }

        $featured = Database::all(
            "SELECT a.*, c.slug AS cat_slug, c.accent_color
               FROM articles a JOIN categories c ON c.id = a.category_id
              WHERE a.status = 'published'
              ORDER BY a.is_featured DESC, a.published_at DESC LIMIT 4"
        );

        $services = Database::all('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order');
        $categories = Database::all('SELECT * FROM categories ORDER BY sort_order');

        // KPIs vitrine (du brief) — alimentables dynamiquement
        $kpis = [
            'signals'  => (int) (Database::one('SELECT COUNT(*) n FROM rumours')['n'] ?? 0),
            'articles' => (int) (Database::one("SELECT COUNT(*) n FROM articles WHERE status='published'")['n'] ?? 0),
            'leads'    => (int) (Database::one('SELECT COUNT(*) n FROM leads')['n'] ?? 0),
        ];

        $this->view('public/home', [
            'title'      => 'ERID-AMRAfrica',
            'settings'   => $settings,
            'featured'   => $featured,
            'services'   => $services,
            'categories' => $categories,
            'kpis'       => $kpis,
        ], 'public');
    }
}
