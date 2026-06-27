<?php
declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

/**
 * Portfolio de services — 3 piliers + grille tarifaire (monétisation).
 */
final class ServicesController extends Controller
{
    public function index(): void
    {
        $services = Database::all('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order');
        $this->view('public/services', [
            'title'    => 'Services & Portfolio — ERID-AMRAfrica',
            'services' => $services,
        ], 'public');
    }

    public function pricing(): void
    {
        $services = Database::all('SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order');
        $this->view('public/pricing', [
            'title'    => 'Offres & Tarification — ERID-AMRAfrica',
            'services' => $services,
        ], 'public');
    }
}
