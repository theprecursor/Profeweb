<?php
// public/index.php
session_start();

// Cargar configuración
require_once '../app/Config/config.php';

// Autoload PSR-4 simple
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $file = $base_dir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

use App\Core\Database;
use App\Core\Router;

// Instancia única de base de datos
$db = Database::getInstance()->getConnection();

// Crear el router
$router = new Router();

// ==================== DEFINIR TUS RUTAS AQUÍ ====================

// Ruta principal → HomeController@index
$router->add_route('GET', '/', 'HomeController@index');

// Rutas de autenticación
$router->add_route('GET', '/login', 'AuthController@showLogin');
$router->add_route('POST', '/login', 'AuthController@login');
$router->add_route('GET', '/register', 'AuthController@showRegister');
$router->add_route('POST', '/register', 'AuthController@register');
$router->add_route('GET', '/logout', 'AuthController@logout');

// Ejemplo futuro
// $router->add_route('GET', '/dashboard', 'DashboardController@index');

// ==============================================================

// Obtener URL y método actual
$request_url = $_SERVER['REQUEST_URI'] ?? '/';
$request_method = $_SERVER['REQUEST_METHOD'];

// Quitar parámetros de query string (?foo=bar)
if (($pos = strpos($request_url, '?')) !== false) {
    $request_url = substr($request_url, 0, $pos);
}

// Despachar la ruta
$router->dispatch($request_url, $request_method, Database::getInstance());