<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

final class EmailTemplatesController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin']);
        $templates = Database::all('SELECT * FROM email_templates ORDER BY template_key');
        $this->view('admin/email_templates', ['title' => 'Templates email', 'templates' => $templates], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin']);
        $this->view('admin/email_template_form', ['title' => 'Nouveau template'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        $id = Database::exec(
            'INSERT INTO email_templates (template_key, subject_fr, subject_en, body_fr, body_en) VALUES (?, ?, ?, ?, ?)',
            [
                $this->input('template_key', ''),
                $this->input('subject_fr', ''),
                $this->input('subject_en', ''),
                $this->input('body_fr', ''),
                $this->input('body_en', ''),
            ]
        );
        Audit::log('create', 'email_template', (string) $id);
        $this->redirect('/admin/email-templates');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin']);
        $tpl = Database::one('SELECT * FROM email_templates WHERE id = ?', [(int) $id]);
        if (!$tpl) { http_response_code(404); return; }
        $this->view('admin/email_template_form', ['title' => 'Éditer le template', 'tpl' => $tpl], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        Database::exec(
            'UPDATE email_templates SET subject_fr=?, subject_en=?, body_fr=?, body_en=? WHERE id=?',
            [
                $this->input('subject_fr', ''),
                $this->input('subject_en', ''),
                $this->input('body_fr', ''),
                $this->input('body_en', ''),
                (int) $id,
            ]
        );
        Audit::log('update', 'email_template', $id);
        $this->redirect('/admin/email-templates');
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        Database::exec('DELETE FROM email_templates WHERE id = ?', [(int) $id]);
        Audit::log('delete', 'email_template', $id);
        $this->redirect('/admin/email-templates');
    }
}
