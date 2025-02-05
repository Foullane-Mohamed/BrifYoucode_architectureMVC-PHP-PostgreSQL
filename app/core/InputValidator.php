<?php
namespace App\Core;

class InputValidator 
{
    private $errors = [];

    // Validate and sanitize email
    public function email($email)
    {
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if (empty($sanitizedEmail)) {
            $this->errors['email'] = 'Email is required';
            return false;
        }

        if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Invalid email format';
            return false;
        }

        return $sanitizedEmail;
    }

    // Validate password strength
    public function password($password, $minLength = 8)
    {
        if (empty($password)) {
            $this->errors['password'] = 'Password is required';
            return false;
        }

        if (strlen($password) < $minLength) {
            $this->errors['password'] = "Password must be at least {$minLength} characters long";
            return false;
        }

        // Check for at least one uppercase, one lowercase, one number, and one special character
        $hasUppercase = preg_match('/[A-Z]/', $password);
        $hasLowercase = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/\d/', $password);
        $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);

        if (!($hasUppercase && $hasLowercase && $hasNumber && $hasSpecialChar)) {
            $this->errors['password'] = 'Password must include uppercase, lowercase, number, and special character';
            return false;
        }

        return $password;
    }

    // Validate and sanitize text input
    public function text($input, $maxLength = 255, $allowHTML = false)
    {
        if (empty($input)) {
            $this->errors['text'] = 'Input is required';
            return false;
        }

        if (strlen($input) > $maxLength) {
            $this->errors['text'] = "Input must be less than {$maxLength} characters";
            return false;
        }

        if (!$allowHTML) {
            $input = strip_tags($input);
        }

        $input = trim($input);
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    // Get validation errors
    public function getErrors()
    {
        return $this->errors;
    }

    // Check if there are any validation errors
    public function fails()
    {
        return !empty($this->errors);
    }

    // Clear previous validation errors
    public function reset()
    {
        $this->errors = [];
    }
}