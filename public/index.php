<?php
declare(strict_types=1);

/**
 * Front controller — point d'entrée unique de la plateforme.
 * Tout le trafic public et admin transite par ce fichier.
 */

session_start();

define('APP_ROOT', dirname(__DIR__));

// --- Autoloader PSR-4 minimal pour App\ ---
spl_autoload_register(function (string $class): void {
    if (!str_starts_with($class, 'App\\')) {
        return;
    }
    $path = APP_ROOT . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

use App\Core\Config;
use App\Core\Lang;
use App\Core\Router;

Config::load(APP_ROOT);
Lang::boot();

// En-têtes de sécurité de base
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

$router = new Router();
require APP_ROOT . '/config/routes.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
