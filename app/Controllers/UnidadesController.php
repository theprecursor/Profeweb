<?php
// app/Controllers/UnidadesController.php

namespace App\Controllers;

use App\Core\Controller;
use PDOException;

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
            ORDER BY orden ASC
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

    public function indexfilter()
    {
        $this->requireAuth();

        $curso_id = trim($_POST['curso'] ?? '');
        $asignatura_id = trim($_POST['asignatura'] ?? '');
        $unidades= '';
        if(empty($curso_id) && empty($asignatura_id)){
            $stmt = $this->db->prepare("
                SELECT *
                FROM unidades_didacticas  
                WHERE usuario_id = ?
                ORDER BY orden DESC
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $unidades = $stmt->fetchAll();
        } elseif(empty($asignatura_id)){
            $stmt = $this->db->prepare("
                SELECT *
                FROM unidades_didacticas  
                WHERE usuario_id = ? AND curso_id = ?
                ORDER BY orden DESC
            ");
            $stmt->execute([$_SESSION['user_id'], $curso_id]);
            $unidades = $stmt->fetchAll();
        } elseif(empty($curso_id)){
             $_SESSION['error'] = 'Debes de seleccionar un curso';
        } else{
            $stmt = $this->db->prepare("
                SELECT *
                FROM unidades_didacticas  
                WHERE usuario_id = ? AND curso_id = ? AND asignatura_id = ?
                ORDER BY orden DESC
            ");
            $stmt->execute([$_SESSION['user_id'], $curso_id, $asignatura_id]);
            $unidades = $stmt->fetchAll();
        }
        

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
        $orden = trim($_POST['orden'] ?? '');

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre de la asignatura es obligatorio.";
            $this->redirect('/unidades/crear');
        }

        
        try{
            $stmt = $this->db->prepare("
            INSERT INTO unidades_didacticas 
                (usuario_id, nombre_unidad, descripcion, es_publico, curso_id, asignatura_id, orden) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$_SESSION['user_id'], $nombre, $descripcion, $es_publico, $curso_id, $asignatura_id, $orden]);
            
            $_SESSION['success'] = "Unidad creada correctamente.";
        } catch(PDOException $e){
            if(empty($curso_id) && empty($asignatura_id)){
                $_SESSION['error'] = "La asignatura y el curso son obligatorios.";
                $this->redirect("/unidades/crear");
            }elseif(empty($curso_id)){
                $_SESSION['error'] = "El curso es obligatorio.";
                $this->redirect("/unidades/crear");
            } elseif(empty($asignatura_id)) {
                $_SESSION['error'] = "La asignatura es obligatoria.";
                $this->redirect("/unidades/crear");
            } else{
                $_SESSION['error'] = 'Error desconocido';
                $this->redirect("/unidades/crear");
            }
        }
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

        if (!$unidades) {
            $_SESSION['error'] = "Unidad no encontrada.";
            $this->redirect('unidades');
        }

        $this->dashboardView('unidades/edit', [
            'unidades' => $unidades,
            'cursos' => $cursos,
            'asignaturas' => $asignaturas,
            'current_page' => 'unidades'
        ]);
    }

    // ACTUALIZAR
    public function update($id)
    {
        printf('llegando aquí');
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("SELECT id FROM unidades_didacticas WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        if (!$stmt->fetch()) {
            $_SESSION['error'] = "No tienes permiso para editar esta asignatura.";
            $this->redirect('unidades');
        }

        $nombre = trim($_POST['nombre_unidad'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;
        $curso_id = trim($_POST['curso_id'] ?? null); 
        $asignatura_id = trim($_POST['asignatura_id'] ?? null); 
        $orden = trim($_POST['orden'] ?? '');

        try{
            $stmt = $this->db->prepare("
            UPDATE unidades_didacticas 
            SET nombre_unidad = ?, 
                descripcion = ?, 
                es_publico = ?, 
                curso_id = ?,
                orden = ?,
                asignatura_id = ?
            WHERE id = ?
            ");
            $stmt->execute([$nombre, $descripcion, $es_publico, $curso_id, $orden, $asignatura_id, $id]);
        } catch(PDOException $e){
            if(empty($curso_id)){
                $_SESSION['error'] = "El curso es obligatorio.";
                $this->redirect("/unidades/editar/{$id}");
            } elseif(empty($asignatura_id)) {
                $_SESSION['error'] = "La asignatura es obligatoria.";
                $this->redirect("/unidades/editar/{$id}");
            } elseif(empty($curso_id) && empty($asignatura_id)){
                $_SESSION['error'] = "La asignatura y el curso son obligatorios.";
                $this->redirect("/unidades/editar/{$id}");
            } else{
                $_SESSION['error'] = 'Error desconocido';
                $this->redirect("/unidades/editar/{$id}");
            }
        }
        

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