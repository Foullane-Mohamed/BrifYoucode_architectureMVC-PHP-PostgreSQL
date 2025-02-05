<?php

use App\Controllers\ArticleController;
use App\Controllers\AuthController;

$router->get('/login', AuthController::class, 'showLoginForm');
$router->post('/login', AuthController::class, 'login');
$router->get('/register', AuthController::class, 'showRegisterForm');
$router->post('/register', AuthController::class, 'register');
$router->get('/logout', AuthController::class, 'logout');

$router->get('/', ArticleController::class, 'index');
$router->get('/articles', ArticleController::class, 'index');
$router->get('/articles/create', ArticleController::class, 'create');
$router->post('/articles', ArticleController::class, 'store');
$router->get('/articles/{id}', ArticleController::class, 'show');
$router->get('/articles/{id}/edit', ArticleController::class, 'edit');
$router->post('/articles/{id}', ArticleController::class, 'update');
$router->get('/articles/{id}/delete', ArticleController::class, 'delete');
