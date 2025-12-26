<?php
namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    private function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $this->requireAuth();
        $userId = $_SESSION['user_id'];

        // 1. Datos básicos del profesor
        $stmt = $this->db->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
        $stmt->execute([$userId]);
        $profesor = $stmt->fetch();

        // 2. OBTENER ESTADÍSTICAS (Contadores)
        
        // A. Cursos (Directo por usuario_id)
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM cursos WHERE usuario_id = ?");
        $stmt->execute([$userId]);
        $totalCursos = $stmt->fetch()['total'];

        // B. Asignaturas (Directo por usuario_id)
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM asignaturas WHERE usuario_id = ?");
        $stmt->execute([$userId]);
        $totalAsignaturas = $stmt->fetch()['total'];

        // C. Unidades Didácticas (Vinculadas a través de asignaturas)
        $stmt = $this->db->prepare("
            SELECT COUNT(id) as total 
            FROM unidades_didacticas
            WHERE usuario_id = ?
        ");
        $stmt->execute([$userId]);
        $totalUnidades = $stmt->fetch()['total'];

        // D. Competencias (Vinculadas a través de asignaturas)
        $stmt = $this->db->prepare("
            SELECT COUNT(id) as total 
            FROM competencias c 
            WHERE usuario_id = ?
        ");
        $stmt->execute([$userId]);
        $totalCompetencias = $stmt->fetch()['total'];

        // E. Criterios de Evaluación (Vinculadas a través de asignaturas)
        $stmt = $this->db->prepare("
            SELECT COUNT(id) as total 
            FROM criterios_evaluacion  
            WHERE usuario_id = ?
        ");
        $stmt->execute([$userId]);
        $totalCriterios = $stmt->fetch()['total'];

        // 3. Renderizar la vista pasando todos los datos
       $this->view('dashboard/index', [
            'profesor' => $profesor,
            'stats' => [
                'cursos' => $totalCursos,
                'asignaturas' => $totalAsignaturas,
                'unidades' => $totalUnidades,
                'competencias' => $totalCompetencias,
                'criterios' => $totalCriterios
            ],
            'current_page' => 'dashboard'
        ]);
    }

    // Muestra la vista de "Mi Perfil" (Sin cambios, mantenemos tu lógica)
    public function perfil()
    {
        $this->requireAuth();
        
        // Usamos el modelo Usuario si está disponible, o consulta directa si prefieres
        // Aquí mantengo la coherencia con tu código anterior
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $usuario = $stmt->fetch();

        $this->dashboardView('perfil', [
            'usuario' => $usuario,
            'current_page' => 'perfil'
        ]);
    }

    // Procesa la actualización de datos
    public function updatePerfil()
    {
        // 1. Seguridad: Solo usuarios logueados
        $this->requireAuth();

        // 2. Verificar que la petición sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 3. Recoger y limpiar datos (Sanitización)
            $nombre = trim($_POST['nombre'] ?? '');
            $biografia = trim($_POST['biografia'] ?? '');

            // 4. Validación básica
            if (empty($nombre)) {
                $_SESSION['error'] = "El nombre esn obligatorio.";
                $this->redirect('/perfil');
                return;
            }

            // 5. Instanciar el modelo y preparar datos
            // Pasamos $this->db que es la conexión PDO
            $usuarioModel = new \App\Models\Usuario($this->db);
            
            $datos = [
                'nombre' => $nombre,
                'biografia' => $biografia
            ];

            // 6. Intentar actualizar en la BBDD
            if ($usuarioModel->update($_SESSION['user_id'], $datos)) {
                // ÉXITO:
                // Actualizamos la sesión para que el nombre cambie en el menú al instante
                $_SESSION['user_name'] = $nombre; 
                $_SESSION['success'] = "Perfil actualizado correctamente.";
            } else {
                // ERROR:
                $_SESSION['error'] = "Hubo un problema al guardar los cambios en la base de datos.";
            }

            // 7. Redirigir de vuelta al perfil
            $this->redirect('/perfil');
        } else {
            // Si intentan entrar por GET a esta ruta, los mandamos al perfil
            $this->redirect('/perfil');
        }
    }

    // Procesa el cambio de Email
    public function cambiarEmail()
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nuevo_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

            if (!filter_var($nuevo_email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "El formato del correo no es válido.";
                $this->redirect('/perfil');
                return;
            }

            $usuarioModel = new \App\Models\Usuario($this->db);
            
            if ($usuarioModel->updateEmail($_SESSION['user_id'], $nuevo_email)) {
                $_SESSION['success'] = "Correo electrónico actualizado.";
            } else {
                $_SESSION['error'] = "El correo ya está en uso o hubo un error.";
            }
            $this->redirect('/perfil');
        }
    }

    // Procesa el cambio de Contraseña
    public function cambiarPassword()
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pass_actual = $_POST['current_password'] ?? '';
            $pass_nueva = $_POST['new_password'] ?? '';

            if (strlen($pass_nueva) < 8) {
                $_SESSION['error'] = "La nueva contraseña debe tener al menos 8 caracteres.";
                $this->redirect('/perfil');
                return;
            }

            $usuarioModel = new \App\Models\Usuario($this->db);
            
            // 1. Verificar que la contraseña actual sea correcta
            $hash_actual_db = $usuarioModel->getPasswordById($_SESSION['user_id']);
            
            if (!password_verify($pass_actual, $hash_actual_db)) {
                $_SESSION['error'] = "La contraseña actual es incorrecta. No se realizaron cambios.";
                $this->redirect('/perfil');
                return;
            }

            // 2. Cifrar la nueva contraseña y guardarla
            $nuevo_hash = password_hash($pass_nueva, PASSWORD_DEFAULT);
            
            if ($usuarioModel->updatePassword($_SESSION['user_id'], $nuevo_hash)) {
                $_SESSION['success'] = "Contraseña actualizada correctamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar la contraseña.";
            }
            $this->redirect('/perfil');
        }
    }
}