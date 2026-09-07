<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Authentification & contrôle d'accès basé sur les rôles (RBAC).
 * Inclut le rate limiting (verrouillage après 5 tentatives échouées).
 */
final class Auth
{
    private const MAX_ATTEMPTS   = 5;
    private const LOCKOUT_MINUTES = 15;

    public static function attempt(string $email, string $password): bool|string
    {
        $user = Database::one('SELECT * FROM users WHERE email = ?', [$email]);

        // Utilisateur inexistant
        if (!$user) {
            Audit::log('login_failed', 'user', null, ['email' => $email, 'reason' => 'unknown_email']);
            return 'invalid';
        }

        // Compte inactif
        if (!$user['is_active']) {
            Audit::log('login_failed', 'user', (string) $user['id'], ['reason' => 'inactive']);
            return 'inactive';
        }

        // Compte verrouillé
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $remaining = (int) ceil((strtotime($user['locked_until']) - time()) / 60);
            Audit::log('login_failed', 'user', (string) $user['id'], ['reason' => 'locked']);
            return 'locked:' . $remaining;
        }

        // Mot de passe incorrect
        if (!password_verify($password, $user['password_hash'])) {
            $attempts = (int) $user['failed_attempts'] + 1;
            if ($attempts >= self::MAX_ATTEMPTS) {
                $lockUntil = date('Y-m-d H:i:s', time() + self::LOCKOUT_MINUTES * 60);
                Database::exec(
                    'UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?',
                    [$attempts, $lockUntil, $user['id']]
                );
                Audit::log('login_failed', 'user', (string) $user['id'], ['reason' => 'locked_after_attempts', 'attempts' => $attempts]);
                return 'locked:' . self::LOCKOUT_MINUTES;
            }
            Database::exec('UPDATE users SET failed_attempts = ? WHERE id = ?', [$attempts, $user['id']]);
            Audit::log('login_failed', 'user', (string) $user['id'], ['reason' => 'bad_password', 'attempts' => $attempts]);
            return 'invalid';
        }

        // Succès — réinitialiser les compteurs
        session_regenerate_id(true);
        $_SESSION['uid']           = (int) $user['id'];
        $_SESSION['uname']         = $user['full_name'];
        $_SESSION['urole']         = $user['role'];
        $_SESSION['last_activity'] = time();
        Database::exec(
            'UPDATE users SET last_login_at = NOW(), failed_attempts = 0, locked_until = NULL WHERE id = ?',
            [$user['id']]
        );
        Audit::log('login', 'user', (string) $user['id']);
        return true;
    }

    public static function check(): bool
    {
        if (!isset($_SESSION['uid'])) {
            return false;
        }
        // Session timeout (30 minutes d'inactivité)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
            self::logout();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
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
            Audit::log('access_denied', 'route', $_SERVER['REQUEST_URI'] ?? '', ['role' => $_SESSION['urole']]);
            http_response_code(403);
            exit('403 — Accès refusé / Forbidden');
        }
    }

    public static function logout(): void
    {
        Audit::log('logout', 'user', (string) ($_SESSION['uid'] ?? ''));
        session_unset();
        session_destroy();
    }
}
