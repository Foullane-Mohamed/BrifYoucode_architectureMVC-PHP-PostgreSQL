<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            $userModel = new User();
            if ($userModel->register($username, $password, $email)) {
                header('Location: /login');
            } else {
                echo "Registration failed";
            }
        } else {
            $this->view('register');
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new User();
            if ($userModel->login($username, $password)) {
                header('Location: /articles');
            } else {
                echo "Login failed";
            }
        } else {
            $this->view('login');
        }
    }
}