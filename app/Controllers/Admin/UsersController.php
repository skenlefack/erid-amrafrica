<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Csrf;
use App\Core\Audit;

/**
 * Gestion des comptes utilisateurs — superadmin uniquement.
 */
final class UsersController extends Controller
{
    public function index(): void
    {
        Auth::require(['superadmin']);

        $filter = $this->input('role');
        $search = $this->input('q');
        $where  = '1=1';
        $params = [];

        if ($filter) {
            $where .= ' AND role = ?';
            $params[] = $filter;
        }
        if ($search) {
            $where .= ' AND (full_name LIKE ? OR email LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $page = max(1, (int) ($this->input('page') ?: 1));
        [$users, $page, $totalPages] = Database::paginate(
            "SELECT * FROM users WHERE {$where} ORDER BY created_at DESC",
            $params, $page, 20
        );

        $this->view('admin/users', [
            'title'      => 'Utilisateurs',
            'users'      => $users,
            'filter'     => $filter,
            'search'     => $search,
            'page'       => $page,
            'totalPages' => $totalPages,
        ], 'admin');
    }

    public function create(): void
    {
        Auth::require(['superadmin']);
        $this->view('admin/user_form', ['title' => 'Nouvel utilisateur'], 'admin');
    }

    public function store(): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        $email    = trim($this->input('email', ''));
        $name     = trim($this->input('full_name', ''));
        $role     = $this->input('role', 'consultant');
        $locale   = $this->input('locale', 'fr');
        $password = $this->input('password', '');

        // Validation
        $errors = [];
        if (!$name) { $errors[] = 'Le nom est requis.'; }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Adresse e-mail invalide.'; }
        if (mb_strlen($password) < 8) { $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.'; }
        if (!in_array($role, ['superadmin', 'editor', 'consultant', 'analyst'], true)) { $errors[] = 'Rôle invalide.'; }

        if (Database::exists('users', 'email = ?', [$email])) {
            $errors[] = 'Cette adresse e-mail est déjà utilisée.';
        }

        if ($errors) {
            $this->view('admin/user_form', [
                'title'  => 'Nouvel utilisateur',
                'errors' => $errors,
                'old'    => $_POST,
            ], 'admin');
            return;
        }

        $id = Database::exec(
            'INSERT INTO users (full_name, email, password_hash, role, locale, is_active) VALUES (?, ?, ?, ?, ?, 1)',
            [$name, $email, password_hash($password, PASSWORD_BCRYPT), $role, $locale]
        );

        Audit::log('create', 'user', (string) $id, ['role' => $role]);
        $this->redirect('/admin/users', 'Utilisateur créé avec succès.');
    }

    public function edit(string $id): void
    {
        Auth::require(['superadmin']);
        $user = Database::one('SELECT * FROM users WHERE id = ?', [(int) $id]);
        if (!$user) { http_response_code(404); return; }

        $this->view('admin/user_form', [
            'title' => 'Éditer l\'utilisateur',
            'user'  => $user,
        ], 'admin');
    }

    public function update(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        $user = Database::one('SELECT * FROM users WHERE id = ?', [(int) $id]);
        if (!$user) { http_response_code(404); return; }

        $email    = trim($this->input('email', ''));
        $name     = trim($this->input('full_name', ''));
        $role     = $this->input('role', $user['role']);
        $locale   = $this->input('locale', $user['locale']);
        $isActive = (int) $this->input('is_active', '1');
        $password = $this->input('password', '');

        $errors = [];
        if (!$name) { $errors[] = 'Le nom est requis.'; }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'Adresse e-mail invalide.'; }
        if (!in_array($role, ['superadmin', 'editor', 'consultant', 'analyst'], true)) { $errors[] = 'Rôle invalide.'; }

        // Vérifier unicité email si modifié
        if ($email !== $user['email'] && Database::exists('users', 'email = ? AND id != ?', [$email, (int) $id])) {
            $errors[] = 'Cette adresse e-mail est déjà utilisée.';
        }

        // Validation mot de passe si fourni
        if ($password && mb_strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        // Empêcher de se désactiver soi-même
        if ((int) $id === ($_SESSION['uid'] ?? 0) && !$isActive) {
            $errors[] = 'Vous ne pouvez pas désactiver votre propre compte.';
        }

        if ($errors) {
            $this->view('admin/user_form', [
                'title'  => 'Éditer l\'utilisateur',
                'user'   => array_merge($user, $_POST),
                'errors' => $errors,
            ], 'admin');
            return;
        }

        Database::exec(
            'UPDATE users SET full_name=?, email=?, role=?, locale=?, is_active=? WHERE id=?',
            [$name, $email, $role, $locale, $isActive, (int) $id]
        );

        // Mettre à jour le mot de passe si fourni
        if ($password) {
            Database::exec(
                'UPDATE users SET password_hash = ? WHERE id = ?',
                [password_hash($password, PASSWORD_BCRYPT), (int) $id]
            );
        }

        Audit::log('update', 'user', $id, ['role' => $role, 'active' => $isActive]);
        $this->redirect('/admin/users', 'Utilisateur mis à jour.');
    }

    public function toggleActive(string $id): void
    {
        Auth::require(['superadmin']);
        Csrf::verify();

        // Empêcher de se désactiver soi-même
        if ((int) $id === ($_SESSION['uid'] ?? 0)) {
            $this->redirect('/admin/users');
            return;
        }

        $user = Database::one('SELECT is_active FROM users WHERE id = ?', [(int) $id]);
        if (!$user) { http_response_code(404); return; }

        $newStatus = $user['is_active'] ? 0 : 1;
        Database::exec('UPDATE users SET is_active = ? WHERE id = ?', [$newStatus, (int) $id]);

        Audit::log('update', 'user', $id, ['is_active' => $newStatus]);
        $this->redirect('/admin/users');
    }
}
