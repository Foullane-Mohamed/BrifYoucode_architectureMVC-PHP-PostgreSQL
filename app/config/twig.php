<?php
require_once DIR . '/../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(DIR . '/../views');
$twig = new \Twig\Environment($loader, [
    'cache' => DIR . '/../cache', 
    'debug' => true, 
]);

return $twig;