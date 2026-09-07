<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;

final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::require();

        $stats = [
            'leads_total'    => (int) (Database::one('SELECT COUNT(*) n FROM leads')['n'] ?? 0),
            'leads_new'      => (int) (Database::one("SELECT COUNT(*) n FROM leads WHERE status='new'")['n'] ?? 0),
            'leads_reviewing'=> (int) (Database::one("SELECT COUNT(*) n FROM leads WHERE status='reviewing'")['n'] ?? 0),
            'leads_scoping'  => (int) (Database::one("SELECT COUNT(*) n FROM leads WHERE status='scoping'")['n'] ?? 0),
            'leads_won'      => (int) (Database::one("SELECT COUNT(*) n FROM leads WHERE status='won'")['n'] ?? 0),
            'pipeline_usd'   => (float) (Database::one("SELECT COALESCE(SUM(est_value_usd),0) v FROM leads WHERE status IN ('reviewing','scoping','quoted')")['v'] ?? 0),
            'won_usd'        => (float) (Database::one("SELECT COALESCE(SUM(est_value_usd),0) v FROM leads WHERE status='won'")['v'] ?? 0),
            'rumours_new'    => (int) (Database::one("SELECT COUNT(*) n FROM rumours WHERE triage_status='new'")['n'] ?? 0),
            'rumours_total'  => (int) (Database::one('SELECT COUNT(*) n FROM rumours')['n'] ?? 0),
            'subscribers'    => (int) (Database::one('SELECT COUNT(*) n FROM subscribers')['n'] ?? 0),
            'articles'       => (int) (Database::one("SELECT COUNT(*) n FROM articles WHERE status='published'")['n'] ?? 0),
            'views_total'    => (int) (Database::one('SELECT COALESCE(SUM(views),0) n FROM articles')['n'] ?? 0),
            'media_count'    => (int) (Database::one('SELECT COUNT(*) n FROM media_items')['n'] ?? 0),
            'pages_count'    => (int) (Database::one('SELECT COUNT(*) n FROM pages')['n'] ?? 0),
            'users_total'    => (int) (Database::one('SELECT COUNT(*) n FROM users')['n'] ?? 0),
            'users_active'   => (int) (Database::one("SELECT COUNT(*) n FROM users WHERE is_active = 1")['n'] ?? 0),
            'courses_count'  => (int) (Database::one("SELECT COUNT(*) n FROM courses WHERE status='published'")['n'] ?? 0),
        ];

        $byType = Database::all(
            'SELECT intake_type, COUNT(*) n, COALESCE(SUM(est_value_usd),0) v
               FROM leads GROUP BY intake_type ORDER BY v DESC'
        );

        $recent = Database::all('SELECT * FROM leads ORDER BY created_at DESC LIMIT 8');

        $recentArticles = Database::all(
            "SELECT id, title_fr, status, views, published_at, created_at FROM articles ORDER BY created_at DESC LIMIT 5"
        );

        $recentRumours = Database::all(
            "SELECT id, source_channel, sector, country, triage_status, created_at FROM rumours ORDER BY created_at DESC LIMIT 5"
        );

        $this->view('admin/dashboard', [
            'title'          => 'Tableau de bord',
            'stats'          => $stats,
            'byType'         => $byType,
            'recent'         => $recent,
            'recentArticles' => $recentArticles,
            'recentRumours'  => $recentRumours,
        ], 'admin');
    }
}
