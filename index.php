<?php
require_once('config/Database.php');
require_once('models/Article.php');
require_once('controllers/ArticleController.php');

$database = new Database();
$db = $database->getConnection();

$articleController = new ArticleController($db);
$articleController->index();