<?php
namespace App\Core;

class Router {
    protected $routes = [];

    // Make middleware optional
    public function add($method, $uri, $controller, $action = null) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch($uri) {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($uri, PHP_URL_PATH); // Remove query string

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            $pattern = "#^" . $route['uri'] . "$#";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                $controller = $route['controller'];
                $action = $route['action'];

                // Handle Closure Routes
                if (is_callable($controller)) {
                    call_user_func_array($controller, $matches);
                    return;
                }

                // Handle Controller@Action Routes
                // If action is null, assume controller has it, OR check older implementation style
                if ($action === null && is_string($controller) && strpos($controller, '@') === false) {
                     // Maybe it's just a closure we missed? Or incomplete definition.
                     // But based on usage, we usually pass Controller, Action.
                     // The error was "Too few arguments... exactly 4 expected".
                     // So previous definition was probably: add($method, $uri, $controller, $action)
                }

                // Instantiate Controller
                $controllerClass = "App\\Controllers\\" . $controller;
                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    if (method_exists($controllerInstance, $action)) {
                        call_user_func_array([$controllerInstance, $action], $matches);
                        return;
                    }
                }
            }
        }
        
        // 404
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found (Router)";
    }
}
