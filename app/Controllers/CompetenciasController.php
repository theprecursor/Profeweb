<?php
// app/Controllers/AsignaturaController.php

namespace App\Controllers;

use App\Core\Controller;

class CompetenciasController extends Controller
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
            FROM competencias 
            WHERE usuario_id = ? 
            ORDER BY id ASC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $competencias = $stmt->fetchAll();

        $this->dashboardView('competencias/index', [
            'competencias' => $competencias,
            'current_page' => 'competencias'
        ]);
    }

    // FORMULARIO CREAR
    public function create()
    {
        $this->requireAuth();

        $this->dashboardView('competencias/create', ['current_page' => 'competencias']);
    }

    // GUARDAR NUEVA
    public function store()
    {
        $this->requireAuth();

        $nombre = trim($_POST['nombre_competencia'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre de la asignatura es obligatorio.";
            $this->redirect('/competencias/crear');
        }

        $stmt = $this->db->prepare("
            INSERT INTO competencias 
                (usuario_id, codigo_competencia, es_publico) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $nombre, $es_publico]);

        $_SESSION['success'] = "Asignatura creada correctamente.";
        $this->redirect('/competencias');
    }

    // FORMULARIO EDITAR
    public function edit($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("
            SELECT * FROM competencias 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $competencias = $stmt->fetch();

        if (!$competencias) {
            $_SESSION['error'] = "Asignatura no encontrada.";
            $this->redirect('/competencias');
        }

        $this->dashboardView('competencias/edit', [
            'competencias' => $competencias,
            'current_page' => 'competencias'
        ]);
    }

    // ACTUALIZAR
    public function update($id)
    {
        $this->requireAuth();

        $nombre = trim($_POST['nombre_competencia'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre es obligatorio.";
            $this->redirect("/competencias/editar/{$id}");
        }

        $stmt = $this->db->prepare("
            UPDATE competencias 
            SET codigo_competencia = ?, 
                es_publico = ? 
            WHERE id = ?
        ");
        $stmt->execute([$nombre, $es_publico, $id]);

        $_SESSION['success'] = "competencia actualizada.";
        $this->redirect('/competencias');
    }

    // ELIMINAR
    public function delete($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("
            DELETE FROM competencias 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);

        $_SESSION['success'] = "competencia eliminada correctamente.";
        $this->redirect('/competencias');
    }
}