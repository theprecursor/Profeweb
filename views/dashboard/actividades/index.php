<?php
// Recuperar filtros de la URL (GET) para mantener la selección tras recargar
// Nota: Usamos $_GET para leer lo que el controlador recibió
$curso_id_actual = $_GET['curso_id'] ?? '';
$asignatura_id_actual = $_GET['asignatura_id'] ?? '';
$unidad_id_actual = $_GET['unidad_id'] ?? '';

// Textos por defecto
$texto_curso = 'Seleccionar Curso';
$texto_asignatura = 'Seleccionar Asignatura';
$texto_unidad = 'Seleccionar Unidad';

// Lógica visual PHP: Poner el nombre correcto en el botón si hay un ID seleccionado
if ($curso_id_actual && !empty($cursos)) {
    foreach ($cursos as $c) {
        if ($c['id'] == $curso_id_actual) { $texto_curso = $c['nombre_curso']; break; }
    }
}
if ($asignatura_id_actual && !empty($asignaturas)) {
    foreach ($asignaturas as $a) {
        if ($a['id'] == $asignatura_id_actual) { $texto_asignatura = $a['nombre_asignatura']; break; }
    }
}
if ($unidad_id_actual && !empty($unidades_filtro)) {
    foreach ($unidades_filtro as $u) {
        if ($u['id'] == $unidad_id_actual) { $texto_unidad = $u['nombre_unidad']; break; }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Mis Actividades</h1>
    <a href="/actividades/crear" class="btn btn-success">
        <i class="fas fa-plus"></i> Nueva Actividad
    </a>
</div>

<!-- ======================================================= -->
<!-- BARRA DE FILTROS (FORMULARIO GET) -->
<!-- ======================================================= -->
<div class="card mb-4">
    <div class="card-body">
        <!-- IMPORTANTE: method="GET" action="/actividades" -->
        <form method="GET" action="/actividades" id="formFiltros" class="row g-3">
            
            <!-- Inputs ocultos que guardan los IDs reales -->
            <input type="hidden" name="curso_id" id="curso_id_filter" value="<?= htmlspecialchars($curso_id_actual) ?>">
            <input type="hidden" name="asignatura_id" id="asignatura_id_filter" value="<?= htmlspecialchars($asignatura_id_actual) ?>">
            <input type="hidden" name="unidad_id" id="unidad_id_filter" value="<?= htmlspecialchars($unidad_id_actual) ?>">
            
            <!-- 1. FILTRO CURSO -->
            <div class="col-md-3">
                <div class="dropdown w-100">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                            id="btnCursoFilter" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= htmlspecialchars($texto_curso) ?>
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><button class="dropdown-item select-filter-btn" type="button" 
                                    data-target="curso_id_filter" data-target-btn="btnCursoFilter" data-value="">Todos los cursos</button></li>
                        <?php if (!empty($cursos)): ?>
                            <?php foreach ($cursos as $c): ?>
                                <li>
                                    <button class="dropdown-item select-filter-btn" type="button" 
                                            data-target="curso_id_filter" 
                                            data-target-btn="btnCursoFilter"
                                            data-value="<?= $c['id'] ?>">
                                        <?= htmlspecialchars($c['nombre_curso']) ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- 2. FILTRO ASIGNATURA -->
            <div class="col-md-3">
                <div class="dropdown w-100">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                            id="btnAsignaturaFilter" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= htmlspecialchars($texto_asignatura) ?>
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><button class="dropdown-item select-filter-btn" type="button" 
                                    data-target="asignatura_id_filter" data-target-btn="btnAsignaturaFilter" data-value="">Todas las asignaturas</button></li>
                        <?php if (!empty($asignaturas)): ?>
                            <?php foreach ($asignaturas as $a): ?>
                                <li>
                                    <button class="dropdown-item select-filter-btn asignatura-option" type="button" 
                                            data-target="asignatura_id_filter" 
                                            data-target-btn="btnAsignaturaFilter"
                                            data-value="<?= $a['id'] ?>"
                                            data-curso-id="<?= $a['curso_id'] ?>">
                                        <?= htmlspecialchars($a['nombre_asignatura']) ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- 3. FILTRO UNIDAD -->
            <div class="col-md-3">
                <div class="dropdown w-100">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                            id="btnUnidadFilter" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= htmlspecialchars($texto_unidad) ?>
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><button class="dropdown-item select-filter-btn" type="button" 
                                    data-target="unidad_id_filter" data-target-btn="btnUnidadFilter" data-value="">Todas las unidades</button></li>
                        <?php if (!empty($unidades_filtro)): ?>
                            <?php foreach ($unidades_filtro as $u): ?>
                                <li>
                                    <button class="dropdown-item select-filter-btn unidad-option" type="button" 
                                            data-target="unidad_id_filter" 
                                            data-target-btn="btnUnidadFilter"
                                            data-value="<?= $u['id'] ?>"
                                            data-asignatura-id="<?= $u['asignatura_id'] ?>">
                                        <?= htmlspecialchars($u['nombre_unidad']) ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- BOTONES -->
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filtrar</button>
                <a href="/actividades" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE RESULTADOS -->
<?php if (empty($actividades)): ?>
    <div class="alert alert-info text-center">No hay actividades con estos filtros.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Actividad</th>
                    <th>Curso</th>
                    <th>Asignatura</th>
                    <th>Unidad</th>
                    <th>Entrega</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($actividades as $act): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($act['nombre_actividad']) ?></strong></td>
                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($act['nombre_curso'] ?? '-') ?></span></td>
                    <td><?= htmlspecialchars($act['nombre_asignatura'] ?? '-') ?></td>
                    <td class="small text-muted"><?= htmlspecialchars($act['nombre_unidad'] ?? '-') ?></td>
                    <td>
                        <?php 
                        if(!empty($act['fecha_entrega'])) {
                            echo (new DateTime($act['fecha_entrega']))->format('d/m/Y');
                        }
                        ?>
                    </td>
                    <td>
                        <?= ($act['es_publico']) ? '<span class="badge bg-success">Pública</span>' : '<span class="badge bg-secondary">Borrador</span>' ?>
                    </td>
                    <td class="text-end">
                        <a href="/actividad/editar/<?= $act['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="/actividades/eliminar/<?= $act['id'] ?>" class="d-inline">
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar esta actividad?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- JAVASCRIPT PARA LA CASCADA -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Referencias a las opciones
    const asignaturasOptions = document.querySelectorAll('.asignatura-option');
    const unidadesOptions = document.querySelectorAll('.unidad-option');

    // Referencias a botones visuales
    const btnAsignatura = document.getElementById('btnAsignaturaFilter');
    const btnUnidad = document.getElementById('btnUnidadFilter');

    // Referencias a inputs ocultos
    const inputAsignatura = document.getElementById('asignatura_id_filter');
    const inputUnidad = document.getElementById('unidad_id_filter');

    // Función para filtrar asignaturas por curso
    function filtrarAsignaturas(cursoId) {
        asignaturasOptions.forEach(op => {
            const li = op.closest('li');
            if (!cursoId || op.getAttribute('data-curso-id') === cursoId) {
                li.style.display = 'list-item';
            } else {
                li.style.display = 'none';
            }
        });
    }

    // Función para filtrar unidades por asignatura
    function filtrarUnidades(asignaturaId) {
        unidadesOptions.forEach(op => {
            const li = op.closest('li');
            if (asignaturaId && op.getAttribute('data-asignatura-id') === asignaturaId) {
                li.style.display = 'list-item';
            } else {
                li.style.display = 'none';
            }
        });
    }

    // Inicialización al cargar (si ya hay filtros en la URL)
    const cursoInicial = document.getElementById('curso_id_filter').value;
    const asignaturaInicial = document.getElementById('asignatura_id_filter').value;

    if (cursoInicial) filtrarAsignaturas(cursoInicial);
    
    if (asignaturaInicial) {
        filtrarUnidades(asignaturaInicial);
    } else {
        // Si no hay asignatura, ocultamos unidades para evitar errores
        filtrarUnidades(null);
    }

    // Eventos de Click en los desplegables
    document.querySelectorAll('.select-filter-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // Evitar comportamientos extraños

            const targetId = this.getAttribute('data-target');
            const value = this.getAttribute('data-value');
            const label = this.textContent;
            const targetBtnId = this.getAttribute('data-target-btn');

            // 1. Actualizar el input oculto (Fundamental para el formulario)
            document.getElementById(targetId).value = value;

            // 2. Actualizar el botón visual
            if (targetBtnId) {
                document.getElementById(targetBtnId).textContent = label;
            }

            // 3. Lógica de Cascada
            if (targetId === 'curso_id_filter') {
                // Se cambió el curso: filtrar asignaturas y resetear lo demás
                filtrarAsignaturas(value);
                
                inputAsignatura.value = '';
                btnAsignatura.textContent = 'Seleccionar Asignatura';
                
                inputUnidad.value = '';
                btnUnidad.textContent = 'Seleccionar Unidad';
                filtrarUnidades(null); // Ocultar unidades
            }

            if (targetId === 'asignatura_id_filter') {
                // Se cambió la asignatura: filtrar unidades y resetear unidad
                filtrarUnidades(value);
                
                inputUnidad.value = '';
                btnUnidad.textContent = 'Seleccionar Unidad';
                
                // Auto-abrir siguiente nivel (opcional, mejora UX)
                if(value) btnUnidad.click();
            }
        });
    });
});
</script>