<?php

// --- Konfigurasi CORS ---
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Tangani HTTP OPTIONS (Preflight Request dari Browser)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ------------------------

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Environment;
use App\Config\Database;
use App\Core\Container;
use App\Core\Router;
use App\Core\Request;

use App\Repositories\BookRepository;
use App\Repositories\BookRepositoryInterface;
use App\Services\BookService;
use App\Controllers\BookController;

use App\Repositories\MySQLCategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Services\CategoryService;
use App\Controllers\CategoryController;

Environment::load(__DIR__ . '/../.env');

$container = new Container();

$container->singleton(PDO::class, function () {
    return Database::getConnection();
});

$container->singleton(BookRepositoryInterface::class, function ($c) {
    return new BookRepository($c->get(PDO::class));
});

$container->singleton(BookService::class, function ($c) {
    return new BookService($c->get(BookRepositoryInterface::class));
});

$container->bind(BookController::class, function ($c) {
    return new BookController($c->get(BookService::class));
});

$container->singleton(CategoryRepositoryInterface::class, function ($c) {
    return new MySQLCategoryRepository($c->get(PDO::class));
});

$container->singleton(CategoryService::class, function ($c) {
    return new CategoryService($c->get(CategoryRepositoryInterface::class));
});

$container->bind(CategoryController::class, function ($c) {
    return new CategoryController($c->get(CategoryService::class));
});

$router = new Router($container);

$router->get('/api/books', [BookController::class, 'index']);
$router->get('/api/books/{id}', [BookController::class, 'show']);
$router->post('/api/books', [BookController::class, 'store']);
$router->put('/api/books/{id}', [BookController::class, 'update']);
$router->delete('/api/books/{id}', [BookController::class, 'destroy']);

$router->get('/api/categories', [CategoryController::class, 'index']);
$router->get('/api/categories/{id}', [CategoryController::class, 'show']);
$router->post('/api/categories', [CategoryController::class, 'store']);
$router->put('/api/categories/{id}', [CategoryController::class, 'update']);
$router->delete('/api/categories/{id}', [CategoryController::class, 'destroy']);

$request = new Request();
$router->dispatch($request);