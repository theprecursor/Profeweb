<?php

// ==========================================================
// FORZAR MUESTRA DE ERRORES: QUITAR EN PRODUCCIÓN
// ==========================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Controlador Frontal de ProfeWeb.
 * Punto de entrada único para todas las peticiones del framework MVC.
 */

// ----------------------------------------------------------
// 1. INICIALIZACIÓN DEL ENTORNO Y CARGA DE CONFIGURACIÓN
// ----------------------------------------------------------

// 1.1. Definir el separador de directorio para portabilidad (DS)
define('DS', DIRECTORY_SEPARATOR);

// 1.2. Definir la raíz del proyecto (un nivel arriba de public)
// ESTA DEFINICIÓN DEBE ESTAR ANTES DE CUALQUIER REQUIRE QUE USE APP_ROOT
define('APP_ROOT', dirname(__DIR__));

// 1.3. Cargar el archivo de configuración (contiene ROOT_URL, DB_HOST, etc.)
require_once APP_ROOT . DS . 'config' . DS . 'config.php'; 

// 1.4. Iniciar la Sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ----------------------------------------------------------
// 2. CARGA DE CLASES DEL CORE Y COMPONENTES DE LA APLICACIÓN
// ----------------------------------------------------------

// Clases del Core
require_once APP_ROOT . DS . 'app' . DS . 'Core' . DS . 'Database.php';
require_once APP_ROOT . DS . 'app' . DS . 'Core' . DS . 'Router.php';

// Nuevas Clases de Aplicación
require_once APP_ROOT . DS . 'app' . DS . 'Models' . DS . 'Usuario.php';
require_once APP_ROOT . DS . 'app' . DS . 'Controllers' . DS . 'LoginController.php';

// 🚨 AÑADIR ESTA LÍNEA para que el Router pueda despachar la ruta raíz ('/')
require_once APP_ROOT . DS . 'app' . DS . 'Controllers' . DS . 'HomeController.php'; 

// ==========================================================
// 3. Lógica de Despacho y Enrutamiento
// ==========================================================

// Obtener la URI
$uri = $_SERVER['REQUEST_URI'];
$uri_no_query = strtok($uri, '?'); 

// 1. Obtener la ruta base y normalizarla (sin barra final)
$base_path = parse_url(ROOT_URL, PHP_URL_PATH);
$base_path_clean = rtrim($base_path, '/');

// 2. Limpiar la URI: primero eliminar el path base de forma insensible a mayúsculas
// Intentamos eliminar la base path completa (ej: /profeweb/)
$path = str_ireplace($base_path, '', $uri_no_query);

// 3. Si la eliminación falló (el path es el mismo), probamos eliminar la versión sin barra final.
if ($path === $uri_no_query) {
    $path = str_ireplace($base_path_clean, '', $uri_no_query);
}

// 4. Limpiar restos de 'public/index.php' y 'public' (también insensible)
$path = str_ireplace('index.php', '', $path);
$path = str_ireplace('public', '', $path); 

// 5. Normalizar: quitar barras iniciales/finales.
$path = trim($path, '/'); 

// 6. Normalizar a la raíz: si está vacío, debe ser '/'.
if (empty($path)) {
    $path = '/'; 
}

// Instanciar Router y Registrar Rutas
$router = new App\Core\Router();
$router->add_route('/', 'HomeController@index'); 

$router->add_route('/login', 'LoginController@showLogin'); 

// 1. Ruta para MOSTRAR el formulario (Método GET)
$router->add_route('GET', '/registro', 'LoginController@showRegister'); 

// 2. Ruta para PROCESAR la solicitud (Método POST, enviada por el formulario)
$router->add_route('POST', '/registro', 'LoginController@storeRegister'); 
$router->dispatch($path);
?>