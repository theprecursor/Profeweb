<?php
// app/Controllers/UnidadesController.php

namespace App\Controllers;

use App\Core\Controller;

class UnidadesController extends Controller
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
            FROM unidades_didacticas  
            WHERE usuario_id = ?
            ORDER BY orden DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $unidades = $stmt->fetchAll();

        $stmt = $this->db->prepare("
            SELECT id, nombre_asignatura, curso_id 
            FROM asignaturas 
            WHERE usuario_id = ?
            ORDER BY curso_id DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $asignaturas = $stmt->fetchAll();

        $stmt = $this->db->prepare("
            SELECT nombre_curso, id 
            FROM cursos 
            WHERE usuario_id = ?
            ORDER BY nombre_curso ASC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $cursos = $stmt->fetchAll();
        $this->dashboardView('unidades/index', [
            'cursos' => $cursos,
            'unidades' => $unidades,
            'asignaturas' => $asignaturas,
            'current_page' => 'unidades']);
    }

    // FORMULARIO CREAR
    public function create()
    {
        $this->requireAuth();
        $stmt = $this->db->prepare("
            SELECT id, nombre_asignatura, curso_id 
            FROM asignaturas 
            WHERE usuario_id = ?
            ORDER BY curso_id DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $asignaturas = $stmt->fetchAll();
        $stmt = $this->db->prepare("
            SELECT id, nombre_curso 
            FROM cursos 
            WHERE usuario_id = ?
            ORDER BY nombre_curso DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $cursos = $stmt->fetchAll();
        $this->dashboardView('unidades/create', [
            'cursos' => $cursos,
            'asignaturas' => $asignaturas,
            'current_page' => 'unidades']);
    }

    // GUARDAR NUEVA
    public function store()
    {
        $this->requireAuth();

        $nombre = trim($_POST['nombre_unidad'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;
        $curso_id = trim($_POST['curso'] ?? '');
        $asignatura_id = trim($_POST['asignatura'] ?? '');

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre de la asignatura es obligatorio.";
            $this->redirect('/unidades/crear');
        }

        $stmt = $this->db->prepare("
            INSERT INTO unidades_didacticas 
                (usuario_id, nombre_unidad, descripcion, es_publico, curso_id, asignatura_id) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $nombre, $descripcion, $es_publico, $curso_id, $asignatura_id]);

        $_SESSION['success'] = "Asignatura creada correctamente.";
        $this->redirect('/unidades');
    }

    // FORMULARIO EDITAR
    public function edit($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("
            SELECT * FROM unidades_didacticas 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $unidades = $stmt->fetch();

        if (!$unidades) {
            $_SESSION['error'] = "Unidad no encontrada.";
            $this->redirect('unidades');
        }

        $this->dashboardView('/unidades/edit', [
            'unidades' => $unidades,
            'current_page' => 'unidades'
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
            $this->redirect('unidades');
        }

        $nombre = trim($_POST['nombre_asignatura'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre es obligatorio.";
            $this->redirect("/unidades/editar/{$id}");
        }

        $stmt = $this->db->prepare("
            UPDATE asignaturas 
            SET nombre_asignatura = ?, 
                descripcion = ?, 
                es_publico = ? 
            WHERE id = ?
        ");
        $stmt->execute([$nombre, $descripcion, $es_publico, $id]);

        $_SESSION['success'] = "Unidad actualizada.";
        $this->redirect('/unidades');
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

        $_SESSION['success'] = "Unidad eliminada correctamente.";
        $this->redirect('/unidades');
    }
}