<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Clase Database: Conexión PDO con patrón Singleton.
 * Asegura una única conexión activa a la BBDD (eficiencia) [15, 16].
 */
class Database {
    /** @var Database Instancia única de la clase */
    private static $instance = null;

    /** @var PDO Objeto de conexión PDO [1, 17] */
    private $pdo;

    /**
     * Constructor privado: Conecta a la base de datos.
     */
    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $options = [
            // 1. Manejo de Errores: Lanzar excepciones en caso de fallo (recomendado) [18, 19]
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            
            // 2. Modo de Fetch: Devolver resultados como array asociativo por defecto [19]
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            
            // 3. Seguridad: Deshabilitar la emulación de sentencias preparadas [19-21]
            // Esto es crucial para asegurar que PDO y las sentencias preparadas sean el único método 
            // eficaz para prevenir Inyección SQL [1, 17].
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Manejo de errores: En desarrollo se puede mostrar el error, en producción se debe registrar
            // y mostrar un error genérico [14, 22].
            die('Error de conexión a la BBDD: ' . $e->getMessage());
        }
    }

    // Previene la clonación de la instancia (Singleton)
    private function __clone() {}

    // Previene la deserialización (Singleton)
    public function __wakeup() {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * Obtiene la única instancia de la conexión a la base de datos.
     * @return Database La instancia Singleton.
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Obtiene el objeto PDO para realizar operaciones de BBDD.
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->pdo;
    }
}
?>