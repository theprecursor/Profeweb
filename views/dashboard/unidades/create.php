<!-- views/dashboard/asignaturas/create.php -->

<h2 class="mb-4">
    Nueva Unidad didáctica
</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="/unidades/crear">
            
            <!-- Nombre de la asignatura -->
            <div class="mb-3">
                <label for="nombre_unidad" class="form-label fw-bold">
                    Nombre de la unidad didáctica <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="nombre_unidad" 
                       id="nombre_unidad"
                       class="form-control form-control-lg" 
                       placeholder="Ej: Ecuaciones de 2º Grado" 
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
                          placeholder="Breve descripción de la unidad, objetivos, nivel..."></textarea>
            </div>

            <!-- Contenedor de Selección de Cursos y Asignaturas -->
            <div class="mb-3 d-flex gap-4 align-items-center">
                
                <!-- CAMPO OCULTO PARA EL CURSO (Recogido en $_POST['curso']) -->
                <input type="hidden" name="curso" id="curso_id_hidden"> 

                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownCursos" data-bs-toggle="dropdown" aria-expanded="false">
                        Seleccionar Curso
                    </button>
                    
                    <ul class="dropdown-menu" aria-labelledby="dropdownCursos" id="curso-menu">
                        <!-- Iterar sobre los cursos -->
                        <?php foreach ($cursos as $c): ?>
                        <li>
                            <!-- Atributos de datos esenciales para JavaScript -->
                            <button class="dropdown-item select-item-btn" 
                                    type="button" 
                                    data-target-id="curso_id_hidden" 
                                    data-target-btn="dropdownCursos"
                                    data-value="<?= $c['id'] ?>">
                                <?= htmlspecialchars($c['nombre_curso']) ?>
                            </button>
                        </li> 
                        <?php endforeach?>
                    </ul>
                </div>
                
                <!-- Desplegable de Asignaturas -->
                <input type="hidden" name="asignatura" id="asignatura_id_hidden">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownAsignaturas" data-bs-toggle="dropdown" aria-expanded="false">
                        Seleccionar Asignatura
                    </button>
                    <!-- Dentro del desplegable de Asignaturas, en create.php -->
                    <ul class="dropdown-menu" aria-labelledby="dropdownAsignaturas" id="asignatura-menu">
                        <?php foreach ($asignaturas as $a): ?>
                        <li>
                            <button class="dropdown-item select-item-btn asignatura-option" 
                                    type="button" 
                                    data-target-id="asignatura_id_hidden" 
                                    data-target-btn="dropdownAsignaturas"
                                    data-value="<?= $a['id'] ?>"
                                    data-curso-id="<?= $a['curso_id'] ?>">
                                <?= htmlspecialchars($a['nombre_asignatura']) ?>
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
                        Unidad pública
                    </label>
                </div>
                <div class="form-text text-muted">
                    Si activas esta opción, otros profesores y alumnos podrán ver esta asignatura en tu perfil público.
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success btn-lg px-5">
                    Crear Unidad
                </button>
                <a href="/unidades" class="btn btn-secondary btn-lg">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Seleccionamos todos los botones que representan una opción de desplegable
        const botonesSeleccion = document.querySelectorAll('.select-item-btn');
        const todasLasAsignaturas = document.querySelectorAll('.asignatura-option');
        const asignaturaBtnVisible = document.getElementById('dropdownAsignaturas');

        // Asignamos el manejador de eventos a cada botón de opción
        botonesSeleccion.forEach(button => {
            button.addEventListener('click', function() {
                
                // 1. Obtener valores y destinos a partir de los atributos de datos (data-*)
                // Usar data attributes es la forma recomendada en HTML5 para información que JS necesita [1, 2]
                const targetId = this.getAttribute('data-target-id');
                const valorSeleccionado = this.getAttribute('data-value');
                // 🚨 CLAVE: Obtener el texto del botón que se hizo clic para mostrarlo 🚨
                const nombreSeleccionado = this.textContent.trim(); 
                const targetBtnId = this.getAttribute('data-target-btn');
                
                // Referencias a los elementos de destino
                const inputOculto = document.getElementById(targetId);
                const botonVisible = document.getElementById(targetBtnId);

                // 2. Ejecutar la acción de sincronización (Visual y PHP)
                if (botonVisible) {
                    // 🚨 ACTUALIZACIÓN VISUAL: Esto hace que se vea la opción seleccionada 🚨
                    botonVisible.textContent = nombreSeleccionado;
                }
                if (inputOculto) {
                    // Actualización del campo oculto para PHP ($_POST)
                    inputOculto.value = valorSeleccionado; 
                }
                
                // 3. Lógica específica para el desplegable de CURSOS (Filtrado de Asignaturas)
                if (targetId === 'curso_id_hidden') {
                    const cursoId = valorSeleccionado;
                    
                    // Ocultar todas las opciones de asignaturas
                    todasLasAsignaturas.forEach(option => {
                        option.closest('li').style.display = 'none'; 
                    });
                    
                    // Mostrar solo las asignaturas que coinciden usando el selector de atributo
                    const selector = `.asignatura-option[data-curso-id="${cursoId}"]`;
                    const asignaturasFiltradas = document.querySelectorAll(selector);
                    
                    if (asignaturasFiltradas.length > 0) {
                        asignaturasFiltradas.forEach(option => {
                            option.closest('li').style.display = 'block'; 
                        });
                    } else {
                        // Si no hay asignaturas, podría optar por no mostrar nada o un mensaje
                    }

                    // 4. Reiniciar el desplegable de Asignaturas al seleccionar un nuevo curso
                    asignaturaBtnVisible.textContent = 'Seleccionar Asignatura';
                    document.getElementById('asignatura_id_hidden').value = ''; 
                }
            });
        });
    });
</script>