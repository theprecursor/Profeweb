<?php

namespace App\Models;

// Asumimos que Database.php proporciona la conexión $db (instancia de PDO)

class Usuario
{
    private $db;

    // El modelo necesita la conexión a la base de datos (PDO) para operar.
    public function __construct(\App\Core\Database $db)
    {
        $this->db = $db->getConnection(); // Asumimos que Database::getConnection() devuelve la instancia PDO
    }

    /**
     * Inserta un nuevo docente en la tabla 'usuarios'.
     * @param array $datos_usuario Contiene el email, el hash de la contraseña y otros campos.
     * @return bool|string True si es exitoso, o mensaje de error si falla (ej. email duplicado).
     */
    public function crearUsuario(array $datos_usuario): bool
    {
        // El RNF de seguridad exige sentencias preparadas PDO.
        $sql = "INSERT INTO usuarios (email, password, nombre, apellidos, slug_perfil, biografia) 
                VALUES (:email, :password, :nombre, :apellidos, :slug_perfil, :biografia)";

        try {
            $stmt = $this->db->prepare($sql);

            // Los valores se enlazan de forma segura a los placeholders (:param)
            return $stmt->execute([
                'email' => $datos_usuario['email'],
                'password' => $datos_usuario['password'], // La contraseña ya debe venir HASHED desde el controlador
                'nombre' => $datos_usuario['nombre'],
                'apellidos' => $datos_usuario['apellidos'] ?? null,
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
    
    // Aquí irían otros métodos del CRUD, como buscarUsuarioPorEmail, etc.
}
