<?php
// app/Core/Controller.php

namespace App\Core;

use App\Core\Database;

/**
 * Clase base para todos los controladores
 * Proporciona acceso a la base de datos y al sistema de vistas
 */
class Controller
{
    /** @var \PDO Conexión a la base de datos */
    protected $db;

    /**
     * Constructor: inyecta la conexión PDO desde el Singleton
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Carga una vista dentro del layout principal
     *
     * @param string $view     Ruta de la vista (ej: 'home/index', 'auth/login')
     * @param array  $data     Datos a pasar a la vista
     */
    protected function view(string $view, array $data = []): void
    {
        // Extraer variables para usarlas directamente en la vista
        extract($data);

        // Ruta completa a la vista (ej: ../views/auth/login.php)
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die("Error: Vista no encontrada → {$viewPath}");
        }

        // Capturar el contenido de la vista
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Cargar el layout principal (incluye header, footer, navbar, etc.)
        require __DIR__ . '/../../views/layout/main.php';
    }

    protected function dashboardView(string $view, array $data = []): void
    {
        extract($data);
        ob_start();
        require "../views/dashboard/{$view}.php";
        $content = ob_get_clean();
        require "../views/dashboard/layout.php";
    }

    /**
     * Redirección sencilla
     *
     * @param string $url URL relativa (ej: '/login', '/dashboard')
     */
    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Método útil para devolver JSON (API futura)
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}