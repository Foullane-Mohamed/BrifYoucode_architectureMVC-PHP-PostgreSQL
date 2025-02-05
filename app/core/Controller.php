<?php
namespace App\Core;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Controller 
{
    protected $twig;

    public function __construct() 
    {
        $loader = new FilesystemLoader(__DIR__ . '/../views');
        $this->twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
            'auto_reload' => true
        ]);
    }

    public function render($view, $data = []) 
    {
        if ($this->twig === null) {
            throw new \Exception('Twig n\'est pas initialisé');
        }
        echo $this->twig->render($view . '.twig', $data);
    }
}