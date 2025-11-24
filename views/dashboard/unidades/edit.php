<?php
// Asumimos que el controlador pasa $unidad, $cursos, y $asignaturas.
// Usamos $u para la unidad para simplificar el código.
$u = $unidades; 
$curso_id_seleccionado = $u['curso_id'] ?? null;
$asignatura_id_seleccionada = $u['asignatura_id'] ?? null;
$es_publico_seleccionado = (bool)($u['es_publico'] ?? 0);

// =========================================================================
// LÓGICA PHP PARA BUSCAR LOS NOMBRES (Preselección visual)
// =========================================================================

// 1. Buscar el nombre del curso actual
$curso_nombre_actual = 'Seleccionar Curso';
if ($curso_id_seleccionado && isset($cursos)) {
    foreach ($cursos as $c) {
        if ((int)($c['id'] ?? 0) === (int)$curso_id_seleccionado) {
            $curso_nombre_actual = $c['nombre_curso'];
            break;
        }
    }
}

// 2. Buscar el nombre de la asignatura actual
$asignatura_nombre_actual = 'Seleccionar Asignatura';
if ($asignatura_id_seleccionada && isset($asignaturas)) {
    foreach ($asignaturas as $a) {
        if ((int)($a['id'] ?? 0) === (int)$asignatura_id_seleccionada) {
            $asignatura_nombre_actual = $a['nombre_asignatura'];
            break;
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3">Editar Unidad: <?= htmlspecialchars($u['nombre_unidad'] ?? '') ?></h2>
    <a href="/unidades" class="btn btn-secondary">Volver al listado</a>
</div>

<!-- El formulario se envía por POST al Controlador UnidadesController@update -->
<form method="POST" action="/unidades/editar/<?= $u['id'] ?>">

    <div class="mb-3">
        <label for="nombre_unidad" class="form-label">Nombre de la Unidad</label>
        <input type="text" class="form-control" id="nombre_unidad" name="nombre_unidad" 
               value="<?= htmlspecialchars($u['nombre_unidad'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?= htmlspecialchars($u['descripcion'] ?? '') ?></textarea>
    </div>

    <!-- ======================================================= -->
    <!-- DESPLEGABLES DE CURSO Y ASIGNATURA -->
    <!-- ======================================================= -->
    <div class="mb-3 row">
        
        <!-- Desplegable Curso -->
        <div class="col-md-6">
            <label class="form-label">Curso</label>
            <!-- 🚨 Campo oculto inicializado con el valor de la Unidad 🚨 -->
            <input type="hidden" name="curso_id" id="curso_id_hidden" 
                   value="<?= htmlspecialchars($curso_id_seleccionado) ?>"> 

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" 
                        id="dropdownCursos" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- 🚨 Muestra el nombre preseleccionado 🚨 -->
                    <?= htmlspecialchars($curso_nombre_actual) ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownCursos" id="curso-menu">
                    <?php if (isset($cursos)): ?>
                    <?php foreach ($cursos as $c): ?>
                    <li>
                        <button class="dropdown-item select-item-btn" 
                                type="button" 
                                data-target-id="curso_id_hidden" 
                                data-target-btn="dropdownCursos"
                                data-value="<?= $c['id'] ?>">
                            <?= htmlspecialchars($c['nombre_curso']) ?>
                        </button>
                    </li> 
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Desplegable Asignatura -->
        <div class="col-md-6">
            <label class="form-label">Asignatura</label>
            <!-- 🚨 Campo oculto inicializado con el valor de la Unidad 🚨 -->
            <input type="hidden" name="asignatura_id" id="asignatura_id_hidden" 
                   value="<?= htmlspecialchars($asignatura_id_seleccionada) ?>">

            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" 
                        id="dropdownAsignaturas" data-bs-toggle="dropdown" aria-expanded="false">
                     <!-- 🚨 Muestra el nombre preseleccionado 🚨 -->
                    <?= htmlspecialchars($asignatura_nombre_actual) ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownAsignaturas" id="asignatura-menu">
                    <?php if (isset($asignaturas) && is_array($asignaturas)): ?>
                    <?php foreach ($asignaturas as $a): ?>
                    <li>
                        <button class="dropdown-item select-item-btn asignatura-option" 
                                type="button" 
                                data-target-id="asignatura_id_hidden" 
                                data-target-btn="dropdownAsignaturas"
                                data-value="<?= $a['id'] ?>"
                                data-curso-id="<?= $a['curso_id'] ?>" 
                                style="display: none;"> <!-- Ocultas por defecto por JS -->
                            <?= htmlspecialchars($a['nombre_asignatura']) ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Checkbox Público/Privado -->
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="es_publico" 
               value="1" id="es_publico" <?= $es_publico_seleccionado ? 'checked' : '' ?>>
        <label class="form-check-label" for="es_publico">
            Unidad pública
        </label>
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="/unidades" class="btn btn-outline-secondary">Cancelar</a>
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Elementos clave para la interactividad
    const asignaturaBtnVisible = document.getElementById('dropdownAsignaturas');
    const todasLasAsignaturasOptions = document.querySelectorAll('.asignatura-option');

    // =======================================================
    // FUNCIÓN CENTRAL DE FILTRADO (REUTILIZABLE)
    // =======================================================
    function filterAsignaturas(cursoId) {
        
        // A) Ocultar TODAS las opciones de asignaturas
        todasLasAsignaturasOptions.forEach(option => {
            const liElement = option.closest('li');
            if (liElement) {
                liElement.style.display = 'none'; 
            }
        });
        
        // B) Mostrar solo las asignaturas coincidentes si el ID es válido
        if (cursoId && cursoId !== 'undefined' && cursoId !== '') {
            const selector = `.asignatura-option[data-curso-id="${cursoId}"]`;
            const asignaturasFiltradas = document.querySelectorAll(selector);
            
            // 🚨 DEBUG opcional, puede eliminar estas líneas si funciona:
            console.log("Inicializando/Filtrando con Curso ID:", cursoId);
            console.log("Asignaturas encontradas:", asignaturasFiltradas.length);
            // 🚨 Fin de DEBUG

            if (asignaturasFiltradas.length > 0) {
                asignaturasFiltradas.forEach(option => {
                    const liElement = option.closest('li');
                    if (liElement) {
                        liElement.style.display = 'list-item'; // Usamos 'list-item' para forzar la visibilidad del LI
                    }
                });
            }
        }
    }


    // =======================================================
    // 🚨 INICIALIZACIÓN AUTOMÁTICA AL CARGAR LA PÁGINA (SOLUCIÓN)
    // =======================================================
    // Lee el ID preseleccionado por PHP e inicializa el filtro.
    const cursoIdInicial = document.getElementById('curso_id_hidden').value;
    if (cursoIdInicial) {
        filterAsignaturas(cursoIdInicial);
    }


    // =======================================================
    // MANEJADOR DE EVENTOS (PARA NUEVAS SELECCIONES MANUALES)
    // =======================================================
    document.querySelectorAll('.select-item-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            
            event.preventDefault(); 

            // 1. Obtención de datos y referencias
            const targetId = this.getAttribute('data-target-id');
            const valorSeleccionado = this.getAttribute('data-value');
            const nombreSeleccionado = this.textContent.trim(); 
            const targetBtnId = this.getAttribute('data-target-btn');
            
            const inputOculto = document.getElementById(targetId);
            const botonVisible = document.getElementById(targetBtnId);

            // 2. Actualización visual y de datos
            if (botonVisible) {
                botonVisible.textContent = nombreSeleccionado; // Actualiza el texto del botón visible
            }
            if (inputOculto) {
                inputOculto.value = valorSeleccionado; 
            }
            
            // 3. Lógica de Filtrado en Cascada (Solo si se selecciona un CURSO)
            if (targetId === 'curso_id_hidden') {
                const cursoId = valorSeleccionado;
                
                // Reiniciar Asignaturas (cuando se selecciona un nuevo curso)
                asignaturaBtnVisible.textContent = 'Seleccionar Asignatura';
                document.getElementById('asignatura_id_hidden').value = ''; 
                
                filterAsignaturas(cursoId);
                
                // Opcional: Abrir el desplegable de Asignaturas (mejora UX)
                const asignaturaButton = document.getElementById('dropdownAsignaturas');
                if (asignaturaButton) {
                    // Simula un clic para que el plugin Dropdown de Bootstrap lo abra [1, 2]
                    asignaturaButton.click(); 
                }
            }
        });
    });
});
</script>