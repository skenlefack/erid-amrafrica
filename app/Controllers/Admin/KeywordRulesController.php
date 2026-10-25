<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

/**
 * CRUD Règles de mots-clés — filtres bilingues pour le signal capture.
 */
final class KeywordRulesController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin', 'analyst']);
        $rules = Database::all('SELECT * FROM keyword_rules ORDER BY name');
        $this->view('admin/keyword_rules', ['title' => 'Règles mots-clés', 'rules' => $rules], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin']);
        $this->view('admin/keyword_rule_form', ['title' => 'Nouvelle règle'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        $id = Database::exec(
            'INSERT INTO keyword_rules (name, pattern, language, category, risk_weight, is_active) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $this->input('name', ''),
                $this->input('pattern', ''),
                $this->input('language', 'both'),
                $this->input('category') ?: null,
                (int) $this->input('risk_weight', '50'),
                $this->input('is_active') ? 1 : 0,
            ]
        );
        Audit::log('create', 'keyword_rule', (string) $id);
        $this->redirect('/admin/keyword-rules', 'Règle créée avec succès.');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin']);
        $rule = Database::one('SELECT * FROM keyword_rules WHERE id = ?', [(int) $id]);
        if (!$rule) { http_response_code(404); return; }
        $this->view('admin/keyword_rule_form', ['title' => 'Éditer la règle', 'rule' => $rule], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        Database::exec(
            'UPDATE keyword_rules SET name=?, pattern=?, language=?, category=?, risk_weight=?, is_active=? WHERE id=?',
            [
                $this->input('name', ''),
                $this->input('pattern', ''),
                $this->input('language', 'both'),
                $this->input('category') ?: null,
                (int) $this->input('risk_weight', '50'),
                $this->input('is_active') ? 1 : 0,
                (int) $id,
            ]
        );
        Audit::log('update', 'keyword_rule', $id);
        $this->redirect('/admin/keyword-rules', 'Règle mise à jour.');
    }

    public function delete(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();
        Database::exec('DELETE FROM keyword_rules WHERE id = ?', [(int) $id]);
        Audit::log('delete', 'keyword_rule', $id);
        $this->redirect('/admin/keyword-rules', 'Règle supprimée.');
    }
}
