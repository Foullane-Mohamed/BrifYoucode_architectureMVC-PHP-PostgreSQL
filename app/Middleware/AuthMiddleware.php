<?php
namespace App\Middleware;

use App\Core\SessionManager;

class AuthMiddleware 
{
    private $sessionManager;

    public function __construct() 
    {
        $this->sessionManager = new SessionManager();
    }

    public function checkAuth() 
    {
        if (!$this->sessionManager->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }

    public function checkGuest() 
    {
        if ($this->sessionManager->isLoggedIn()) {
            header('Location: /');
            exit;
        }
    }
}