<?php
// app/Controllers/AsignaturaController.php

namespace App\Controllers;

use App\Core\Controller;

class CriterioController extends Controller
{
    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    // LISTADO
    public function index()
    {
        $this->requireAuth();

        $stmt = $this->db->prepare("
            SELECT *
            FROM criterios_evaluacion 
            WHERE usuario_id = ? 
            ORDER BY id ASC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $criterios = $stmt->fetchAll();

        $this->dashboardView('criterios/index', [
            'criterios' => $criterios,
            'current_page' => 'criterios'
        ]);
    }

    // FORMULARIO CREAR
    public function create()
    {
        $this->requireAuth();

        $this->dashboardView('criterios/create', ['current_page' => 'criterios']);
    }

    // GUARDAR NUEVA
    public function store()
    {
        $this->requireAuth();

        $nombre = trim($_POST['codigo_criterio'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre de la asignatura es obligatorio.";
            $this->redirect('/criterios/crear');
        }

        $stmt = $this->db->prepare("
            INSERT INTO criterios_evaluacion 
                (usuario_id, codigo_criterio, es_publico) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $nombre, $es_publico]);

        $_SESSION['success'] = "Asignatura creada correctamente.";
        $this->redirect('/criterios');
    }

    // FORMULARIO EDITAR
    public function edit($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("
            SELECT * FROM criterios_evaluacion 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $criterios = $stmt->fetch();

        if (!$criterios) {
            $_SESSION['error'] = "Asignatura no encontrada.";
            $this->redirect('/criterios');
        }

        $this->dashboardView('criterios/edit', [
            'criterios' => $criterios,
            'current_page' => 'criterios'
        ]);
    }

    // ACTUALIZAR
    public function update($id)
    {
        $this->requireAuth();

        $nombre = trim($_POST['codigo_criterio'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre es obligatorio.";
            $this->redirect("/criterios/editar/{$id}");
        }

        $stmt = $this->db->prepare("
            UPDATE criterios_evaluacion 
            SET codigo_criterio = ?, 
                es_publico = ? 
            WHERE id = ?
        ");
        $stmt->execute([$nombre, $es_publico, $id]);

        $_SESSION['success'] = "Criterio actualizado.";
        $this->redirect('/criterios');
    }

    // ELIMINAR
    public function delete($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("
            DELETE FROM criterios_evaluacion 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);

        $_SESSION['success'] = "Criterio eliminado correctamente.";
        $this->redirect('/criterios');
    }
}