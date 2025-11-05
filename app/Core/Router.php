<?php

namespace App\Core;

/**
 * Clase Router: Maneja la lógica de enrutamiento de la aplicación.
 * Mapea las URIs a los controladores y métodos correspondientes [10, 23].
 */
class Router {
    /** @var array Lista de rutas registradas [24] */
    protected $routes = [];

    /**
     * Registra una nueva ruta simple (sin soporte para parámetros por ahora).
     * @param string $uri La URI a la que responde la ruta (ej: '/', '/login')
     * @param string $controller_action El handler en formato 'Controller@method'
     */
    public function add_route(string $route, string $handler): void {
        // 🚨 Normalizamos la ruta quitando barras iniciales/finales. 
        // Esto convierte '/' en una cadena vacía ('').
        $clean_route = trim($route, '/');
        
        $this->routes[$clean_route] = $handler;
    }

    /**
     * Procesa la URL recibida y llama al controlador/método correspondiente.
     * @param string $url La ruta a despachar.
     */
    public function dispatch(string $url): void {
    
        // 1. Limpiamos la URL entrante (la variable $path que recibes de index.php)
        // Esto convierte '/' a '' para que coincida con la clave registrada en add_route.
        $search_url = trim($url, '/'); 
        
        // 2. Comprobar si la ruta existe
        if (array_key_exists($search_url, $this->routes)) {
            
            $handler = $this->routes[$search_url];
            list($controllerName, $action) = explode('@', $handler);

            // La clase App\Controllers\HomeController debe estar visible (DEBUG OK)
            $controllerClass = "App\\Controllers\\" . $controllerName; 
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                $controller->$action();

                // 🚨 QUITAR ESTA LÍNEA DE DEBUG TEMPORAL DESPUÉS DE LA PRUEBA EXITOSA
                // echo "<h2>Ruta Despachada Exitosamente</h2>Controller: {$controllerName}<br>Method: {$action}<br>";
                
            } else {
                // Si el Router encuentra la ruta pero no puede instanciar la clase (improbable ahora)
                $this->handle404();
            }
        } else {
            // Ejecución del 404 si la clave limpia no se encontró
            $this->handle404();
        }
    }

    /**
     * Maneja el error 404 - Not Found.
     */
    protected function handle404(): void {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "<p>La ruta solicitada no se ha podido dirigir.</p>";
    }
}
?>