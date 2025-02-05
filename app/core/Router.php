<?php

namespace App\Core;

class Router
{
    protected array $routes = [];
    protected array $params = [];
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function get($path, $controller, $method)
    {
        $this->routes['GET'][$path] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function post($path, $controller, $method)
    {
        $this->routes['POST'][$path] = [
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function match($url, $requestMethod)
    {
        if (!isset($this->routes[$requestMethod])) {
            return false;
        }

        foreach ($this->routes[$requestMethod] as $route => $params) {
            // Convert route parameters (e.g., {id}) to regex pattern
            $pattern = "@^" . preg_replace('/\{([a-zA-Z]+)\}/', '(?<\1>[^/]+)', $route) . "$@D";

            if (preg_match($pattern, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function dispatch()
    {
        $url = $this->request->getPath();
        $requestMethod = $this->request->getMethod();

        // Remove base path from URL
        $basePath = '/project/public';
        if (strpos($url, $basePath) === 0) {
            $url = substr($url, strlen($basePath));
        }

        // Remove query string
        if ($pos = strpos($url, '?')) {
            $url = substr($url, 0, $pos);
        }
        
        // Remove trailing slash
        $url = rtrim($url, '/');
        
        // If empty URL, set to '/'
        if (empty($url)) {
            $url = '/';
        }

        if ($this->match($url, $requestMethod)) {
            $controller = $this->params['controller'];
            $method = $this->params['method'];

            if (class_exists($controller)) {
                $controller_object = new $controller();

                if (method_exists($controller_object, $method)) {
                    // Remove controller and method from params
                    unset($this->params['controller']);
                    unset($this->params['method']);

                    // Call the method with parameters
                    return call_user_func_array([$controller_object, $method], $this->params);
                }
            }
        }

        // 404 Handler
        header("HTTP/1.0 404 Not Found");
        echo $this->render404();
    }

    protected function render404()
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>404 Not Found</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-100 h-screen flex items-center justify-center">
            <div class="text-center">
                <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
                <p class="text-xl text-gray-600 mb-8">Page not found</p>
                <a href="/" class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600">
                    Go Home
                </a>
            </div>
        </body>
        </html>';
    }
}