<?php

namespace App\Core;

class Controller {
    protected $twig;
    protected $request;

    public function __construct() {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => false, 
            'debug' => true,
            'auto_reload' => true
        ]);
        
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('app', [
            'request' => ['get' => $_GET]
        ]);
        
        $this->request = new Request();
    }

    protected function render($view, $data = []) {
        echo $this->twig->render($view . '.twig', $data);
    }

    protected function redirect($url) {
        $basePath = '/project/public';
        $url = $basePath . $url;
        
        header('Location: ' . $url);
        exit;
    }

    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    protected function getUser() {
        return isset($_SESSION['user_id']) ? [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username']
        ] : null;
    }
}