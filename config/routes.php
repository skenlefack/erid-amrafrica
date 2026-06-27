<?php
declare(strict_types=1);

/**
 * Définition des routes.  $router est fourni par public/index.php
 *
 * @var \App\Core\Router $router
 */

use App\Controllers\Public\HomeController;
use App\Controllers\Public\NewsController;
use App\Controllers\Public\ServicesController;
use App\Controllers\Public\IntakeController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ContentController;
use App\Controllers\Admin\LeadsController;

// ----------------------- SITE PUBLIC -----------------------
$router->get('/',                 [HomeController::class, 'index']);
$router->get('/news',             [NewsController::class, 'index']);
$router->get('/news/{slug}',      [NewsController::class, 'show']);
$router->get('/services',         [ServicesController::class, 'index']);
$router->get('/pricing',          [ServicesController::class, 'pricing']);

// Portails d'intake (CTA → CRM + e-mail de triage automatique)
$router->get('/intake/{pillar}',  [IntakeController::class, 'form']);
$router->post('/intake',          [IntakeController::class, 'submit']);
$router->post('/rumour',          [IntakeController::class, 'rumour']);
$router->post('/subscribe',       [IntakeController::class, 'subscribe']);

// ----------------------- CONSOLE D'ADMINISTRATION -----------------------
$router->get('/admin/login',      [AuthController::class, 'showLogin']);
$router->post('/admin/login',     [AuthController::class, 'login']);
$router->get('/admin/logout',     [AuthController::class, 'logout']);

$router->get('/admin',            [DashboardController::class, 'index']);

// CMS — pilote tout le site public
$router->get('/admin/articles',           [ContentController::class, 'articles']);
$router->get('/admin/articles/new',       [ContentController::class, 'createArticle']);
$router->post('/admin/articles',          [ContentController::class, 'storeArticle']);
$router->get('/admin/services',           [ContentController::class, 'services']);
$router->post('/admin/services/{id}',     [ContentController::class, 'updateService']);
$router->get('/admin/settings',           [ContentController::class, 'settings']);
$router->post('/admin/settings',          [ContentController::class, 'updateSettings']);

// CRM — pipeline commercial & surveillance
$router->get('/admin/leads',              [LeadsController::class, 'index']);
$router->get('/admin/leads/{id}',         [LeadsController::class, 'show']);
$router->post('/admin/leads/{id}',        [LeadsController::class, 'update']);
$router->get('/admin/rumours',            [LeadsController::class, 'rumours']);
