<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3">Editar Competencia: <?= htmlspecialchars($competencias['codigo_competencia'] ?? '') ?></h2>
    <a href="/competencias" class="btn btn-secondary">Volver al listado</a>
</div>

<!-- El formulario se envía por POST al Controlador UnidadesController@update -->
<form method="POST" action="/competencias/editar/<?= $competencias['id'] ?>">

    <div class="mb-3">
        <label for="nombre_unidad" class="form-label">Nombre de la Competencia</label>
        <input type="text" class="form-control" id="nombre_competencia" name="nombre_competencia" 
               value="<?= htmlspecialchars($competencias['codigo_competencia'] ?? '') ?>" required>
    </div>

    <!-- Checkbox Público/Privado -->
    <div class="mb-4">
        <div class="form-check form-switch">
            <input class="form-check-input select-competencias-btn" 
                    type="checkbox" 
                    name="es_publico" 
                    id="es_publico"
                    checked="<?= htmlspecialchars($competencias['es_publico']) ?>" 
                    value="<?= htmlspecialchars($competencias['es_publico']) ?>">
            <label class="form-check-label fw-bold" for="es_publico">
                Competencia pública
            </label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="/competencias" class="btn btn-outline-secondary">Cancelar</a>
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
        const opcionSeleccionada = event.target.closest('.select-competencias-btn');
        
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