<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Journal d'audit immuable — gouvernance des données.
 * Trace chaque action sensible (login, create, update, delete, export, read).
 */
final class Audit
{
    public static function log(string $action, string $entity, ?string $entityId = null, array $meta = []): void
    {
        Database::exec(
            'INSERT INTO audit_logs (user_id, action, entity, entity_id, ip_address, meta_json)
             VALUES (?, ?, ?, ?, ?, ?)',
            [
                $_SESSION['uid'] ?? null,
                $action,
                $entity,
                $entityId,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $meta ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null,
            ]
        );
    }
}
