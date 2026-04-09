<?php
// app/Controllers/AuthController.php

namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{
    // === MOSTRAR FORMULARIOS ===
    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login', ['current_page' => 'login']);
    }

    public function showRegister()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/register', ['current_page' => 'register']);
    }

    // === PROCESAR REGISTRO ===
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $pass   = $_POST['password'] ?? '';
        $pass2  = $_POST['password_confirm'] ?? '';

        // Validaciones
        if (empty($nombre) || empty($email) || empty($pass)) {
            $_SESSION['error'] = "Todos los campos son obligatorios";
            $this->redirect('/register');
        }

        if ($pass !== $pass2) {
            $_SESSION['error'] = "Las contraseñas no coinciden";
            $this->redirect('/register');
        }

        if (strlen($pass) < 6) {
            $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres";
            $this->redirect('/register');
        }

        // Comprobar si el email ya existe
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = "Este email ya está registrado";
            $this->redirect('/register');
        }

        // Insertar usuario
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $email, $hash]);

        $_SESSION['success'] = "¡Registro completado! Ya puedes iniciar sesión.";
        $this->redirect('/login');
    }

    // === PROCESAR LOGIN ===
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';

        if (empty($email) || empty($pass)) {
            $_SESSION['error'] = "Email y contraseña obligatorios";
            $this->redirect('/login');
        }

        $stmt = $this->db->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $this->redirect('/dashboard');
        } else {
            $_SESSION['error'] = "Email o contraseña incorrectos";
            $this->redirect('/login');
        }
    }

    // === CERRAR SESIÓN ===
    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('/');
    }
}