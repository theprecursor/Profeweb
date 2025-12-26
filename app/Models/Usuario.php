<?php

namespace App\Models;
use PDO;

// Asumimos que Database.php proporciona la conexión $db (instancia de PDO)

class Usuario
{
    private $db;

    // ELIMINA O COMENTA EL CONSTRUCTOR ANTERIOR QUE TENÍA \App\Core\Database
    /* 
    public function __construct(\App\Core\Database $db) {
        $this->db = $db->getConnection(); 
    }
    */

    // USA ESTE NUEVO CONSTRUCTOR:
    public function __construct($db)
    {
        // Ahora aceptamos directamente la conexión (sea PDO o lo que venga del controlador)
        $this->db = $db;
    }

    /**
     * Inserta un nuevo docente en la tabla 'usuarios'.
     * @param array $datos_usuario Contiene el email, el hash de la contraseña y otros campos.
     * @return bool|string True si es exitoso, o mensaje de error si falla (ej. email duplicado).
     */
    public function crearUsuario(array $datos_usuario): bool
    {
        // El RNF de seguridad exige sentencias preparadas PDO.
        $sql = "INSERT INTO usuarios (email, password, nombre, slug_perfil, biografia) 
                VALUES (:email, :password, :nombre, :slug_perfil, :biografia)";

        try {
            $stmt = $this->db->prepare($sql);

            // Los valores se enlazan de forma segura a los placeholders (:param)
            return $stmt->execute([
                'email' => $datos_usuario['email'],
                'password' => $datos_usuario['password'], // La contraseña ya debe venir HASHED desde el controlador
                'nombre' => $datos_usuario['nombre'],
                'slug_perfil' => $datos_usuario['slug_perfil'],
                'biografia' => $datos_usuario['biografia'] ?? null
            ]);

        } catch (\PDOException $e) {
            // Manejo de errores (ej. si el email ya existe, que es NOT NULL UNIQUE [11])
            // En este punto, solo devolvemos falso o manejamos el error a nivel superior.
            error_log("Error de inserción de usuario: " . $e->getMessage());
            // Si el error es una violación de unicidad (email duplicado), podemos devolver false.
            return false;
        }
    }
    
    // Método para actualizar el perfil
    public function update($id, $data)
    {
        // Sentencia SQL con placeholders (:nombre) por seguridad
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, 
                    biografia = :biografia 
                WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            
            // Vinculamos los valores
            $stmt->bindValue(':nombre', $data['nombre']);
            $stmt->bindValue(':biografia', $data['biografia']);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (\PDOException $e) {
            // Es buena práctica registrar el error en logs internos
            error_log("Error al actualizar perfil: " . $e->getMessage());
            return false;
        }
    }

    // Método para obtener un usuario por ID (si no lo tienes ya)
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateEmail($id, $newEmail)
    {
        // Primero verificamos que el email no esté en uso por otro usuario
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$newEmail, $id]);
        if ($stmt->fetch()) {
            return false; // El email ya existe
        }

        $stmt = $this->db->prepare("UPDATE usuarios SET email = ? WHERE id = ?");
        return $stmt->execute([$newEmail, $id]);
    }

    // Actualizar solo la Contraseña
    public function updatePassword($id, $newPasswordHash)
    {
        $stmt = $this->db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        return $stmt->execute([$newPasswordHash, $id]);
    }

    // Obtener la contraseña actual (hash) para verificar antes de cambiar
    public function getPasswordById($id)
    {
        $stmt = $this->db->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ? $result['password'] : null;
    }
}
