<?php

namespace App\Models; // Namespace del Modelo [1]

use PDO;
use PDOException;
use App\Core\Database; // 🚨 NECESARIO: Importar la clase Database del Core para DI [4, 5]

/**
 * Clase Modelo Usuario: Maneja el CRUD en la tabla 'usuarios'.
 * Esta clase representa la lógica del negocio para la gestión de docentes [1, 3, 6].
 */
class Usuario
{
    /** @var PDO $db Objeto de conexión PDO, obtenido del Singleton Database. */
    private $db;

    // La tabla de la BBDD a la que se accede es 'usuarios' [7, 8].
    private const TABLE_NAME = 'usuarios';

    /**
     * Constructor que recibe la dependencia de la conexión a la BBDD (Inyección de Dependencias).
     * @param Database $db Instancia Singleton de la conexión segura.
     */
    public function __construct(Database $db)
    {
        // Obtiene el objeto PDO configurado para seguridad (deshabilitando emulación) [4, 9-11].
        $this->db = $db->getConnection(); 
    }

    // =========================================================
    // LÓGICA DE PERSISTENCIA (CREATE - Usado en Registro)
    // =========================================================

    /**
     * Inserta un nuevo docente en la tabla 'usuarios'.
     * @param array $datos_usuario Contiene email, nombre, y el hash de la contraseña [12].
     * @return bool True si es exitoso, false si falla (ej. email duplicado) [12].
     */
    public function crearUsuario(array $datos_usuario): bool
    {
        // El requisito fundamental (RF) es que el Controlador ya cifre la contraseña con password_hash() [13-16].
        $sql = "INSERT INTO " . self::TABLE_NAME . " 
                (email, password, nombre, apellidos, slug_perfil, biografia)
                VALUES (:email, :password, :nombre, :apellidos, :slug_perfil, :biografia)";

        try {
            // 🚨 RNF de Seguridad: Uso de Sentencias Preparadas PDO [12, 13, 17, 18].
            $stmt = $this->db->prepare($sql);

            // Los valores se enlazan de forma segura a los placeholders [15].
            return $stmt->execute([
                'email' => $datos_usuario['email'],
                'password' => $datos_usuario['password'], 
                'nombre' => $datos_usuario['nombre'],
                // Campos opcionales o con valores predeterminados [8]
                'apellidos' => $datos_usuario['apellidos'] ?? null, 
                'slug_perfil' => $datos_usuario['slug_perfil'],
                'biografia' => $datos_usuario['biografia'] ?? null
            ]);

        } catch (PDOException $e) {
            // Manejo de errores de la BBDD (ej. violación de unicidad del email) [19].
            error_log("Error de inserción de usuario: " . $e->getMessage()); 
            return false;
        }
    }

    // =========================================================
    // LÓGICA DE BÚSQUEDA (READ - Usado en Login y Validación)
    // =========================================================

    /**
 * Busca un usuario en la tabla 'usuarios' por email.
 * @param string $email
 * @return array|false Devuelve ID, nombre y el hash de la contraseña, o false.
 */
public function findByEmail(string $email): array|false
{
    // 🚨 Sentencia preparada PDO. La tabla se llama 'usuarios' [3].
    // Se seleccionan los campos necesarios para la sesión y la verificación:
    $sql = "SELECT id, nombre, password FROM usuarios WHERE email = :email";

    try {
        // PDO::prepare() separa el SQL del dato de entrada [11, 12].
        $stmt = $this->db->prepare($sql); 
        
        // El dato (email) se enlaza de forma segura a los placeholders [13, 14].
        $stmt->execute([':email' => $email]); 
        
        // Retornamos el resultado como un array asociativo [15].
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    } catch (\PDOException $e) {
        // En caso de fallo de BBDD, se registra el error.
        error_log("Error de búsqueda de autenticación: " . $e->getMessage()); 
        return false;
    }
}
}