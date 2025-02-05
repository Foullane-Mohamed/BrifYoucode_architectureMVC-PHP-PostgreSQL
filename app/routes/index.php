<?php
use App\core\Router;
use App\controllers\ArticleController;
use App\controllers\AuthController;
use App\controllers\HomeController;

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