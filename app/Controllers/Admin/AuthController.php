<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Csrf;

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

        if (Auth::attempt($email, $pass)) {
            $this->redirect('/admin');
        }
        $this->view('admin/login', [
            'title' => 'Connexion — Console ERID-AMRAfrica',
            'error' => 'Identifiants invalides / Invalid credentials',
        ], 'admin_blank');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/admin/login');
    }
}
