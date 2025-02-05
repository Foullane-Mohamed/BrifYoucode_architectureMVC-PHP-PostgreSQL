<?php
namespace App\Core;

use Dotenv\Dotenv;

class Security 
{
    private static $SECRET_KEY;
    private static $PEPPER;

    public function __construct() 
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        
        self::$SECRET_KEY = $_ENV['APP_SECRET_KEY'];
        self::$PEPPER = $_ENV['APP_PEPPER'];
    }

    public static function hashPassword($password)
    {
        $salt = bin2hex(random_bytes(16));
        
        $hashedPassword = hash_pbkdf2(
            'sha256', 
            $password . self::$PEPPER, 
            $salt, 
            10000, 
            0, 
            true
        );
        
        return [
            'salt' => $salt,
            'password' => base64_encode($hashedPassword)
        ];
    }

    public static function verifyPassword($inputPassword, $storedHashedPassword, $salt)
    {
        $hashedInput = hash_pbkdf2(
            'sha256', 
            $inputPassword . self::$PEPPER, 
            $salt, 
            10000, 
            0, 
            true
        );

        return hash_equals(
            base64_encode($hashedInput), 
            $storedHashedPassword
        );
    }

    public static function generateCSRFToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRFToken($token)
    {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
}