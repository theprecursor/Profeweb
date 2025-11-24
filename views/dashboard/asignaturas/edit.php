<h2><i class="fas fa-edit text-primary"></i> Editar Asignatura</h2>

<form method="POST" action="/asignatura/editar/<?= $asignatura['id'] ?>" class="mt-4">
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre_asignatura" value="<?= htmlspecialchars($asignatura['nombre_asignatura']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <input type="text" name="descripcion" value="<?= htmlspecialchars($asignatura['descripcion'] ?? '') ?>" class="form-control">
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

    <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="es_publico" 
                           id="es_publico"
                           checked="<?= htmlspecialchars($asignatura['es_publico']) ?>" 
                           value="<?= htmlspecialchars($asignatura['es_publico']) ?>">
                    <label class="form-check-label fw-bold" for="es_publico">
                        Asignatura pública
                    </label>
                </div>
            </div>
    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="/asignaturas" class="btn btn-secondary">Cancelar</a>
</form>
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