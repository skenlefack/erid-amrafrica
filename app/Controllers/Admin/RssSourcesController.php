<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

/**
 * CRUD Sources RSS — gestion des flux à surveiller pour le signal capture.
 */
final class RssSourcesController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin', 'analyst']);
        $sources = Database::all('SELECT * FROM rss_sources ORDER BY name');
        $this->view('admin/rss_sources', ['title' => 'Sources RSS', 'sources' => $sources], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin']);
        $this->view('admin/rss_source_form', ['title' => 'Nouvelle source RSS'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        $id = Database::exec(
            'INSERT INTO rss_sources (name, url, source_type, is_active, poll_interval) VALUES (?, ?, ?, ?, ?)',
            [
                $this->input('name', ''),
                $this->input('url', ''),
                $this->input('source_type', 'rss'),
                $this->input('is_active') ? 1 : 0,
                (int) $this->input('poll_interval', '360'),
            ]
        );
        Audit::log('create', 'rss_source', (string) $id);
        $this->redirect('/admin/rss-sources', 'Source RSS créée avec succès.');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin']);
        $source = Database::one('SELECT * FROM rss_sources WHERE id = ?', [(int) $id]);
        if (!$source) { http_response_code(404); return; }
        $this->view('admin/rss_source_form', ['title' => 'Éditer la source RSS', 'source' => $source], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        Database::exec(
            'UPDATE rss_sources SET name=?, url=?, source_type=?, is_active=?, poll_interval=? WHERE id=?',
            [
                $this->input('name', ''),
                $this->input('url', ''),
                $this->input('source_type', 'rss'),
                $this->input('is_active') ? 1 : 0,
                (int) $this->input('poll_interval', '360'),
                (int) $id,
            ]
        );
        Audit::log('update', 'rss_source', $id);
        $this->redirect('/admin/rss-sources', 'Source RSS mise à jour.');
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        Database::exec('DELETE FROM rss_sources WHERE id = ?', [(int) $id]);
        Audit::log('delete', 'rss_source', $id);
        $this->redirect('/admin/rss-sources', 'Source RSS supprimée.');
    }
}
