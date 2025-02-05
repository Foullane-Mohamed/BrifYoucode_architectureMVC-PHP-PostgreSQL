<?php
namespace App\core;

use App\Middleware\AuthMiddleware;

class Router extends Controller
{
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    private function addRoute($route, $controller, $action, $method)
    {
        $this->routes[$method][$route] = [
            'controller' => $controller, 
            'action' => $action
        ];  
    }

    public function get($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "GET");
    }

    public function post($route, $controller, $action)
    {
        $this->addRoute($route, $controller, $action, "POST");
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $matchedRoute = null;
        foreach ($this->routes[$method] as $route => $routeInfo) {
            $pattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $route);
            $pattern = str_replace('/', '\/', $pattern);
            
            if (preg_match("/^$pattern$/", $uri, $matches)) {
                $matchedRoute = $routeInfo;
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                break;
            }
        }

        if (!$matchedRoute && isset($this->routes[$method][$uri])) {
            $matchedRoute = $this->routes[$method][$uri];
            $params = [];
        }

        if ($matchedRoute) {
            $controller = new $matchedRoute['controller']();
            $action = $matchedRoute['action'];

            if (empty($params)) {
                $controller->$action();
            } else {
                call_user_func_array([$controller, $action], array_values($params));
            }
        } else {
            $this->render('404');
        }
    }
}