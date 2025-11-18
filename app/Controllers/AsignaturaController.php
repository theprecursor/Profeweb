<?php
// app/Controllers/AsignaturaController.php

namespace App\Controllers;

use App\Core\Controller;

class AsignaturaController extends Controller
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
            SELECT id, nombre_asignatura, descripcion, es_publico, created_at 
            FROM asignaturas 
            WHERE usuario_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $asignaturas = $stmt->fetchAll();

        $this->dashboardView('asignaturas/index', [
            'asignaturas' => $asignaturas,
            'current_page' => 'asignaturas'
        ]);
    }

    // FORMULARIO CREAR
    public function create()
    {
        $this->requireAuth();
        $this->dashboardView('asignaturas/create', ['current_page' => 'asignaturas']);
    }

    // GUARDAR NUEVA
    public function store()
    {
        $this->requireAuth();

        $nombre = trim($_POST['nombre_asignatura'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre de la asignatura es obligatorio.";
            $this->redirect('/asignatura/crear');
        }

        $stmt = $this->db->prepare("
            INSERT INTO asignaturas 
                (usuario_id, nombre_asignatura, descripcion, es_publico) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $nombre, $descripcion, $es_publico]);

        $_SESSION['success'] = "Asignatura creada correctamente.";
        $this->redirect('/asignaturas');
    }

    // FORMULARIO EDITAR
    public function edit($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("
            SELECT * FROM asignaturas 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $asignatura = $stmt->fetch();

        if (!$asignatura) {
            $_SESSION['error'] = "Asignatura no encontrada.";
            $this->redirect('/asignaturas');
        }

        $this->dashboardView('asignaturas/edit', [
            'asignatura' => $asignatura,
            'current_page' => 'asignaturas'
        ]);
    }

    // ACTUALIZAR
    public function update($id)
    {
        printf('llegando aquí');
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("SELECT id FROM asignaturas WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            $_SESSION['error'] = "No tienes permiso para editar esta asignatura.";
            $this->redirect('/asignaturas');
        }

        $nombre = trim($_POST['nombre_asignatura'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre es obligatorio.";
            $this->redirect("/asignatura/editar/{$id}");
        }

        $stmt = $this->db->prepare("
            UPDATE asignaturas 
            SET nombre_asignatura = ?, 
                descripcion = ?, 
                es_publico = ? 
            WHERE id = ?
        ");
        $stmt->execute([$nombre, $descripcion, $es_publico, $id]);

        $_SESSION['success'] = "Asignatura actualizada.";
        $this->redirect('/asignaturas');
    }

    // ELIMINAR
    public function delete($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("
            DELETE FROM asignaturas 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);

        $_SESSION['success'] = "Asignatura eliminada correctamente.";
        $this->redirect('/asignaturas');
    }
}