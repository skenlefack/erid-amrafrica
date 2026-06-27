<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Chargeur de configuration minimal (lit le fichier .env à la racine).
 * Évite toute dépendance externe pour le starter kit.
 */
final class Config
{
    private static array $data = [];

    public static function load(string $root): void
    {
        $envFile = $root . '/.env';
        $env = [];
        if (is_file($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                    continue;
                }
                [$k, $v] = explode('=', $line, 2);
                $env[trim($k)] = trim($v);
            }
        }

        self::$data = [
            'app' => [
                'name'   => $env['APP_NAME']   ?? 'ERID-AMRAfrica',
                'env'    => $env['APP_ENV']    ?? 'production',
                'url'    => $env['APP_URL']    ?? 'http://localhost:8080',
                'locale' => $env['APP_LOCALE'] ?? 'fr',
            ],
            'db' => [
                'host' => $env['DB_HOST'] ?? '127.0.0.1',
                'port' => $env['DB_PORT'] ?? '3306',
                'name' => $env['DB_NAME'] ?? 'erid_amrafrica',
                'user' => $env['DB_USER'] ?? 'root',
                'pass' => $env['DB_PASS'] ?? '',
            ],
            'mail' => [
                'from'      => $env['MAIL_FROM'] ?? 'consulting@erid-amrafrica.org',
                'from_name' => $env['MAIL_FROM_NAME'] ?? 'ERID-AMRAfrica',
            ],
            'root' => $root,
        ];
    }

    public static function get(string $key): mixed
    {
        return self::$data[$key] ?? null;
    }
}
