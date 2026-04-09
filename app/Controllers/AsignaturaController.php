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

    public function index() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];
        
        // 1. Obtener todos los cursos para el desplegable del filtro
        $stmtCursos = $this->db->prepare("SELECT id, nombre_curso FROM cursos WHERE usuario_id = ? ORDER BY nombre_curso ASC");
        $stmtCursos->execute([$userId]);
        $cursos = $stmtCursos->fetchAll();

        // 2. Verificar si hay un filtro aplicado
        $filtroCursoId = $_GET['curso_id'] ?? '';
        
        // 3. Preparar la consulta de Asignaturas (Dinámica)
        $sql = "SELECT a.*, c.nombre_curso 
                FROM asignaturas a 
                LEFT JOIN cursos c ON a.curso_id = c.id 
                WHERE a.usuario_id = ?";
        
        $params = [$userId];

        if (!empty($filtroCursoId)) {
            $sql .= " AND a.curso_id = ?";
            $params[] = $filtroCursoId;
        }
        
        $sql .= " ORDER BY a.nombre_asignatura ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $asignaturas = $stmt->fetchAll();

        // 4. Enviar todo a la vista
        $this->dashboardView('asignaturas/index', [
            'asignaturas' => $asignaturas,
            'cursos' => $cursos,           // Necesario para el <select>
            'filtro_curso' => $filtroCursoId, // Para recordar qué se seleccionó
            'current_page' => 'asignaturas'
        ]);
    }
    // FORMULARIO CREAR
    public function create()
    {
        $this->requireAuth();

        $stmt = $this->db->prepare("
            SELECT id, nombre_curso, es_publico
            FROM cursos 
            WHERE usuario_id = ? 
            ORDER BY nombre_curso DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $cursos = $stmt->fetchAll();

        $this->dashboardView('asignaturas/create', ['current_page' => 'asignaturas', 'cursos' =>  $cursos]);
    }

    // GUARDAR NUEVA
    public function store()
    {
        $this->requireAuth();

        $nombre = trim($_POST['nombre_asignatura'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;
        $curso = trim($_POST['curso'] ?? '');

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre de la asignatura es obligatorio.";
            $this->redirect('/asignatura/crear');
        }

        $stmt = $this->db->prepare("
            INSERT INTO asignaturas 
                (usuario_id, nombre_asignatura, descripcion, es_publico, curso_id) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $nombre, $descripcion, $es_publico, $curso]);

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

        $stmt = $this->db->prepare("
            SELECT id, nombre_curso, es_publico
            FROM cursos 
            WHERE usuario_id = ? 
            ORDER BY nombre_curso DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $cursos = $stmt->fetchAll();

        if (!$asignatura) {
            $_SESSION['error'] = "Asignatura no encontrada.";
            $this->redirect('/asignaturas');
        }

        $this->dashboardView('asignaturas/edit', [
            'asignatura' => $asignatura,
            'current_page' => 'asignaturas', 
            'cursos' =>  $cursos
        ]);
    }

    // ACTUALIZAR
    public function update($id)
    {
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