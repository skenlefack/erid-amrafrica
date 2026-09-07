<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], string $layout = 'public'): void
    {
        View::render($template, $data, $layout);
    }

    protected function redirect(string $to, ?string $flash = null, string $type = 'success'): void
    {
        if ($flash) {
            $_SESSION['_flash'] = ['message' => $flash, 'type' => $type];
        }
        header('Location: ' . $to);
        exit;
    }

    protected function input(string $key, ?string $default = null): ?string
    {
        $v = $_POST[$key] ?? $_GET[$key] ?? $default;
        return is_string($v) ? trim($v) : $default;
    }

    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
