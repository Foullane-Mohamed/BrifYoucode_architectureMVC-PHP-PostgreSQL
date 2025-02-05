<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    private $user;

    public function __construct() {
        parent::__construct();
        $this->user = new User();
    }

    public function showLoginForm()
    {
        $this->render('auth/login');
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->user->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $this->redirect('/articles');
        }

        $this->redirect('/login?error=1');
    }

    public function showRegisterForm()
    {
        $this->render('auth/register');
    }

    public function register()
    {
        $this->user->username = $_POST['username'];
        $this->user->email = $_POST['email'];
        $this->user->password = $_POST['password'];

        if ($this->user->create()) {
            $this->redirect('/login?registered=1');
        }

        $this->redirect('/register?error=1');
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/login');
    }
}