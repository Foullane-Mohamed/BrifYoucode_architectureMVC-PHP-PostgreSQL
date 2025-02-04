<?php

require_once '../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\UserController;
use App\Controllers\ArticleController;

$router = new Router();

$router->add('/register', [UserController::class, 'register']);
$router->add('/login', [UserController::class, 'login']);
$router->add('/articles', [ArticleController::class, 'index']);
$router->add('/articles/create', [ArticleController::class, 'create']);

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($url);