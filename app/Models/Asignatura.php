<?php

namespace App\Models;

use PDO;
use App\Core\Database; // Necesitamos la clase Database
use PDOException;

class Asignatura
{
    private $db;

    public function __construct(Database $database)
    {
        // Obtiene la conexión PDO segura [16]
        $this->db = $database->getConnection(); 
    }

    // =========================================================
    // CRUD: READ (Ver)
    // =========================================================
    /**
     * Obtiene todas las asignaturas creadas por un docente.
     * @param int $docente_id ID del docente autenticado (debería venir de la sesión).
     * @return array Lista de asignaturas.
     */
    public function getAll(int $docente_id): array
    {
        // Obtener datos de la tabla asignaturas [17]
        $sql = "SELECT id, nombre_asignatura, es_publico, created_at 
                FROM asignaturas 
                WHERE usuario_id = :usuario_id 
                ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':usuario_id' => $docente_id]);
        
        // El modo de fetch predeterminado es PDO::FETCH_ASSOC [13], devolviendo un array asociativo.
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    
    // El método create() ya fue provisto en la respuesta anterior (INSERT usando PDO)
    // ...
    
    // =========================================================
    // CRUD: UPDATE (Editar)
    // =========================================================
    /**
     * Modifica una asignatura existente.
     * @param int $id ID de la asignatura.
     * @param array $datos_actualizados Contiene 'nombre' y 'es_publico'.
     * @return bool
     */
    public function update(int $id, array $datos_actualizados): bool
    {
        $sql = "UPDATE asignaturas 
                SET nombre_asignatura = :nombre, es_publico = :es_publico 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $datos_actualizados['nombre'],
            ':es_publico' => (int)($datos_actualizados['es_publico'] ?? 0)
        ]);
    }

    // =========================================================
    // CRUD: DELETE (Borrar)
    // =========================================================
    /**
     * Elimina una asignatura de la base de datos.
     * @param int $id ID de la asignatura.
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM asignaturas WHERE id = :id";
        
        $stmt = $this->db->prepare($sql); // Uso de sentencias preparadas [18]
        return $stmt->execute([':id' => $id]); // [19]
    }
    
    // ... el resto de métodos (findById, create, etc.)
}