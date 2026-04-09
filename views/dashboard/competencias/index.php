<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3">Mis Competencias</h2>
    <a href="/competencias/crear" class="btn btn-success">Nueva Competencia</a>
</div>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<hr class="my-4">

<!-- Listado de Unidades Didácticas (Visualización) -->
<?php if (empty($competencias)): ?>
    <div class="alert alert-info">Aún no has creado ninguna Competencia.</div>
<?php else: ?>
<div class="row g-4">
        <?php foreach ($competencias as $u): ?>
            <!-- CORRECCIÓN CLAVE: La condición debe verificar el curso_id de la UNIDAD individual ($u) -->
            <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-between align-items-start">
                        Competencia:
                    </h4>
                    <h5 class="card-title d-flex justify-content-between align-items-start">
                        <?= htmlspecialchars($u['codigo_competencia']) ?>
                        <?php if ($u['es_publico']): ?>
                            <span class="badge bg-success">Pública</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Privada</span>
                        <?php endif; ?>
                    </h5>
                    
                    <div class="mt-3">
                        <a href="/competencias/editar/<?= $u['id'] ?>" class="btn btn-outline-primary btn-sm">Editar</a>
                        <form method="POST" action="/competencias/eliminar/<?= $u['id'] ?>" class="d-inline">
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar esta unidad y todo su contenido?')">Eliminar</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>
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