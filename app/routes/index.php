<?php
use App\core\Router;
use App\controllers\articleController;

$router = new Router();


$router->get('/', ArticleController::class, 'index');
$router->get('/article', ArticleController::class, 'index');

