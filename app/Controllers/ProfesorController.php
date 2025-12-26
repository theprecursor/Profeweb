<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ProfesorController extends Controller
{
    /**
     * Muestra el perfil público del profesor y su estructura académica.
     * Ruta: /profesor/{id}
     */
    public function show($id)
    {
        // 1. Sanitización básica del ID
        $id = (int)$id;

        // 2. Obtener datos del Profesor
        // Se busca en la tabla usuarios y se valida que exista [1].
        $sqlProfesor = "SELECT id, nombre, biografia, email 
                        FROM usuarios 
                        WHERE id = ?";
        $stmt = $this->db->prepare($sqlProfesor);
        $stmt->execute([$id]);
        $profesor = $stmt->fetch();

        if (!$profesor) {
            // Si el profesor no existe, mostramos error 404 [2]
            http_response_code(404);
            echo "Profesor no encontrado.";
            return;
        }

        // 3. Obtener la Estructura Académica (Solo contenido PÚBLICO)
        // Hacemos un JOIN masivo para traer Cursos, Asignaturas, Unidades y Actividades.
        // Filtramos por es_publico = 1 en cada nivel para respetar la privacidad [3-5].
        $sqlEstructura = "
            SELECT 
                c.id AS curso_id, c.nombre_curso,
                a.id AS asig_id, a.nombre_asignatura, a.descripcion AS asig_desc,
                u.id AS unidad_id, u.nombre_unidad,
                act.id AS act_id, act.nombre_actividad, act.descripcion AS act_desc, act.fecha_entrega
            FROM asignaturas a
            JOIN cursos c ON a.curso_id = c.id
            LEFT JOIN unidades_didacticas u ON a.id = u.asignatura_id AND u.es_publico = 1
            LEFT JOIN actividades act ON u.id = act.unidad_id AND act.es_publico = 1
            WHERE a.usuario_id = ? AND a.es_publico = 1
            ORDER BY c.nombre_curso, a.nombre_asignatura, u.orden, act.fecha_entrega
        ";

        $stmt = $this->db->prepare($sqlEstructura);
        $stmt->execute([$id]);
        $filas = $stmt->fetchAll();

        // 4. Procesar los datos para crear la jerarquía (Árbol)
        // Transformamos filas planas en: Curso -> Asignatura -> Unidad -> Actividades
        $estructura = [];
        $actividades_ids = []; // Guardaremos los IDs para buscar competencias/criterios después

        foreach ($filas as $fila) {
            $cursoNombre = $fila['nombre_curso'];
            $asigId = $fila['asig_id'];
            $unidadId = $fila['unidad_id'];
            $actId = $fila['act_id'];

            // Nivel 1: Curso
            if (!isset($estructura[$cursoNombre])) {
                $estructura[$cursoNombre] = [];
            }

            // Nivel 2: Asignatura
            if (!isset($estructura[$cursoNombre][$asigId])) {
                $estructura[$cursoNombre][$asigId] = [
                    'id' => $asigId,
                    'nombre_asignatura' => $fila['nombre_asignatura'],
                    'descripcion' => $fila['asig_desc'],
                    'unidades' => []
                ];
            }

            // Nivel 3: Unidad (Solo si existe unidad, ya que el LEFT JOIN puede traer nulos)
            if ($unidadId) {
                if (!isset($estructura[$cursoNombre][$asigId]['unidades'][$unidadId])) {
                    $estructura[$cursoNombre][$asigId]['unidades'][$unidadId] = [
                        'id' => $unidadId,
                        'nombre_unidad' => $fila['nombre_unidad'],
                        'actividades' => []
                    ];
                }

                // Nivel 4: Actividad (Solo si existe actividad)
                if ($actId) {
                    $estructura[$cursoNombre][$asigId]['unidades'][$unidadId]['actividades'][$actId] = [
                        'id' => $actId,
                        'nombre_actividad' => $fila['nombre_actividad'],
                        'descripcion' => $fila['act_desc'],
                        'fecha_entrega' => $fila['fecha_entrega'],
                        'competencias' => [], // Se llenarán en el paso 5
                        'criterios' => []     // Se llenarán en el paso 5
                    ];
                    $actividades_ids[] = $actId;
                }
            }
        }

        // 5. Obtener Competencias y Criterios (Optimización)
        // En lugar de hacer una consulta por cada actividad (N+1), hacemos 2 consultas grandes
        // usando WHERE IN (...) para todas las actividades visibles.
        if (!empty($actividades_ids)) {
            $inQuery = implode(',', array_fill(0, count($actividades_ids), '?'));

            // A) Competencias
            $sqlComp = "
                SELECT ac.id_actividades, c.id, c.codigo_competencia
                FROM actividad_competencias ac
                JOIN competencias c ON ac.id_competencia = c.id
                WHERE ac.id_actividades IN ($inQuery)
            ";
            $stmtComp = $this->db->prepare($sqlComp);
            $stmtComp->execute($actividades_ids);
            $competencias = $stmtComp->fetchAll();

            // B) Criterios
            $sqlCrit = "
                SELECT acp.id_actividades, cr.codigo_criterio
                FROM actividad_criterio acp
                JOIN criterios_evaluacion cr ON acp.id_criterio = cr.id
                WHERE acp.id_actividades IN ($inQuery)
            ";
            $stmtCrit = $this->db->prepare($sqlCrit);
            $stmtCrit->execute($actividades_ids);
            $criterios = $stmtCrit->fetchAll();

            // C) Asignar a la estructura (Mapeo en memoria)
            // Recorremos la estructura anidada para inyectar los detalles
            foreach ($estructura as $curso => &$asignaturas) {
                foreach ($asignaturas as &$asig) {
                    foreach ($asig['unidades'] as &$uni) {
                        foreach ($uni['actividades'] as $actId => &$act) {
                            
                            // Filtrar y asignar competencias de esta actividad
                            $act['competencias'] = array_filter($competencias, function($c) use ($actId) {
                                return $c['id_actividades'] == $actId;
                            });
                            // Reindexar array para que JSON_ENCODE no cree objetos extraños
                            $act['competencias'] = array_values($act['competencias']); 

                            // Filtrar y asignar criterios de esta actividad
                            $act['criterios'] = array_filter($criterios, function($cr) use ($actId) {
                                return $cr['id_actividades'] == $actId;
                            });
                            $act['criterios'] = array_values($act['criterios']);
                        }
                    }
                }
            }
        }

        // 6. Renderizar la Vista
        $this->view('profesor/show', [
            'profesor' => $profesor,
            'estructura_academica' => $estructura,
            'page_title' => 'Perfil de ' . $profesor['nombre']
        ]);
    }
}