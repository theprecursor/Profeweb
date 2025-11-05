<?php

namespace App\Controllers;

use App\Models\Usuario;
// Asumimos que la clase BaseController o similar ya existe para inyectar dependencias o el router
// Si no, podríamos inyectar la instancia de la base de datos (Database.php) o el Modelo directamente.

class RegisterController 
{
    protected $usuarioModel;
    protected $db; // Inyectar la conexión a PDO

    // El constructor recibe la conexión a la base de datos o el modelo ya instanciado
    public function __construct($db) 
    {
        $this->db = $db;
        $this->usuarioModel = new \App\Models\Usuario($db); // Crear la instancia del Modelo de Usuario
    }

    /**
     * Maneja la solicitud GET /registro
     * Responsabilidad: Mostrar la vista del formulario de registro.
     */
    public function showRegister()
    {
        // La Vista es responsable de presentar el diseño y los datos [10, 11].
        // Devolver la vista (ejemplo: 'register.php' o 'register.tpl')
        require_once APP_ROOT . DS . 'app' . DS . 'Views' . DS . 'registro.view.php';
        
        // Es fundamental que este formulario envíe los datos a la ruta POST /registro
    }

    /**
     * Maneja la solicitud POST /registro
     * Responsabilidad: Procesar la entrada del usuario, validar y guardar en el Modelo.
     */
    public function storeRegister()
    {
        // 1. Recoger la entrada del usuario (Request)
        // El Controlador recibe la entrada del usuario [2, 5].
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Manejar error si no es POST
            header('Location: /registro');
            exit;
        }

        // Asumimos que el input viene vía POST. Se debe validar toda entrada de usuario [12, 13].
        $email = $_POST['email'] ?? '';
        $password_plana = $_POST['password'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        
        // 2. Validación y Lógica de Seguridad (Controller / Modelo)
        
        // **VALIDACIÓN INICIAL DE FORMATO Y PRESENCIA (Controlador)**
        // Las validaciones de input de usuario (formato, presencia) se centran en el Controlador [14].
        if (empty($email) || empty($password_plana) || empty($nombre)) {
            // Manejar error de campos obligatorios faltantes
            // Volver a la vista con mensaje de error
            echo "Error: Todos los campos son obligatorios.";
            return $this->showRegister();
        }
        
        // **SEGURIDAD CRÍTICA: CIFRADO DE CONTRASEÑA**
        // Requisito fundamental: la contraseña debe ser cifrada usando password_hash() [15, 16].
        $password_hash = password_hash($password_plana, PASSWORD_DEFAULT);
        
        // 3. Preparar los datos para el Modelo
        $datos_usuario = [
            'email' => $email,
            'password' => $password_hash,
            'nombre' => $nombre,
            // Otros campos requeridos por la tabla `usuarios` [16]:
            'apellidos' => $_POST['apellidos'] ?? null,
            'slug_perfil' => $this->generateSlug($nombre), // Función a implementar en el futuro
            'biografia' => ''
        ];
        
        // 4. Invocar al Modelo para la persistencia de datos
        // El Modelo (Usuario.php) es el encargado de la lógica de negocio, reglas y acceso a la BBDD [3, 7].
        try {
            $resultado = $this->usuarioModel->crearUsuario($datos_usuario);
            
            if ($resultado === true) {
                // Registro exitoso, redirigir al login o a la página de bienvenida
                header('Location: /login');
                exit;
            } else {
                // Manejar errores de negocio (ej. email ya existe, fallo de unicidad) [17]
                echo "Error al registrar el usuario: " . $resultado;
                return $this->showRegister();
            }
        } catch (\PDOException $e) {
            // Manejar fallos de la base de datos (incluyendo errores HY000/1045 si la autenticación falla [18])
            // En producción, solo mostrar un error genérico para no dar pistas al atacante [19].
            error_log("Fallo de registro: " . $e->getMessage());
            echo "Error interno del servidor. Por favor, inténtelo de nuevo más tarde.";
        }
    }
    
    // Función auxiliar simple para generar un slug (solo para fines de ejemplo)
    private function generateSlug($text) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
        return $slug;
    }
}