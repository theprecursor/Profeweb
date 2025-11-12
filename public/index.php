<?php
// ==========================================================
// FORZAR MUESTRA DE ERRORES: QUITAR EN PRODUCCIÓN
// ==========================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 
/**
 * Controlador Frontal de ProfeWeb.
 * Punto de entrada único para todas las peticiones del framework MVC [33].
 */

// ----------------------------------------------------------
// 1. INICIALIZACIÓN DEL ENTORNO Y CARGA DE CONFIGURACIÓN
// ----------------------------------------------------------

// 1.1. Definir el separador de directorio para portabilidad (DS) [34]
define('DS', DIRECTORY_SEPARATOR); 
// 1.2. Definir la raíz del proyecto (un nivel arriba de public) [34]
define('APP_ROOT', dirname(__DIR__)); 

// 1.3. Cargar el archivo de configuración (contiene ROOT_URL, DB_HOST, etc.) [34]
require_once APP_ROOT . DS . 'config' . DS . 'config.php'; 

// 1.4. Iniciar la Sesión [35]
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ----------------------------------------------------------
// 2. CARGA DE CLASES DEL CORE Y COMPONENTES DE LA APLICACIÓN
// ----------------------------------------------------------

// Clases del Core (Database debe usar el patrón Singleton [31, 32])
require_once APP_ROOT . DS . 'app' . DS . 'Core' . DS . 'Database.php';
require_once APP_ROOT . DS . 'app' . DS . 'Core' . DS . 'Router.php';

// Modelos y Controladores
require_once APP_ROOT . DS . 'app' . DS . 'Models' . DS . 'Usuario.php';
require_once APP_ROOT . DS . 'app' . DS . 'Controllers' . DS . 'HomeController.php';
require_once APP_ROOT . DS . 'app' . DS . 'Controllers' . DS . 'LoginController.php'; 
// 🚨 Nuevo Controlador de Registro
require_once APP_ROOT . DS . 'app' . DS . 'Controllers' . DS . 'RegisterController.php';

// ----------------------------------------------------------
// 3. Lógica de Despacho y Enrutamiento
// ----------------------------------------------------------

// 🚨 Obtener la instancia de la base de datos (PDO) usando el patrón Singleton
$database = \App\Core\Database::getInstance();
// Necesitamos pasar la instancia de Database, no el PDO, para que los controladores puedan llamar a getConnection()
// y trabajar con la abstracción.

// Obtener la URI y normalizar la ruta [38, 39]
$uri = $_SERVER['REQUEST_URI'];
$uri_no_query = strtok($uri, '?'); 
$base_path = parse_url(ROOT_URL, PHP_URL_PATH);
$base_path_clean = rtrim($base_path, '/');
$path = str_ireplace($base_path, '', $uri_no_query);

if ($path === $uri_no_query) {
    $path = str_ireplace($base_path_clean, '', $uri_no_query);
}

$path = str_ireplace('index.php', '', $path);
$path = str_ireplace('public', '', $path); 
$path = trim($path, '/'); 

// Normalizar a la raíz: si está vacío, debe ser '/' [39].
if (empty($path)) {
    $path = '/'; 
}

// Instanciar Router y Registrar Rutas
$router = new App\Core\Router();

// Rutas GET (Mostrar Vistas)
$router->add_route('GET', '', 'HomeController@index'); 
$router->add_route('GET', '/login', 'LoginController@showLogin'); 
$router->add_route('GET', '/registro', 'RegisterController@showRegister'); // Mostrar formulario de registro

// Rutas POST (Procesar Formularios/Lógica de Negocio)
$router->add_route('POST', '/registro', 'RegisterController@storeRegister'); // Procesar envío de registro

// 🚨 Despachar pasando la RUTA, el MÉTODO HTTP y la INSTANCIA DATABASE
$router->dispatch($path, $_SERVER['REQUEST_METHOD'], $database); 
?>