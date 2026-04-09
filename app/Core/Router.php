<?php
// app/Core/Router.php

namespace App\Core;

use App\Core\Database;

class Router
{
    protected $routes = []; // [method][pattern] => handler_string

    public function add_route(string $method, string $route, string $handler): void
    {
        $method = strtoupper($method);
        $route  = trim($route, '/');

        // Guardamos siempre el handler como string
        $this->routes[$method][$route] = $handler;
    }

    public function dispatch(string $uri, string $httpMethod, Database $db_instance): void
    {
        $uri        = trim(parse_url($uri, PHP_URL_PATH), '/');
        $httpMethod = strtoupper($httpMethod);

        // 1. Ruta exacta
        if (isset($this->routes[$httpMethod][$uri])) {
            $this->execute($this->routes[$httpMethod][$uri], []);
            return;
        }

        // 2. Rutas con parámetros {id}, {slug}, etc.
        foreach ($this->routes[$httpMethod] ?? [] as $pattern => $handler) {
            // Convertir {id} → regex
            $regex = preg_replace('#\{[^}]+\}#', '([^/]+)', $pattern);
            $regex = "#^$regex$#";

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches); // quitar el match completo
                $this->execute($handler, $matches);
                return;
            }
        }

        // 3. 404
        $this->handle404();
    }

    private function execute(string $handler, array $params = []): void
    {
        // Ahora $handler siempre es string → explode() funciona seguro
        [$controllerName, $method] = explode('@', $handler);

        $controllerClass = "App\\Controllers\\" . $controllerName;

        if (!class_exists($controllerClass)) {
            $this->handle404();
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            $this->handle404();
            return;
        }

        call_user_func_array([$controller, $method], $params);
    }

    protected function handle404(): void
    {
        http_response_code(404);
        echo "<div style='text-align:center;padding:50px;font-family:Arial'>
                <h1>404 - Página no encontrada</h1>
                <p>La ruta solicitada no existe.</p>
              </div>";
        exit;
    }
}