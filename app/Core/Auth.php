<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Authentification & contrôle d'accès basé sur les rôles (RBAC).
 */
final class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $user = Database::one(
            'SELECT * FROM users WHERE email = ? AND is_active = 1',
            [$email]
        );
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['uid']    = (int) $user['id'];
            $_SESSION['uname']  = $user['full_name'];
            $_SESSION['urole']  = $user['role'];
            Database::exec('UPDATE users SET last_login_at = NOW() WHERE id = ?', [$user['id']]);
            Audit::log('login', 'user', (string) $user['id']);
            return true;
        }
        return false;
    }

    public static function check(): bool
    {
        return isset($_SESSION['uid']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        return [
            'id'   => $_SESSION['uid'],
            'name' => $_SESSION['uname'],
            'role' => $_SESSION['urole'],
        ];
    }

    /** Bloque l'accès si non connecté ou rôle insuffisant. */
    public static function require(array $roles = []): void
    {
        if (!self::check()) {
            header('Location: /admin/login');
            exit;
        }
        if ($roles && !in_array($_SESSION['urole'], $roles, true)) {
            http_response_code(403);
            exit('403 — Accès refusé / Forbidden');
        }
    }

    public static function logout(): void
    {
        Audit::log('logout', 'user', (string) ($_SESSION['uid'] ?? ''));
        session_destroy();
    }
}
