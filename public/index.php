<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$router = new App\Core\Router();

require_once __DIR__ . '/../routes/web.php';