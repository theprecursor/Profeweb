<?php
// app/Core/Controller.php

namespace App\Core;

use App\Core\Database;

class Controller
{
    protected $db;

    public function __construct()
    {
        // Inyectamos la conexión a la base de datos
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Carga una vista dentro del layout principal
     */
    protected function view(string $view, array $data = [])
    {
        extract($data);

        // Convertimos home/index en ../views/home/index.php
        $viewPath = "../views/{$view}.php";

        if (!file_exists($viewPath)) {
            die("Vista no encontrada: {$viewPath}");
        }

        // Capturamos el contenido de la vista
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Cargamos el layout
        require "../views/layout/main.php";
    }

    protected function redirect(string $url)
    {
        header("Location: " . $url);
        exit;
    }
}