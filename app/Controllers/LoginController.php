<?php

namespace App\Controllers;

use App\Models\Usuario; // Necesita el Modelo de Usuario para buscar credenciales
use App\Core\Database;

class LoginController
{
    protected $usuarioModel;
    protected $errors = [];

    public function __construct(Database $db)
    {
        // 🚨 CRÍTICO: El Router::dispatch() debe asegurar que la instancia Database (Singleton)
        // se pase al constructor del controlador, resolviendo así la 'Fallo en la Instanciación de Dependencias'.
        $this->usuarioModel = new Usuario($db); 
    }

    /**
     * Muestra la vista de inicio de sesión.
     */
    public function showLogin(): void
    {
        // La vista (V) de Login (login.view.php) se carga aquí
        $view_path = __DIR__ . '\..\Views\auth\login.view.php';
        $errors = $this->errors;
        // La vista de login debe tener el formulario definido como en el Formulario de Registro Docente.
        require_once $view_path;
    }

    /**
     * Procesa la solicitud POST del formulario de login.
     */
    public function authenticate(): void
    {
        
        // 1. VERIFICACIÓN DEL MÉTODO (Solución al problema previo)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Si no es POST, redirige (previene accesos directos o fallos de enrutamiento).
            header("Location: " . ROOT_URL . "/public/login");
            exit;
        }
        
        // 2. RECOGIDA Y SANITIZACIÓN DE DATOS (Prevención de XSS) [4, 18]
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        // Contraseña en texto plano
        $password_plana = $_POST['password'] ?? '';
        
        // 3. VALIDACIÓN INICIAL DE PRESENCIA [19]
        if (empty($email) || empty($password_plana)) {
            $this->errors[] = "Todos los campos son obligatorios.";
            $this->showLogin(); 
            exit;
        } 
        // 4. BÚSQUEDA DEL USUARIO EN LA BBDD (Llamada al Modelo)
        // El Modelo retorna el registro, incluyendo el hash de la contraseña cifrada.
        $user = $this->usuarioModel->findByEmail($email); 

        // 5. VERIFICACIÓN DE CREDENCIALES (MODO MD5 INSEGURO)
        // La verificación compara el hash MD5 de la entrada del usuario con el hash MD5 almacenado
        // **ADVERTENCIA: ESTO ES INSEGURO Y DEBE SER SUSTITUIDO POR password_verify()**

        if (md5($password_plana) === $user['password']) {
    
            // 6. AUTENTICACIÓN EXITOSA: INICIO DE SESIÓN
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            
            // 7. REDIRECCIÓN EXITOSA
            // El controlador actúa como intermediario y orquestador del flujo [9-11]
            header("Location: " . ROOT_URL . "/public/dashboard");
            exit; 
            
        } else {
            echo md5($password_plana), ' es igual a ', $user['password'];
            // 8. FALLO DE AUTENTICACIÓN
            $this->errors[] = "Usuario y/o contraseña incorrecto."; 
            $this->showLogin();
            exit;
        }
    }
}