<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Core\Database;

class LoginController {

    protected $usuarioModel;
    protected $db; // Inyectar la conexión a PDO

    // El constructor recibe la instancia de Database Singleton
    public function __construct(Database $db) {
        $this->db = $db;
        // Asumiendo que el Modelo Usuario acepta la instancia de Database [9, 11]
        // Se carga el Modelo aquí para futuras operaciones de autenticación.
        $this->usuarioModel = new Usuario($db); 
    }

    /**
     * Maneja la solicitud GET /login
     * Responsabilidad: Mostrar la vista del formulario de login.
     */
    public function showLogin(): void {
        // La Vista (V) es la capa de presentación [12, 13].
        // Usamos la variable $errors para compatibilidad con la vista [14].
        $errors = [];

        // Asumiendo que la vista está en Views/auth/login.view.php
        $view_path = APP_ROOT . DS . 'app' . DS . 'Views' . DS . 'auth' . DS . 'login.view.php';
        
        if (!file_exists($view_path)) {
            die("Error del sistema: Vista de login no encontrada. Buscada en: " . $view_path);
        }
        
        require_once $view_path;
    }

    // El método storeLogin() para procesar el POST de login se implementaría aquí más tarde.
}
?>