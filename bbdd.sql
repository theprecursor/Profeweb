-- 1. Crear la base de datos (si no existe) y usarla
CREATE DATABASE IF NOT EXISTS profeweb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE profeweb;

-- 2. Tabla Usuarios (Docentes)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    slug_perfil VARCHAR(100) NOT NULL UNIQUE,
    biografia TEXT
) ENGINE=InnoDB;

-- 3. Tabla Cursos
CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre_curso VARCHAR(255) NOT NULL,
    es_publico TINYINT(1) DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Tabla Asignaturas
CREATE TABLE asignaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    curso_id INT NOT NULL,
    nombre_asignatura VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255),
    es_publico TINYINT(1) DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Tabla Unidades Didácticas
CREATE TABLE unidades_didacticas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asignatura_id INT NOT NULL,
    usuario_id INT NOT NULL,
    curso_id INT NOT NULL,
    nombre_unidad VARCHAR(255) NOT NULL,
    descripcion VARCHAR(255),
    orden INT DEFAULT 0,
    es_publico TINYINT(1) DEFAULT 0,
    FOREIGN KEY (asignatura_id) REFERENCES asignaturas(id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Tabla Criterios de Evaluación
CREATE TABLE criterios_evaluacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_criterio VARCHAR(50) NOT NULL,
    usuario_id INT NOT NULL,
    es_publico TINYINT(1) DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Tabla Competencias
CREATE TABLE competencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_competencia VARCHAR(50) NOT NULL,
    usuario_id INT NOT NULL,
    es_publico TINYINT(1) DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. Tabla Actividades
CREATE TABLE actividades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unidad_id INT NOT NULL,
    nombre_actividad VARCHAR(255) NOT NULL,
    fecha_entrega DATE,
    descripcion VARCHAR(255),
    es_publico TINYINT(1) DEFAULT 0,
    usuario_id INT NOT NULL,
    FOREIGN KEY (unidad_id) REFERENCES unidades_didacticas(id) ON DELETE CASCADE,
    FOREIGN KEY (criterio_id) REFERENCES criterios_evaluacion(id) ON DELETE CASCADE,
    FOREIGN KEY (competencia_id) REFERENCES competencias(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 10. Tabla 1:N actividad-competencias
CREATE TABLE actividad_competencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_actividades INT NOT NULL,
    id_competencia VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_actividades) REFERENCES actividades(id) ON DELETE CASCADE,
    FOREIGN KEY (id_competencia) REFERENCES competencias(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 11. Tabla 1:N actividad-criterios
CREATE TABLE actividad_criterio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_actividades INT NOT NULL,
    id_criterio VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_actividades) REFERENCES actividades(id) ON DELETE CASCADE,
    FOREIGN KEY (id_criterio) REFERENCES criterios_evaluacion(id) ON DELETE CASCADE
) ENGINE=InnoDB;
