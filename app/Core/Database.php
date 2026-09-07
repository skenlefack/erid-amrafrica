<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Couche d'accès aux données — MariaDB via PDO.
 * Singleton ; requêtes préparées uniquement (anti-injection SQL).
 */
final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $cfg = Config::get('db');
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $cfg['host'], $cfg['port'], $cfg['name']
            );
            try {
                self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                exit('Database connection failed.');
            }
        }
        return self::$pdo;
    }

    /** Retourne toutes les lignes. */
    public static function all(string $sql, array $params = []): array
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Retourne une seule ligne (ou null). */
    public static function one(string $sql, array $params = []): ?array
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Exécute INSERT/UPDATE/DELETE ; retourne le dernier id inséré. */
    public static function exec(string $sql, array $params = []): int
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return (int) self::pdo()->lastInsertId();
    }

    /** Retourne le nombre de lignes correspondant à la requête. */
    public static function count(string $table, string $where = '1=1', array $params = []): int
    {
        return (int) self::one("SELECT COUNT(*) AS c FROM {$table} WHERE {$where}", $params)['c'];
    }

    /** Vérifie l'existence d'au moins une ligne. */
    public static function exists(string $table, string $where, array $params = []): bool
    {
        return self::one("SELECT 1 FROM {$table} WHERE {$where} LIMIT 1", $params) !== null;
    }

    /** Pagine une requête et retourne [items, page, totalPages]. */
    public static function paginate(string $sql, array $params, int $page, int $perPage = 20): array
    {
        $countSql = preg_replace('/^SELECT .+? FROM/is', 'SELECT COUNT(*) AS c FROM', $sql);
        $countSql = preg_replace('/\s+ORDER BY .+$/i', '', $countSql);
        $total = (int) self::one($countSql, $params)['c'];
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;
        $items = self::all($sql . " LIMIT {$perPage} OFFSET {$offset}", $params);
        return [$items, $page, $totalPages, $total];
    }

    /** Démarre une transaction. */
    public static function beginTransaction(): void
    {
        self::pdo()->beginTransaction();
    }

    /** Valide la transaction en cours. */
    public static function commit(): void
    {
        self::pdo()->commit();
    }

    /** Annule la transaction en cours. */
    public static function rollback(): void
    {
        self::pdo()->rollBack();
    }
}
