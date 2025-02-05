<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\InputValidator;
use App\Core\Security;
use App\Core\SessionManager;
use App\Models\User;

class AuthController extends Controller
{
    private $userModel;
    private $validator;
    private $sessionManager;

    public function __construct() 
    {
        parent::__construct();
        $this->userModel = new User();
        $this->validator = new InputValidator();
        $this->sessionManager = new SessionManager();
    }

    // Display login form
    public function login()
    {
        // Generate CSRF token
        $csrfToken = Security::generateCSRFToken();
        $this->render('auth/login', ['csrf_token' => $csrfToken]);
    }

    // Process login
    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        // Validate CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Security::validateCSRFToken($csrfToken)) {
            $errors['csrf'] = 'Invalid CSRF token';
            $this->render('auth/login', ['errors' => $errors]);
            return;
        }

        // Validate inputs
        $email = $this->validator->email($_POST['email']);
        $password = $this->validator->text($_POST['password'], 255, false);

        if ($this->validator->fails()) {
            $errors = $this->validator->getErrors();
            $this->render('auth/login', ['errors' => $errors]);
            return;
        }

        // Attempt login
        $user = $this->userModel->login($email, $_POST['password']);
        if ($user) {
            // Login successful
            $this->sessionManager->login($user['id']);
            header('Location: /');
            exit;
        } else {
            // Login failed
            $errors['login'] = 'Invalid email or password';
            $this->render('auth/login', ['errors' => $errors]);
        }
    }

    // Display registration form
    public function register()
    {
        // Generate CSRF token
        $csrfToken = Security::generateCSRFToken();
        $this->render('auth/register', ['csrf_token' => $csrfToken]);
    }

    // Process registration
    public function processRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        // Validate CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Security::validateCSRFToken($csrfToken)) {
            $errors['csrf'] = 'Invalid CSRF token';
            $this->render('auth/register', ['errors' => $errors]);
            return;
        }

        // Validate inputs
        $username = $this->validator->text($_POST['username'], 50);
        $email = $this->validator->email($_POST['email']);
        $password = $this->validator->password($_POST['password']);
        $confirmPassword = $_POST['confirm_password'];

        // Check password confirmation
        if ($password !== $confirmPassword) {
            $this->validator->getErrors()['confirm_password'] = 'Passwords do not match';
        }

        if ($this->validator->fails()) {
            $errors = $this->validator->getErrors();
            $this->render('auth/register', ['errors' => $errors]);
            return;
        }

        // Attempt registration
        $registered = $this->userModel->register($username, $email, $password);
        if ($registered) {
            // Registration successful, redirect to login
            header('Location: /login');
            exit;
        } else {
            $errors['register'] = 'Registration failed. Email might already be in use.';
            $this->render('auth/register', ['errors' => $errors]);
        }
    }

    // Logout
    public function logout()
    {
        $this->sessionManager->destroy();
        header('Location: /login');
        exit;
    }
}