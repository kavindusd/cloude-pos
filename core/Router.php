<?php

class Router
{
    protected array $routes = [];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url    = '/' . trim($url, '/');

        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route => $action) {
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);
                [$controllerName, $methodName] = explode('@', $action);

                if (!class_exists($controllerName)) {
                    $this->abort(500, "Controller [{$controllerName}] not found.");
                    return;
                }

                $controller = new $controllerName();

                if (!method_exists($controller, $methodName)) {
                    $this->abort(500, "Method [{$methodName}] not found on [{$controllerName}].");
                    return;
                }

                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        $this->abort(404, "Route [{$url}] not found.");
    }

    protected function abort(int $code, string $message): void
    {
        http_response_code($code);
        echo "<h1>Error {$code}</h1><p>{$message}</p>";
    }
}
