<?php
// app/Controllers/ProfesorController.php

namespace App\Controllers;

use App\Core\Controller;

class ProfesorController extends Controller
{
    public function show($id)
    {
        $id = (int)$id;

        // Buscar profesor
        $stmt = $this->db->prepare("SELECT id, nombre FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $profesor = $stmt->fetch();

        if (!$profesor) {
            http_response_code(404);
            echo "<h1 class='text-center py-5'>Profesor no encontrado</h1>";
            exit;
        }

        // Solo asignaturas públicas
        $stmt = $this->db->prepare("
            SELECT nombre_asignatura, descripcion, created_at 
            FROM asignaturas 
            WHERE usuario_id = ? AND es_publico = 1 
            ORDER BY nombre_asignatura
        ");
        $stmt->execute([$id]);
        $asignaturas = $stmt->fetchAll();

        $this->view('profesor/show', [
            'profesor' => $profesor,
            'asignaturas' => $asignaturas
        ]);
    }
}