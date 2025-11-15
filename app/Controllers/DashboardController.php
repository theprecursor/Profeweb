<?php
// app/Controllers/DashboardController.php

namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Protección: si no está logueado → al login
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        // Datos básicos del profesor
        $stmt = $this->db->prepare("
            SELECT nombre, email FROM usuarios WHERE id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $profesor = $stmt->fetch();

        // Contadores rápidos
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_asignaturas
            FROM asignaturas 
            WHERE usuario_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $stats = $stmt->fetch();

        $this->view('dashboard/index', [
            'profesor' => $profesor,
            'stats'    => $stats,
            'current_page' => 'dashboard'
        ]);
    }
}