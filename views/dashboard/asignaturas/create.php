<!-- views/dashboard/asignaturas/create.php -->

<h2 class="mb-4">
    Nueva Asignatura
</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="/asignatura/crear">
            
            <!-- Nombre de la asignatura -->
            <div class="mb-3">
                <label for="nombre_asignatura" class="form-label fw-bold">
                    Nombre de la asignatura <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="nombre_asignatura" 
                       id="nombre_asignatura"
                       class="form-control form-control-lg" 
                       placeholder="Ej: Matemáticas 1º ESO" 
                       required 
                       autofocus>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label for="descripcion" class="form-label fw-bold">
                    Descripción (opcional)
                </label>
                <textarea name="descripcion" 
                          id="descripcion" 
                          rows="4" 
                          class="form-control" 
                          placeholder="Breve descripción de la asignatura, objetivos, nivel..."></textarea>
            </div>

            <!-- Curso -->
            <div class="mb-3 d-flex gap-4 align-items-center">
                
                <!-- Insertar el campo de formulario oculto que PHP necesita.
                    El atributo name="curso" asegura que se recoge en $_POST['curso'] -->
                <input type="hidden" name="curso" id="curso_id_hidden">
                
                <div class="dropdown">
                    <!-- Botón que activa el desplegable -->
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownCursos" data-bs-toggle="dropdown" aria-expanded="false">
                        Seleccionar Curso <!-- Este texto será reemplazado por JS -->
                    </button>
                    
                    <!-- Contenedor de las opciones -->
                    <ul class="dropdown-menu" aria-labelledby="dropdownCursos" id="curso-menu">
                        <?php foreach ($cursos as $c): ?>
                        <li>
                            <!-- Usar 'dropdown-item' y atributos 'data-*' para guardar el ID -->
                            <button class="dropdown-item select-curso-btn" 
                                    type="button" 
                                    data-curso-id="<?= $c['id'] ?>" 
                                    data-curso-nombre="<?= $c['nombre_curso'] ?>">
                                <?= $c['nombre_curso'] ?>
                            </button>
                        </li> 
                        <?php endforeach?>
                    </ul>
                </div>
            </div>          

            <!-- Visibilidad pública -->
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="es_publico" 
                           id="es_publico" 
                           value="1">
                    <label class="form-check-label fw-bold" for="es_publico">
                        Asignatura pública
                    </label>
                </div>
                <div class="form-text text-muted">
                    Si activas esta opción, otros profesores y alumnos podrán ver esta asignatura en tu perfil público.
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success btn-lg px-5">
                    Crear asignatura
                </button>
                <a href="/asignaturas" class="btn btn-secondary btn-lg">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Referencias a los elementos clave
    const inputOculto = document.getElementById('curso_id_hidden');
    const botonVisible = document.getElementById('dropdownCursos');
    const menuCursos = document.getElementById('curso-menu');

    // Escuchar el clic en cualquier elemento dentro del menú
    menuCursos.addEventListener('click', function(event) {
        
        // Determinar si el clic fue en un botón de curso
        const opcionSeleccionada = event.target.closest('.select-curso-btn');
        
        if (opcionSeleccionada) {
            // Obtener los valores del curso usando los atributos de datos
            const id = opcionSeleccionada.getAttribute('data-curso-id');
            const nombre = opcionSeleccionada.getAttribute('data-curso-nombre');

            // 1. RREGLO VISUAL: Mostrar la opción seleccionada en el botón
            botonVisible.textContent = nombre;

            // 2. ARREGLO PARA PHP: Asignar el ID al campo oculto que se enviará por POST
            inputOculto.value = id; 
        }
    });
});
</script>