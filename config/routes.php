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
use App\Controllers\Public\MediaController as PublicMediaController;
use App\Controllers\Public\PublicationsController as PublicPublicationsController;
use App\Controllers\Public\PageController;
use App\Controllers\Public\ClassroomController;
use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ContentController;
use App\Controllers\Admin\LeadsController;
use App\Controllers\Admin\MediaController as AdminMediaController;
use App\Controllers\Admin\PublicationsController as AdminPublicationsController;
use App\Controllers\Admin\PagesController;
use App\Controllers\Admin\AuditController;
use App\Controllers\Admin\SubscribersController;
use App\Controllers\Admin\EmailTemplatesController;
use App\Controllers\Admin\ClassroomController as AdminClassroomController;
use App\Controllers\Admin\UsersController;

// ----------------------- SITE PUBLIC -----------------------
$router->get('/',                 [HomeController::class, 'index']);
$router->get('/news',             [NewsController::class, 'index']);
$router->get('/news/{slug}',      [NewsController::class, 'show']);
$router->get('/services',         [ServicesController::class, 'index']);
$router->get('/pricing',          [ServicesController::class, 'pricing']);
$router->get('/media',            [PublicMediaController::class, 'index']);
$router->get('/publications',     [PublicPublicationsController::class, 'index']);
$router->get('/publications/{id}/download', [PublicPublicationsController::class, 'download']);
$router->get('/page/{slug}',      [PageController::class, 'show']);
$router->get('/classroom',        [ClassroomController::class, 'index']);
$router->get('/classroom/{id}',   [ClassroomController::class, 'show']);

// Portails d'intake (CTA → CRM + e-mail de triage automatique)
$router->get('/intake/{pillar}',  [IntakeController::class, 'form']);
$router->post('/intake',          [IntakeController::class, 'submit']);
$router->get('/signal',           [IntakeController::class, 'rumourForm']);
$router->post('/rumour',          [IntakeController::class, 'rumour']);
$router->post('/subscribe',       [IntakeController::class, 'subscribe']);

// ----------------------- CONSOLE D'ADMINISTRATION -----------------------
$router->get('/admin/login',      [AuthController::class, 'showLogin']);
$router->post('/admin/login',     [AuthController::class, 'login']);
$router->get('/admin/logout',     [AuthController::class, 'logout']);
$router->get('/admin/password',   [AuthController::class, 'showPassword']);
$router->post('/admin/password',  [AuthController::class, 'changePassword']);

$router->get('/admin',            [DashboardController::class, 'index']);

// CMS — Articles
$router->get('/admin/articles',              [ContentController::class, 'articles']);
$router->get('/admin/articles/new',          [ContentController::class, 'createArticle']);
$router->post('/admin/articles',             [ContentController::class, 'storeArticle']);
$router->get('/admin/articles/{id}/edit',    [ContentController::class, 'editArticle']);
$router->post('/admin/articles/{id}',        [ContentController::class, 'updateArticle']);
$router->post('/admin/articles/{id}/delete', [ContentController::class, 'deleteArticle']);

// CMS — Services
$router->get('/admin/services',           [ContentController::class, 'services']);
$router->post('/admin/services/{id}',     [ContentController::class, 'updateService']);
$router->get('/admin/settings',           [ContentController::class, 'settings']);
$router->post('/admin/settings',          [ContentController::class, 'updateSettings']);

// CMS — Médiathèque
$router->get('/admin/media',              [AdminMediaController::class, 'index']);
$router->get('/admin/media/new',          [AdminMediaController::class, 'create']);
$router->post('/admin/media',             [AdminMediaController::class, 'store']);
$router->get('/admin/media/{id}/edit',    [AdminMediaController::class, 'edit']);
$router->post('/admin/media/{id}',        [AdminMediaController::class, 'update']);
$router->post('/admin/media/{id}/delete', [AdminMediaController::class, 'delete']);

// CMS — Publications
$router->get('/admin/publications',              [AdminPublicationsController::class, 'index']);
$router->get('/admin/publications/new',          [AdminPublicationsController::class, 'create']);
$router->post('/admin/publications',             [AdminPublicationsController::class, 'store']);
$router->get('/admin/publications/{id}/edit',    [AdminPublicationsController::class, 'edit']);
$router->post('/admin/publications/{id}',        [AdminPublicationsController::class, 'update']);
$router->post('/admin/publications/{id}/delete', [AdminPublicationsController::class, 'delete']);

// CMS — Pages statiques
$router->get('/admin/pages',              [PagesController::class, 'index']);
$router->get('/admin/pages/new',          [PagesController::class, 'create']);
$router->post('/admin/pages',             [PagesController::class, 'store']);
$router->get('/admin/pages/{id}/edit',    [PagesController::class, 'edit']);
$router->post('/admin/pages/{id}',        [PagesController::class, 'update']);
$router->post('/admin/pages/{id}/delete', [PagesController::class, 'delete']);

// Journal d'audit
$router->get('/admin/audit',              [AuditController::class, 'index']);

// Abonnés
$router->get('/admin/subscribers',              [SubscribersController::class, 'index']);
$router->get('/admin/subscribers/export',       [SubscribersController::class, 'export']);
$router->get('/admin/subscribers/{id}',         [SubscribersController::class, 'show']);
$router->post('/admin/subscribers/{id}',        [SubscribersController::class, 'update']);
$router->post('/admin/subscribers/{id}/delete', [SubscribersController::class, 'delete']);

// Templates email
$router->get('/admin/email-templates',              [EmailTemplatesController::class, 'index']);
$router->get('/admin/email-templates/new',          [EmailTemplatesController::class, 'create']);
$router->post('/admin/email-templates',             [EmailTemplatesController::class, 'store']);
$router->get('/admin/email-templates/{id}/edit',    [EmailTemplatesController::class, 'edit']);
$router->post('/admin/email-templates/{id}',        [EmailTemplatesController::class, 'update']);
$router->post('/admin/email-templates/{id}/delete', [EmailTemplatesController::class, 'delete']);

// CMS — Classroom / Academy
$router->get('/admin/courses',              [AdminClassroomController::class, 'index']);
$router->get('/admin/courses/new',          [AdminClassroomController::class, 'create']);
$router->post('/admin/courses',             [AdminClassroomController::class, 'store']);
$router->get('/admin/courses/{id}/edit',    [AdminClassroomController::class, 'edit']);
$router->post('/admin/courses/{id}',        [AdminClassroomController::class, 'update']);
$router->post('/admin/courses/{id}/delete', [AdminClassroomController::class, 'delete']);

// Gestion des utilisateurs (superadmin)
$router->get('/admin/users',              [UsersController::class, 'index']);
$router->get('/admin/users/new',          [UsersController::class, 'create']);
$router->post('/admin/users',             [UsersController::class, 'store']);
$router->get('/admin/users/{id}/edit',    [UsersController::class, 'edit']);
$router->post('/admin/users/{id}',        [UsersController::class, 'update']);
$router->post('/admin/users/{id}/toggle', [UsersController::class, 'toggleActive']);

// CRM — pipeline commercial & surveillance
$router->get('/admin/leads',              [LeadsController::class, 'index']);
$router->get('/admin/leads/{id}',         [LeadsController::class, 'show']);
$router->post('/admin/leads/{id}',        [LeadsController::class, 'update']);
$router->get('/admin/rumours',            [LeadsController::class, 'rumours']);
$router->get('/admin/rumours/{id}',       [LeadsController::class, 'rumourDetail']);
$router->post('/admin/rumours/{id}',      [LeadsController::class, 'updateRumour']);
