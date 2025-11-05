<?php

namespace App\Controllers;

/**
 * Clase HomeController: Responsable de la lógica de la página principal y otras rutas públicas.
 */
class HomeController {

    /**
     * Método por defecto para la ruta raíz (/).
     */
    public function index(): void {
        // Esta es la simulación de la página principal.
        // La Vista (V) es la capa de presentación que usa HTML/CSS/JS [4-6].
        echo "<h1>Bienvenido a ProfeWeb (Página de inicio)</h1>";
        echo "<p>El motor MVC ha despachado correctamente el HomeController.</p>";
        
        // Enlace al Registro, como ya estaba:
        echo '<p>Dirígete a <a href="' . ROOT_URL . '/registro">/registro</a> para probar el formulario de registro seguro.</p>';
        
        // 🚨 ENLACE A LOGIN:
        echo '<p>¿Ya tienes cuenta? <a href="' . ROOT_URL . '/login">Inicia Sesión</a>.</p>'; 
    }
}
?>