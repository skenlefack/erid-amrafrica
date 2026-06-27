<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Moteur de vues PHP natif avec layouts (public / admin).
 */
final class View
{
    public static function render(string $template, array $data = [], string $layout = 'public'): void
    {
        $root = Config::get('root');
        extract($data, EXTR_SKIP);
        $lang = Lang::current();

        ob_start();
        require $root . '/app/Views/' . $template . '.php';
        $content = ob_get_clean();

        require $root . '/app/Views/layouts/' . $layout . '.php';
    }

    /** Échappement HTML systématique (anti-XSS). */
    public static function e(?string $s): string
    {
        return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
    }
}
