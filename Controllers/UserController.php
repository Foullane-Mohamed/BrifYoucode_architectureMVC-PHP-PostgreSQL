<?php
class UserController {
    private $user;

    public function __construct($db) {
        $this->user = new User($db);
    }

    public function register() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->email = $_POST['email'];
            $this->user->password = $_POST['password'];

            if($this->user->create()) {
                header("Location: login.php");
                exit();
            }
        }
        require_once('views/user/register.php');
    }

    public function login() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if($user = $this->user->login($email, $password)) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            }
        }
        require_once('views/user/login.php');
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}