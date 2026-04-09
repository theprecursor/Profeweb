<?php
// app/Controllers/ActividadesController.php

namespace App\Controllers;

use App\Core\Controller;
use PDOException;

class ActividadesController extends Controller
{
    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    // LISTADO
    public function index() {
        // Verificamos autenticación
        $this->requireAuth();
        $userId = $_SESSION['user_id'];

        // 1. CAPTURAR FILTROS (USANDO GET)
        // Usamos $_GET porque estamos "pidiendo" datos, no guardando.
        // Si no llegan, asignamos '' (cadena vacía) o null.
        $curso_id = $_GET['curso_id'] ?? null;
        $asignatura_id = $_GET['asignatura_id'] ?? null;
        $unidad_id = $_GET['unidad_id'] ?? null;

        // 2. CONSTRUCCIÓN DE LA CONSULTA SQL CON JOINs
        // Traemos los nombres de las tablas relacionadas para mostrarlos en la vista
        $sql = "
            SELECT 
                act.*, 
                u.nombre_unidad, 
                asig.nombre_asignatura, 
                asig.curso_id,
                c.nombre_curso
            FROM actividades act
            INNER JOIN unidades_didacticas u ON act.unidad_id = u.id
            INNER JOIN asignaturas asig ON u.asignatura_id = asig.id
            INNER JOIN cursos c ON asig.curso_id = c.id
            WHERE asig.usuario_id = ?
        ";

        $params = [$userId];

        // 3. APLICAR FILTROS SI EXISTEN
        if (!empty($curso_id)) {
            $sql .= " AND asig.curso_id = ?";
            $params[] = $curso_id;
        }

        if (!empty($asignatura_id)) {
            $sql .= " AND u.asignatura_id = ?";
            $params[] = $asignatura_id;
        }

        if (!empty($unidad_id)) {
            $sql .= " AND act.unidad_id = ?";
            $params[] = $unidad_id;
        }

        $sql .= " ORDER BY act.fecha_entrega ASC";

        // Ejecutar consulta principal
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $actividades = $stmt->fetchAll();

        // 4. CARGAR DATOS PARA LOS DESPLEGABLES (FILTROS)
        // Cursos
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE usuario_id = ?");
        $stmt->execute([$userId]);
        $cursos = $stmt->fetchAll();

        // Asignaturas (necesitamos curso_id para el filtro JS)
        $stmt = $this->db->prepare("SELECT id, nombre_asignatura, curso_id FROM asignaturas WHERE usuario_id = ?");
        $stmt->execute([$userId]);
        $asignaturas = $stmt->fetchAll();

        // Unidades (necesitamos asignatura_id para el filtro JS)
        $stmt = $this->db->prepare("
            SELECT u.id, u.nombre_unidad, u.asignatura_id 
            FROM unidades_didacticas u
            JOIN asignaturas a ON u.asignatura_id = a.id
            WHERE a.usuario_id = ?
        ");
        $stmt->execute([$userId]);
        $unidades = $stmt->fetchAll();

        // 5. ENVIAR A LA VISTA
        $this->Dashboardview('actividades/index', [
            'actividades' => $actividades,
            'cursos' => $cursos,
            'asignaturas' => $asignaturas,
            'unidades_filtro' => $unidades
        ]);
    }

    // FORMULARIO CREAR
    public function create() {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];

        // Cargar Cursos
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE usuario_id = ?");
        $stmt->execute([$userId]);
        $cursos = $stmt->fetchAll();

        // Cargar Asignaturas (Necesitamos curso_id para el filtro JS)
        $stmt = $this->db->prepare("SELECT id, nombre_asignatura, curso_id FROM asignaturas WHERE usuario_id = ?");
        $stmt->execute([$userId]);
        $asignaturas = $stmt->fetchAll();

        // Cargar Unidades (Necesitamos asignatura_id para el filtro JS)
        $stmt = $this->db->prepare("
            SELECT u.id, u.nombre_unidad, u.asignatura_id 
            FROM unidades_didacticas u
            JOIN asignaturas a ON u.asignatura_id = a.id
            WHERE a.usuario_id = ?
        ");
        $stmt->execute([$userId]);
        $unidades = $stmt->fetchAll();

        // Cargar COMPETENCIAS (Necesitamos asignatura_id para filtrar la tabla con JS)
        $stmt = $this->db->prepare("
            SELECT *
            FROM competencias 
            WHERE usuario_id = ?
        ");
        $stmt->execute([$userId]);
        $competencias = $stmt->fetchAll();

        // Cargar CRITERIOS (Necesitamos asignatura_id para filtrar la tabla con JS)
        $stmt = $this->db->prepare("
            SELECT *
            FROM criterios_evaluacion 
            WHERE usuario_id = ?
        ");
        $stmt->execute([$userId]);
        $criterios = $stmt->fetchAll();

        // Enviar todo a la vista
        $this->dashboardView('actividades/create', [
            'cursos' => $cursos,
            'asignaturas' => $asignaturas,
            'unidades' => $unidades,
            'competencias' => $competencias,
            'criterios_evaluacion' => $criterios
        ]);
    }

    // GUARDAR NUEVA
    public function store() {
        $this->requireAuth();
    
        // 1. Recoger datos básicos
        $nombre = trim($_POST['nombre_actividad'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha_entrega = !empty($_POST['fecha_entrega']) ? $_POST['fecha_entrega'] : null;
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;
        $unidad_id = $_POST['unidad_id'] ?? null;

        // 2. Recoger los ARRAYS de IDs (Checkboxes)
        $competencias_ids = $_POST['competencias'] ?? [];
        $criterios_ids = $_POST['criterios'] ?? [];

        // Validación básica
        if (empty($nombre) || empty($unidad_id)) {
            $_SESSION['error'] = "El nombre y la unidad son obligatorios.";
            $this->redirect('/actividades/crear');
            return;
        }

        try {
            // === INICIO DE TRANSACCIÓN ===
            $this->db->beginTransaction();

            // A) Insertar la Actividad
            $sqlActividad = "INSERT INTO actividades 
                            (unidad_id, nombre_actividad, descripcion, fecha_entrega, es_publico, usuario_id) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sqlActividad);
            $stmt->execute([$unidad_id, $nombre, $descripcion, $fecha_entrega, $es_publico, $_SESSION['user_id']]);
            
            // Recuperar el ID de la actividad recién creada
            $actividad_id = $this->db->lastInsertId();

            // B) Insertar Competencias (Tabla Pivot)
            if (!empty($competencias_ids)) {
                // Asegúrate que tu tabla en BBDD se llame 'actividad_competencia'
                $sqlComp = "INSERT INTO actividad_competencias (id_actividades, id_competencia) VALUES (?, ?)";
                $stmtComp = $this->db->prepare($sqlComp);
                
                foreach ($competencias_ids as $comp_id) {
                    // Evitar insertar vacíos
                    if (!empty($comp_id)) {
                        $stmtComp->execute([$actividad_id, $comp_id]);
                    }
                }
            }

            // C) Insertar Criterios (Tabla Pivot)
            if (!empty($criterios_ids)) {
                // Asegúrate que tu tabla en BBDD se llame 'actividad_criterio'
                $sqlCrit = "INSERT INTO actividad_criterio (id_actividades, id_criterio) VALUES (?, ?)";
                $stmtCrit = $this->db->prepare($sqlCrit);
                
                foreach ($criterios_ids as $crit_id) {
                    if (!empty($crit_id)) {
                        $stmtCrit->execute([$actividad_id, $crit_id]);
                    }
                }
            }

            // === CONFIRMAR TRANSACCIÓN ===
            $this->db->commit();

            $_SESSION['success'] = "Actividad creada correctamente.";
            $this->redirect('/actividades');

        } catch (PDOException $e) {
            $this->db->rollBack();
            // Si quieres ver el error en pantalla descomenta la siguiente línea:
            // die("Error SQL: " . $e->getMessage()); 
            $_SESSION['error'] = "Error al guardar la actividad: " . $e->getMessage();
            $this->redirect('/actividades/crear');
        }
    }

    // FORMULARIO EDITAR
    public function edit($id) {
        $this->requireAuth();
        $id = (int)$id;

        // 1. Obtener datos básicos de la actividad
        // Validamos que pertenezca a una unidad del usuario actual (por seguridad)
        $stmt = $this->db->prepare("
            SELECT a.*, u.asignatura_id, asig.curso_id 
            FROM actividades a
            JOIN unidades_didacticas u ON a.unidad_id = u.id
            JOIN asignaturas asig ON u.asignatura_id = asig.id
            WHERE a.id = ? AND asig.usuario_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $actividad = $stmt->fetch();

        if (!$actividad) {
            $_SESSION['error'] = "Actividad no encontrada o no tienes permiso.";
            $this->redirect('/actividades');
            return;
        }

        // 2. Obtener IDs de competencias seleccionadas previamente
        $stmtComp = $this->db->prepare("SELECT id_competencia FROM actividad_competencias WHERE id_actividades = ?");
        $stmtComp->execute([$id]);
        // Convertimos a un array simple de IDs: [1-3]
        $competencias_seleccionadas = $stmtComp->fetchAll(\PDO::FETCH_COLUMN);

        // 3. Obtener IDs de criterios seleccionados previamente
        $stmtCrit = $this->db->prepare("SELECT id_criterio FROM actividad_criterio WHERE id_actividades = ?");
        $stmtCrit->execute([$id]);
        $criterios_seleccionados = $stmtCrit->fetchAll(\PDO::FETCH_COLUMN);

        // 4. Cargar listas para los desplegables (igual que en create)
        // Cursos
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE usuario_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $cursos = $stmt->fetchAll();

        // Asignaturas
        $stmt = $this->db->prepare("SELECT * FROM asignaturas WHERE usuario_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $asignaturas = $stmt->fetchAll();

        // Unidades
        $stmt = $this->db->prepare("SELECT u.*, a.curso_id FROM unidades_didacticas u JOIN asignaturas a ON u.asignatura_id = a.id WHERE a.usuario_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $unidades = $stmt->fetchAll();

        // Competencias y Criterios (Globales o filtrados, según tu lógica original)
        // Aquí traigo todos, igual que en el create
        $stmt = $this->db->prepare("SELECT * FROM competencias"); 
        $stmt->execute();
        $competencias = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT * FROM criterios_evaluacion");
        $stmt->execute();
        $criterios_evaluacion = $stmt->fetchAll();

        // 5. Renderizar vista
        $this->dashboardView('actividades/edit', [
            'actividad' => $actividad,
            'competencias_seleccionadas' => $competencias_seleccionadas,
            'criterios_seleccionados' => $criterios_seleccionados,
            'cursos' => $cursos,
            'asignaturas' => $asignaturas,
            'unidades' => $unidades,
            'competencias' => $competencias,
            'criterios_evaluacion' => $criterios_evaluacion,
            'current_page' => 'actividades'
        ]);
    }

    // Procesa la actualización
    public function update($id) {
        $this->requireAuth();
        $id = (int)$id;

        // Recoger datos
        $nombre = trim($_POST['nombre_actividad'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha_entrega = !empty($_POST['fecha_entrega']) ? $_POST['fecha_entrega'] : null;
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;
        $unidad_id = $_POST['unidad_id'] ?? null;
        
        $competencias_ids = $_POST['competencias'] ?? [];
        $criterios_ids = $_POST['criterios'] ?? [];

        if (empty($nombre) || empty($unidad_id)) {
            $_SESSION['error'] = "Nombre y Unidad obligatorios.";
            $this->redirect("/actividades/editar/$id");
            return;
        }

        try {
            $this->db->beginTransaction();

            // 1. Actualizar tabla actividades
            $sql = "UPDATE actividades SET nombre_actividad = ?, descripcion = ?, fecha_entrega = ?, es_publico = ?, unidad_id = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nombre, $descripcion, $fecha_entrega, $es_publico, $unidad_id, $id]);

            // 2. Actualizar Competencias (Borrar anteriores e insertar nuevas)
            $this->db->prepare("DELETE FROM actividad_competencias WHERE id_actividades = ?")->execute([$id]);
            
            if (!empty($competencias_ids)) {
                $stmtComp = $this->db->prepare("INSERT INTO actividad_competencias (id_actividades, id_competencia) VALUES (?, ?)");
                foreach ($competencias_ids as $c_id) {
                    $stmtComp->execute([$id, $c_id]);
                }
            }

            // 3. Actualizar Criterios (Borrar anteriores e insertar nuevos)
            $this->db->prepare("DELETE FROM actividad_criterio WHERE id_actividades = ?")->execute([$id]);

            if (!empty($criterios_ids)) {
                $stmtCrit = $this->db->prepare("INSERT INTO actividad_criterio (id_actividades, id_criterio) VALUES (?, ?)");
                foreach ($criterios_ids as $crit_id) {
                    $stmtCrit->execute([$id, $crit_id]);
                }
            }

            $this->db->commit();
            $_SESSION['success'] = "Actividad actualizada correctamente.";
            $this->redirect('/actividades');

        } catch (\PDOException $e) {
            $this->db->rollBack();
            error_log("Error update actividad: " . $e->getMessage());
            $_SESSION['error'] = "Error al actualizar: " . $e->getMessage();
            $this->redirect("/actividades/editar/$id");
        }
    }

    // ELIMINAR
    public function delete($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        $stmt = $this->db->prepare("
            DELETE FROM actividades 
            WHERE id = ? AND usuario_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);

        $_SESSION['success'] = "Unidad eliminada correctamente.";
        $this->redirect('/actividades');
    }
}