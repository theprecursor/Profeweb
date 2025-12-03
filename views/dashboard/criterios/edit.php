<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3">Editar Competencia: <?= htmlspecialchars($criterios['codigo_criterio'] ?? '') ?></h2>
    <a href="/unidades" class="btn btn-secondary">Volver al listado</a>
</div>

<!-- El formulario se envía por POST al Controlador UnidadesController@update -->
<form method="POST" action="/criterios/editar/<?= $criterios['id'] ?>">

    <div class="mb-3">
        <label for="criterios" class="form-label">Criterio de Evaluiación</label>
        <input type="text" class="form-control" id="codigo_criterio" name="codigo_criterio" 
               value="<?= htmlspecialchars($criterios['codigo_criterio'] ?? '') ?>" required>
    </div>

    <!-- Checkbox Público/Privado -->
    <div class="form-check mb-4">
        <input class="form-check-input select-criterios-btn" type="checkbox" name="es_publico" 
               value="1" id="es_publico" <?= $criterios['es_publico'] ?> checked=<?= $criterios['es_publico'] ?>>
        <label class="form-check-label" for="es_publico">
            Criterio público
        </label>
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="/criterios" class="btn btn-outline-secondary">Cancelar</a>
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
        const opcionSeleccionada = event.target.closest('.select-criterios-btn');
        
        if (opcionSeleccionada) {
            // Obtener los valores del curso usando los atributos de datos
            const id = opcionSeleccionada.getAttribute('data-curso-id');
            const nombre = opcionSeleccionada.getAttribute('data-curso-nombre');

            // 1. RREGLO VISUAL: Mostrar la opción seleccionada en el botón
            botonVisible.textContent = $criterios['es_publico'];
            botonVisible.value = $criterios['es_publico'];
            botonVisible.checked = $criterios['es_publico'];
            // 2. ARREGLO PARA PHP: Asignar el ID al campo oculto que se enviará por POST
            inputOculto.value = id;
        }
    });
});
</script>