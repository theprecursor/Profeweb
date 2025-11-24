<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3">Mis Unidades</h2>
    <a href="/unidades/crear" class="btn btn-success">Nueva unidad</a>
</div>

<!-- Formulario de Filtro (Se envía a UnidadesController@index por POST) -->
<form method="POST" action="/unidades" id="filtro-unidades-form">
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
        
        <button type="submit" class="btn btn-primary">Aplicar Filtro</button>
        <!-- Opcional: Botón para resetear los filtros -->
        <a href="/unidades" class="btn btn-outline-secondary">Limpiar Filtros</a>
        
    </div>
</form>

<hr class="my-4">

<!-- Listado de Unidades Didácticas (Visualización) -->
<?php if (empty($unidades)): ?>
    <div class="alert alert-info">Aún no has creado ninguna Unidad Didáctica o no hay resultados para este filtro.</div>
<?php else: ?>
<div class="row g-4">
    <?php foreach ($cursos as $c): ?>
        <!-- Este título solo se muestra si hay unidades del curso a continuación -->
        <?php 
        // Verificamos si hay alguna unidad para este curso para no mostrar el título vacío
        $has_units = false;
        foreach ($unidades as $u) {
            if ($u['curso_id'] === $c['id']) {
                $has_units = true;
                break;
            }
        }
        ?>

        <?php if ($has_units): ?>
        <div class="col-12 mt-5">
            <h4 class="card-title d-flex justify-content-between align-items-start border-bottom pb-2">
                Curso <?= htmlspecialchars($c['nombre_curso']) ?>
            </h4>
        </div>
        <?php endif; ?>

        <?php foreach ($unidades as $u): ?>
            <!-- CORRECCIÓN CLAVE: La condición debe verificar el curso_id de la UNIDAD individual ($u) -->
            <?php if ($u['curso_id'] === $c['id']): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title d-flex justify-content-between align-items-start">
                            Unidad <?= htmlspecialchars($u['orden']) ?>
                        </h4>
                        <h5 class="card-title d-flex justify-content-between align-items-start">
                            <?= htmlspecialchars($u['nombre_unidad']) ?>
                            <?php if ($u['es_publico']): ?>
                                <span class="badge bg-success">Pública</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Privada</span>
                            <?php endif; ?>
                        </h5>
                        <?php if ($u['descripcion']): ?> 
                            <p class="text-muted small"><?= nl2br(htmlspecialchars($u['descripcion'])) ?></p>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <a href="/unidades/editar/<?= $u['id'] ?>" class="btn btn-outline-primary btn-sm">Editar</a>
                            <form method="POST" action="/unidades/eliminar/<?= $u['id'] ?>" class="d-inline">
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar esta unidad y todo su contenido?')">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
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