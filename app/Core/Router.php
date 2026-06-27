<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Routeur léger : associe méthode HTTP + chemin à un callback.
 * Supporte les segments dynamiques {id}.
 */
final class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    private function normalize(string $path): string
    {
        return '/' . trim($path, '/');
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = $this->normalize(parse_url($uri, PHP_URL_PATH) ?: '/');

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = '#^' . preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $route) . '$#';
            if (preg_match($pattern, $path, $m)) {
                array_shift($m);
                $this->invoke($handler, $m);
                return;
            }
        }

        http_response_code(404);
        View::render('public/404', ['title' => 'Introuvable / Not found'], 'public');
    }

    private function invoke(callable|array $handler, array $args): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            (new $class())->{$method}(...$args);
            return;
        }
        $handler(...$args);
    }
}
