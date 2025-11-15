<?php
namespace App\Controllers;

use App\Core\Controller; // ← lo creamos en un segundo si no lo tienes

class HomeController extends Controller
{
    public function index()
    {
        // Obtener profesores con número de asignaturas
        $stmt = $this->db->query("
            SELECT u.id, u.nombre, u.email, COUNT(a.id) AS total_asignaturas
            FROM usuarios u
            LEFT JOIN asignaturas a ON a.usuario_id = u.id
            GROUP BY u.id
            ORDER BY u.nombre
        ");
        $profesores = $stmt->fetchAll();

        // Cargar la vista dentro del layout
        $this->view('home/index', [
            'profesores' => $profesores,
            'current_page' => 'home'
        ]);
    }
}