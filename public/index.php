<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\ArticleController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();

$router->get('/', HomeController::class, 'index');

$router->get('/article', ArticleController::class, 'index');
$router->get('/article/create', ArticleController::class, 'create');
$router->post('/article/store', ArticleController::class, 'store');
$router->get('/article/edit/{id}', ArticleController::class, 'edit');
$router->post('/article/update/{id}', ArticleController::class, 'update');
$router->post('/article/delete/{id}', ArticleController::class, 'delete');

$router->get('/login', AuthController::class, 'login');
$router->post('/login', AuthController::class, 'processLogin');
$router->get('/register', AuthController::class, 'register');
$router->post('/register', AuthController::class, 'processRegister');
$router->get('/logout', AuthController::class, 'logout');

$router->dispatch();