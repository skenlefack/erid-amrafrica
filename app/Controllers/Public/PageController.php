<?php
declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Core\Database;

final class PageController extends Controller
{
    public function show(string $slug): void
    {
        $page = Database::one(
            "SELECT * FROM pages WHERE slug = ? AND status = 'published'",
            [$slug]
        );
        if (!$page) {
            http_response_code(404);
            $this->view('public/404', ['title' => '404'], 'public');
            return;
        }
        $this->view('public/page', [
            'title' => $page['title_fr'],
            'page'  => $page,
        ], 'public');
    }
}
