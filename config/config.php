<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
/**
 * Archivo de Configuración Global de ProfeWeb
 * Define constantes para la conexión a la BBDD y la URL raíz.
 */

// ==========================================================
// CONFIGURACIÓN DE BASE DE DATOS (MySQL/MariaDB)
// Se recomienda utf8mb4 para el soporte completo de caracteres
// ==========================================================

// DB_HOST: Host de la base de datos. Usualmente 'localhost' o '127.0.0.1' en desarrollo.
define('DB_HOST', 'localhost');

// DB_NAME: Nombre de la base de datos.
define('DB_NAME', 'profeweb'); // Nombre según el esquema

// DB_USER: Usuario de la base de datos.
define('DB_USER', 'root');

// DB_PASS: Contraseña del usuario. ¡Usar credenciales seguras en producción!
define('DB_PASS', '');

// ==========================================================
// CONFIGURACIÓN DE RUTAS
// ==========================================================

// ROOT_URL: URL base de la aplicación (apuntando al DocumentRoot: /public). 
// ¡Ajustar en el entorno de producción!
define('ROOT_URL', 'http://localhost/profeweb');

// Constante para el charset recomendado en la conexión PDO
define('DB_CHARSET', 'utf8mb4');
?>