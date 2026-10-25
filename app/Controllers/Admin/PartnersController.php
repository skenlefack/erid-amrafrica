<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

/**
 * CRUD Partenaires — gestion dynamique du réseau & footer logo bar.
 */
final class PartnersController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin', 'editor']);
        $partners = Database::all('SELECT * FROM partners ORDER BY sort_order');
        $toggle   = Database::one("SELECT value_fr FROM settings WHERE setting_key = 'footer_partners_enabled'");
        $this->view('admin/partners', [
            'title'    => 'Partenaires',
            'partners' => $partners,
            'enabled'  => ($toggle['value_fr'] ?? '1') === '1',
        ], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin']);
        $this->view('admin/partner_form', ['title' => 'Nouveau partenaire'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        $logo = $this->uploadLogo();
        $maxOrder = (int) (Database::one('SELECT MAX(sort_order) AS m FROM partners')['m'] ?? 0);

        $id = Database::exec(
            'INSERT INTO partners (name, logo_path, website_url, is_active, sort_order) VALUES (?, ?, ?, ?, ?)',
            [
                $this->input('name', ''),
                $logo,
                $this->input('website_url') ?: null,
                $this->input('is_active') ? 1 : 0,
                $maxOrder + 1,
            ]
        );
        Audit::log('create', 'partner', (string) $id);
        $this->redirect('/admin/partners', 'Partenaire créé avec succès.');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin']);
        $partner = Database::one('SELECT * FROM partners WHERE id = ?', [(int) $id]);
        if (!$partner) { http_response_code(404); return; }
        $this->view('admin/partner_form', ['title' => 'Éditer le partenaire', 'partner' => $partner], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        $logo    = $this->uploadLogo();
        $existing = Database::one('SELECT logo_path FROM partners WHERE id = ?', [(int) $id]);
        if (!$logo && $existing) {
            $logo = $existing['logo_path'];
        }

        Database::exec(
            'UPDATE partners SET name=?, logo_path=?, website_url=?, is_active=?, sort_order=? WHERE id=?',
            [
                $this->input('name', ''),
                $logo,
                $this->input('website_url') ?: null,
                $this->input('is_active') ? 1 : 0,
                (int) $this->input('sort_order', '0'),
                (int) $id,
            ]
        );
        Audit::log('update', 'partner', $id);
        $this->redirect('/admin/partners', 'Partenaire mis à jour.');
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        Database::exec('DELETE FROM partners WHERE id = ?', [(int) $id]);
        Audit::log('delete', 'partner', $id);
        $this->redirect('/admin/partners', 'Partenaire supprimé.');
    }

    public function toggleFooter(): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        $current = Database::one("SELECT value_fr FROM settings WHERE setting_key = 'footer_partners_enabled'");
        $new = ($current && $current['value_fr'] === '1') ? '0' : '1';
        Database::exec(
            "UPDATE settings SET value_fr = ?, value_en = ? WHERE setting_key = 'footer_partners_enabled'",
            [$new, $new]
        );
        Audit::log('update', 'setting', 'footer_partners_enabled');
        $this->redirect('/admin/partners', $new === '1' ? 'Barre partenaires activée.' : 'Barre partenaires désactivée.');
    }

    private function uploadLogo(): ?string
    {
        if (empty($_FILES['logo']['tmp_name']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $file = $_FILES['logo'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowed, true) || $file['size'] > 2 * 1024 * 1024) {
            return null;
        }

        $ext = match ($mime) {
            'image/jpeg'    => 'jpg',
            'image/png'     => 'png',
            'image/webp'    => 'webp',
            'image/svg+xml' => 'svg',
            default         => 'png',
        };
        $dir = APP_ROOT . '/public/uploads/partners';
        if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
        $filename = 'partner-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) {
            return null;
        }
        return '/uploads/partners/' . $filename;
    }
}
