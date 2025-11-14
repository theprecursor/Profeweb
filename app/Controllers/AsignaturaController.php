<?php

namespace App\Controllers;

use App\Models\Asignatura;
use App\Core\Database;

class AsignaturaController
{
    protected $asignaturaModel;
    
    public function __construct(Database $db)
    {
        // Se inyecta la conexión de la BBDD al Modelo [20, 21]
        $this->asignaturaModel = new Asignatura($db);
    }

    // =========================================================
    // CRUD: INDEX (READ - Petición GET para cargar la tabla)
    // =========================================================
    public function index(): void
    {
        header('Content-Type: application/json');
        
        // 🚨 Obtener ID del docente desde la sesión (Simulación)
        // En producción, esto se obtendría de la sesión PHP segura [22]
        $docente_id = $_SESSION['user_id'] ?? 1; 

        $asignaturas = $this->asignaturaModel->getAll($docente_id);

        // Devolver la lista completa de asignaturas para la Vista (JavaScript)
        echo json_encode([
            'success' => true,
            'data' => $asignaturas
        ]);
    }

    // =========================================================
    // CRUD: UPDATE (Editar - Petición PUT/PATCH)
    // =========================================================
    public function update(int $id): void 
    {
        header('Content-Type: application/json');

        // Leer JSON del cuerpo de la petición Fetch (ya que no es un formulario POST tradicional) [23]
        $data = json_decode(file_get_contents('php://input'), true);

        // Validación (Controlador: formato y coherencia) [24]
        if (empty($data['nombre']) || !isset($data['es_publico'])) {
            http_response_code(400); 
            echo json_encode(['success' => false, 'message' => 'Faltan datos requeridos.']);
            return;
        }

        if ($this->asignaturaModel->update($id, $data)) {
            http_response_code(200);
            echo json_encode([
                'success' => true, 
                'message' => 'Asignatura actualizada correctamente.',
                'data' => $data // Devolvemos los datos actualizados
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar en la BBDD.']);
        }
    }

    // =========================================================
    // CRUD: DESTROY (Borrar - Petición DELETE)
    // =========================================================
    public function destroy(int $id): void
    {
        header('Content-Type: application/json');

        if ($this->asignaturaModel->delete($id)) {
            http_response_code(200);
            echo json_encode([
                'success' => true, 
                'message' => 'Asignatura eliminada correctamente.',
                'id' => $id
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la asignatura.']);
        }
    }

    // ... el método store() para CREATE ya fue provisto.
}