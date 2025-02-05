<?php
namespace App\Core;

class SessionManager 
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', 1); // Only for HTTPS

            session_start();
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function destroy()
    {
        session_unset();
        session_destroy();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }

    public function regenerate()
    {
        session_regenerate_id(true);
    }

    public function login($userId)
    {
        $this->regenerate();
        $this->set('user_id', $userId);
        $this->set('logged_in', true);
    }

    public function isLoggedIn()
    {
        return $this->get('logged_in', false);
    }

    public function getUserId()
    {
        return $this->get('user_id');
    }
}