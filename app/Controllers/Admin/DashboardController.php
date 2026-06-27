<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;

/**
 * Tableau de bord admin — vue commerciale (pipeline) + surveillance.
 */
final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::require();

        $stats = [
            'leads_total'   => (int) (Database::one('SELECT COUNT(*) n FROM leads')['n'] ?? 0),
            'leads_new'     => (int) (Database::one("SELECT COUNT(*) n FROM leads WHERE status='new'")['n'] ?? 0),
            'leads_won'     => (int) (Database::one("SELECT COUNT(*) n FROM leads WHERE status='won'")['n'] ?? 0),
            'pipeline_usd'  => (float) (Database::one("SELECT COALESCE(SUM(est_value_usd),0) v FROM leads WHERE status IN ('reviewing','scoping','quoted')")['v'] ?? 0),
            'won_usd'       => (float) (Database::one("SELECT COALESCE(SUM(est_value_usd),0) v FROM leads WHERE status='won'")['v'] ?? 0),
            'rumours_new'   => (int) (Database::one("SELECT COUNT(*) n FROM rumours WHERE triage_status='new'")['n'] ?? 0),
            'subscribers'   => (int) (Database::one('SELECT COUNT(*) n FROM subscribers')['n'] ?? 0),
            'articles'      => (int) (Database::one("SELECT COUNT(*) n FROM articles WHERE status='published'")['n'] ?? 0),
        ];

        // Pipeline par type d'intake (monétisation par pilier)
        $byType = Database::all(
            'SELECT intake_type, COUNT(*) n, COALESCE(SUM(est_value_usd),0) v
               FROM leads GROUP BY intake_type ORDER BY v DESC'
        );

        $recent = Database::all('SELECT * FROM leads ORDER BY created_at DESC LIMIT 8');

        $this->view('admin/dashboard', [
            'title'  => 'Tableau de bord — Console',
            'stats'  => $stats,
            'byType' => $byType,
            'recent' => $recent,
        ], 'admin');
    }
}
