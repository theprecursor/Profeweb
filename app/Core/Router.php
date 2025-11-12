<?php

namespace App\Core;

/**
 * Clase Router: Maneja la lógica de enrutamiento de la aplicación.
 * Mapea las URIs a los controladores y métodos correspondientes [7].
 * Estructura: ['uri' => ['METHOD' => 'Controller@action']]
 */
class Router {
    /** @var array Lista de rutas registradas */
    protected $routes = [];

    /**
     * Registra una nueva ruta, incluyendo el método HTTP.
     * @param string $method El método HTTP (GET, POST, etc.)
     * @param string $uri La URI a la que responde la ruta (ej: '/', '/login')
     * @param string $handler El handler en formato 'Controller@method'
     */
    public function add_route(string $method, string $route, string $handler): void {
        // Normalizamos la ruta quitando barras iniciales/finales [8].
        $clean_route = trim($route, '/');
        
        // Almacenamos el handler indexado por URI y Método HTTP
        $this->routes[$clean_route][strtoupper($method)] = $handler;
    }

    /**
     * Procesa la URL recibida y llama al controlador/método correspondiente.
     * Debe recibir el método HTTP de la solicitud y la instancia de Database para inyección.
     * @param string $url La ruta a despachar.
     * @param string $method El método HTTP de la solicitud actual.
     * @param \App\Core\Database $db_instance Instancia Singleton de la conexión DB.
     */
    public function dispatch(string $url, string $method, \App\Core\Database $db_instance): void {
   
        $search_url = trim($url, '/'); 
        $current_method = strtoupper($method);

        // 1. Comprobar si la URI existe Y si el método para esa URI está registrado
        if (isset($this->routes[$search_url]) && array_key_exists($current_method, $this->routes[$search_url])) {
            
            $handler = $this->routes[$search_url][$current_method];
            list($controllerName, $action) = explode('@', $handler);
            $controllerClass = "App\\Controllers\\" . $controllerName;
            
            if (class_exists($controllerClass)) {
                
                // 🚨 Inyección de Dependencias: Pasamos la instancia de Database al constructor.
                if ($controllerName === 'HomeController') {
                    // HomeController no necesita la DB por ahora, pero lo mantenemos simple.
                    $controller = new $controllerClass();
                } else {
                    // LoginController y RegisterController necesitan la DB para instanciar el Modelo Usuario [9-11].
                    $controller = new $controllerClass($db_instance); 
                }

                if (method_exists($controller, $action)) {
                    $controller->$action();
                } else {
                    $this->handle404();
                }
            } else {
                $this->handle404();
            }
        } else {
            $this->handle404();
        }
    }

    protected function handle404(): void {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "<p>La ruta solicitada no se ha podido dirigir.</p>";
    }
}
?>