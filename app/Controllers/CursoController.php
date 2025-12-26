<?php
namespace App\Controllers;

use App\Core\Controller;

class CursoController extends Controller {

    // Middleware simple para proteger la ruta
    private function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    // 1. LISTADO (GET /cursos)
    public function index() {
        $this->requireAuth();
        
        // Obtenemos los cursos del usuario logueado
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE usuario_id = ? ORDER BY nombre_curso ASC");
        $stmt->execute([$_SESSION['user_id']]);
        $cursos = $stmt->fetchAll();

        // Renderizamos la vista pasando los datos
        // 'current_page' sirve para marcar activo el menú lateral
        $this->dashboardView('cursos/index', [
            'cursos' => $cursos,
            'current_page' => 'cursos'
        ]);
    }

    // 2. FORMULARIO DE CREACIÓN (GET /cursos/crear)
    public function create() {
        $this->requireAuth();
        $this->dashboardView('cursos/create', [
            'current_page' => 'cursos'
        ]);
    }

    // GUARDAR (POST /curso/crear)
    public function store() {
        $this->requireAuth();

        $nombre = trim($_POST['nombre_curso'] ?? '');

        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre del curso es obligatorio.";
            $this->redirect('/cursos/crear');
            return;
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO cursos (usuario_id, nombre_curso) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $nombre]);
            
            $_SESSION['success'] = "Curso creado correctamente.";
            $this->redirect('/cursos');
        } catch (\PDOException $e) {
            $_SESSION['error'] = "Error al guardar: " . $e->getMessage();
            $this->redirect('/cursos/crear');
        }
    }

    public function edit($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        // CONSULTA CORREGIDA
        // Usamos 'fetch()' en singular, no 'fetchAll()'
        // fetch() devuelve un solo array plano ['id' => 1, 'nombre' => '...']
        // fetchAll() devolvía una lista [[...]] lo que causaba el error de claves no definidas.
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $curso = $stmt->fetch(); 

        // Validación: Si no existe el curso, redirigir
        if (!$curso) {
            $_SESSION['error'] = "Curso no encontrado o no tienes permiso para editarlo.";
            $this->redirect('/cursos');
            return;
        }

        // Enviamos la variable 'curso' a la vista
        $this->dashboardView('cursos/edit', [
            'curso' => $curso,
            'current_page' => 'cursos'
        ]);
    }

    // ACTUALIZAR
    public function update($id)
    {
        $this->requireAuth();
        $id = (int)$id;

        // 1. Verificar que la petición sea POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/cursos');
            return;
        }

        // 2. Recoger y limpiar datos
        $nombre = trim($_POST['nombre_curso'] ?? '');
        
        // Manejo del checkbox: si no está marcado, no se envía en $_POST, por eso usamos isset
        // Si existe vale 1, si no existe vale 0.
        $es_publico = isset($_POST['es_publico']) ? 1 : 0;

        // 3. Validación básica
        if (empty($nombre)) {
            $_SESSION['error'] = "El nombre del curso es obligatorio.";
            // Redirigimos al formulario de edición para que corrija
            $this->redirect('curso/editar/' . $id . '');
            return;
        }

        try {
            // 4. Actualizar en Base de Datos
            // IMPORTANTE: El WHERE incluye 'usuario_id' para seguridad (Ownership)
            $sql = "UPDATE cursos 
                    SET nombre_curso = ?, es_publico = ? 
                    WHERE id = ? AND usuario_id = ?";
            
            $stmt = $this->db->prepare($sql);
            // Usamos sentencias preparadas para prevenir Inyección SQL [3, 4]
            $stmt->execute([$nombre, $es_publico, $id, $_SESSION['user_id']]);

            // Verificamos si se tocó alguna fila
            // Si rowCount es 0, puede ser que no existía el curso o que los datos eran idénticos.
            // En este caso asumimos éxito si no saltó excepción.
            $_SESSION['success'] = "Curso actualizado correctamente.";
            
            $this->redirect('/cursos');

        } catch (\PDOException $e) {
            // Log del error real para el desarrollador
            error_log("Error al actualizar curso: " . $e->getMessage());
            
            $_SESSION['error'] = "Hubo un error al guardar los cambios.";
            $this->redirect('/curso/editar/' . $id . '');
        }
    }
    // ELIMINAR (POST /curso/{id}/eliminar)
    public function delete($id) {
        $this->requireAuth();
        
        // Borrado seguro: verificamos que el curso pertenezca al usuario
        $stmt = $this->db->prepare("DELETE FROM cursos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        
        $_SESSION['success'] = "Curso eliminado.";
        $this->redirect('/curso');
    }
}