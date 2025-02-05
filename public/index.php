<?php
require_once '../app/core/Database.php';
require '../vendor/autoload.php';

require '../app/routes/index.php';



// Exécuter le routeur
$router->dispatch();