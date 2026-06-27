<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Internationalisation FR / EN.
 * La locale est stockée en session ; bascule via ?lang=fr|en.
 */
final class Lang
{
    private static array $strings = [];
    private static string $locale = 'fr';

    public static function boot(): void
    {
        if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'], true)) {
            $_SESSION['locale'] = $_GET['lang'];
        }
        self::$locale = $_SESSION['locale'] ?? Config::get('app')['locale'] ?? 'fr';
        $file = Config::get('root') . '/app/lang/' . self::$locale . '.php';
        self::$strings = is_file($file) ? require $file : [];
    }

    public static function current(): string
    {
        return self::$locale;
    }

    /** Traduit une clé. */
    public static function t(string $key): string
    {
        return self::$strings[$key] ?? $key;
    }

    /** Choisit la bonne colonne bilingue d'une ligne BDD (ex: title_fr / title_en). */
    public static function pick(array $row, string $base): string
    {
        return (string) ($row[$base . '_' . self::$locale] ?? $row[$base . '_fr'] ?? '');
    }
}
