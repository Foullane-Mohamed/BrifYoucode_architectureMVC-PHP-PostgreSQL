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

        $this->twig->addGlobal('session', $_SESSION);
    }

    public function render($view, $data = []) 
    {
        if ($this->twig === null) {
            throw new \Exception('Twig n\'est pas initialisé');
        }
        
        $viewTemplate = strpos($view, '.twig') !== false ? $view : $view . '.twig';
        
        echo $this->twig->render($viewTemplate, $data);
    }

    public function view($view, $data = [])
    {
        $this->render($view, $data);
    }
}