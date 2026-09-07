<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Database;
use App\Core\Audit;
use App\Core\Lang;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/admin');
        }
        $this->view('admin/login', ['title' => 'Connexion — Console ERID-AMRAfrica'], 'admin_blank');
    }

    public function login(): void
    {
        Csrf::verify();
        $email = $this->input('email', '');
        $pass  = $this->input('password', '');

        $result = Auth::attempt($email, $pass);

        if ($result === true) {
            $this->redirect('/admin');
            return;
        }

        $error = match (true) {
            $result === 'inactive' => 'Compte désactivé. Contactez l\'administrateur. / Account deactivated.',
            str_starts_with((string) $result, 'locked:') => 'Compte verrouillé — réessayez dans ' . explode(':', $result)[1] . ' min. / Account locked.',
            default => 'Identifiants invalides / Invalid credentials',
        };

        $this->view('admin/login', [
            'title' => 'Connexion — Console ERID-AMRAfrica',
            'error' => $error,
        ], 'admin_blank');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/admin/login');
    }

    public function showPassword(): void
    {
        Auth::require(['superadmin', 'editor', 'consultant', 'analyst']);
        $this->view('admin/password', ['title' => Lang::t('password_title')], 'admin');
    }

    public function changePassword(): void
    {
        Auth::require(['superadmin', 'editor', 'consultant', 'analyst']);
        Csrf::verify();

        $user = Database::one('SELECT * FROM users WHERE id = ?', [Auth::user()['id']]);
        $current = $this->input('current_password', '');
        $new     = $this->input('new_password', '');
        $confirm = $this->input('confirm_password', '');

        if (!password_verify($current, $user['password_hash'])) {
            $this->view('admin/password', ['title' => Lang::t('password_title'), 'error' => Lang::t('password_error')], 'admin');
            return;
        }
        if ($new !== $confirm || strlen($new) < 8) {
            $this->view('admin/password', ['title' => Lang::t('password_title'), 'error' => Lang::t('password_mismatch')], 'admin');
            return;
        }

        Database::exec('UPDATE users SET password_hash = ? WHERE id = ?', [password_hash($new, PASSWORD_BCRYPT), $user['id']]);
        Audit::log('update', 'user_password', (string) $user['id']);
        $this->view('admin/password', ['title' => Lang::t('password_title'), 'success' => Lang::t('password_changed')], 'admin');
    }
}
