<?php

namespace App\Controllers;

// Asumimos que el modelo se incluye correctamente en el index.php o mediante un autoloading (futuro)
use App\Models\Usuario; 

// Nota: Asumimos que APP_ROOT y DS están definidos en public/index.php

class LoginController {

    protected $usuarioModel;
    protected $errors = [];

    public function __construct() {
        // Instanciar el modelo de datos para acceso a la BBDD [26]
        $this->usuarioModel = new Usuario();
    }

    /**
     * Muestra la vista del formulario de login. (Necesario para que el Router no falle)
     */
    public function showLogin(): void {
        // Por ahora, solo simula que la vista de login se carga correctamente.
        echo "<h1>Vista de Login Pendiente</h1>"; 
    }

    /**
     * Muestra la vista del formulario de registro.
     */
    public function showRegister(): void {
        // Lógica para cargar la vista [25, 27, 28]
        $view_path = APP_ROOT . DS . 'app' . DS . 'Views' . DS . 'auth' . DS . 'login.view.php';
        
        if (!file_exists($view_path)) {
            // Manejo de error si la ruta de la vista no es correcta
            die("Error del sistema: Vista de registro no encontrada.");
        }
        
        // Si hay errores, se pasa a la vista (para mostrar mensajes en un entorno real)
        $errors = $this->errors; 
        
        require_once $view_path;
    }

    /**
     * Procesa los datos del formulario POST para registrar un nuevo usuario.
     */
    public function storeRegister(): void {
        
        // Aseguramos que solo procesamos peticiones POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . ROOT_URL . "/registro");
            exit;
        }

        // 1. Limpieza y sanitización de inputs (Prevención de XSS) [7, 29]
        // htmlspecialchars es esencial para filtrar la salida de datos [7, 8, 29]
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        // Sanitizar el nombre y email antes de la validación
        $nombre = htmlspecialchars(trim($nombre), ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars(trim($email), ENT_QUOTES, 'UTF-8');
        
        // 2. Validación de la entrada (Controlador: formato y coherencia) [30]
        
        if (empty($nombre) || empty($email) || empty($password) || empty($password_confirm)) {
            $this->errors[] = "Todos los campos son obligatorios.";
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "El formato del correo electrónico no es válido.";
        }
        
        if ($password !== $password_confirm) {
            $this->errors[] = "Las contraseñas no coinciden.";
        }
        
        if (strlen($password) < 8) {
            $this->errors[] = "La contraseña debe tener al menos 8 caracteres.";
        }
        
        // 3. Validación de regla de negocio: Email único (persistencia) [16]
        if (empty($this->errors) && $this->usuarioModel->findByEmail($email)) {
            $this->errors[] = "El correo electrónico ya está registrado.";
        }

        // Si hay errores, volvemos a mostrar el formulario con los mensajes
        if (!empty($this->errors)) {
            $this->showRegister();
            exit;
        }

        // 4. Si todo es válido, crear el usuario
        if ($this->usuarioModel->create($nombre, $email, $password)) {
            // 5. Redirigir a /login (Requisito) [26]
            header("Location: " . ROOT_URL . "/login?registro=exitoso");
            exit;
        } else {
            // Error en la inserción de BBDD
            $this->errors[] = "Error al intentar guardar el usuario. Inténtelo más tarde.";
            $this->showRegister();
            exit;
        }
    }
}
?>