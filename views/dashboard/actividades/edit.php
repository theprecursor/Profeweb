<?php
// Pre-cálculo de nombres para los botones de los dropdowns
$nombre_curso_actual = 'Seleccionar Curso';
$nombre_asig_actual = 'Seleccionar Asignatura';
$nombre_unidad_actual = 'Seleccionar Unidad';

// Buscamos los nombres correspondientes a los IDs guardados
if(!empty($cursos)) {
    foreach($cursos as $c) {
        if($c['id'] == $actividad['curso_id']) $nombre_curso_actual = $c['nombre_curso'];
    }
}
if(!empty($asignaturas)) {
    foreach($asignaturas as $a) {
        if($a['id'] == $actividad['asignatura_id']) $nombre_asig_actual = $a['nombre_asignatura'];
    }
}
if(!empty($unidades)) {
    foreach($unidades as $u) {
        if($u['id'] == $actividad['unidad_id']) $nombre_unidad_actual = $u['nombre_unidad'];
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Editar Actividad</h1>
    <a href="/actividades" class="btn btn-secondary">Volver</a>
</div>

<!-- OJO: La acción apunta a la ruta de actualización con el ID -->
<form method="POST" action="/actividades/editar/<?= $actividad['id'] ?>">

    <!-- 1. DATOS BÁSICOS -->
    <div class="card mb-4">
        <div class="card-header">Datos Generales</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label">Nombre de la Actividad <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nombre_actividad" 
                           value="<?= htmlspecialchars($actividad['nombre_actividad']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Entrega</label>
                    <input type="date" class="form-control" name="fecha_entrega" 
                           value="<?= $actividad['fecha_entrega'] ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="2"><?= htmlspecialchars($actividad['descripcion']) ?></textarea>
            </div>

            <div class="row">
                <!-- Curso -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Curso <span class="text-danger">*</span></label>
                    <input type="hidden" name="curso_id" id="curso_id_hidden" value="<?= $actividad['curso_id'] ?>" required>
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                                id="btnCurso" data-bs-toggle="dropdown">
                            <?= htmlspecialchars($nombre_curso_actual) ?>
                        </button>
                        <ul class="dropdown-menu w-100">
                            <?php foreach ($cursos as $c): ?>
                                <li><button class="dropdown-item select-item-btn" type="button" 
                                    data-target-id="curso_id_hidden" 
                                    data-target-btn="btnCurso" 
                                    data-type="curso"
                                    data-value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre_curso']) ?></button></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Asignatura -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Asignatura <span class="text-danger">*</span></label>
                    <input type="hidden" name="asignatura_id" id="asignatura_id_hidden" value="<?= $actividad['asignatura_id'] ?>" required>
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                                id="btnAsignatura" data-bs-toggle="dropdown">
                             <?= htmlspecialchars($nombre_asig_actual) ?>
                        </button>
                        <ul class="dropdown-menu w-100">
                            <?php foreach ($asignaturas as $a): ?>
                                <li class="asignatura-option" data-curso-id="<?= $a['curso_id'] ?>">
                                    <button class="dropdown-item select-item-btn" type="button" 
                                        data-target-id="asignatura_id_hidden" 
                                        data-target-btn="btnAsignatura" 
                                        data-type="asignatura"
                                        data-value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre_asignatura']) ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Unidad -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Unidad Didáctica <span class="text-danger">*</span></label>
                    <input type="hidden" name="unidad_id" id="unidad_id_hidden" value="<?= $actividad['unidad_id'] ?>" required>
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                                id="btnUnidad" data-bs-toggle="dropdown">
                            <?= htmlspecialchars($nombre_unidad_actual) ?>
                        </button>
                        <ul class="dropdown-menu w-100">
                            <?php foreach ($unidades as $u): ?>
                                <li class="unidad-option" data-asignatura-id="<?= $u['asignatura_id'] ?>">
                                    <button class="dropdown-item select-item-btn" type="button" 
                                        data-target-id="unidad_id_hidden" 
                                        data-target-btn="btnUnidad" 
                                        data-type="unidad"
                                        data-value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre_unidad']) ?></button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. VINCULACIÓN CURRICULAR (TABLAS CON CHECKBOXES PRE-MARCADOS) -->
    <div class="row">
        <!-- Competencias -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light fw-bold">Competencias a evaluar</div>
                <div class="card-body p-0 table-responsive" style="max-height: 300px;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="text-center" style="width: 50px;"><i class="fas fa-check"></i></th>
                                <th>Código</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($competencias)): ?>
                                <?php foreach ($competencias as $comp): ?>
                                    <?php 
                                        // Verificamos si está en el array de seleccionados
                                        $checked = in_array($comp['id'], $competencias_seleccionadas) ? 'checked' : ''; 
                                    ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <input class="form-check-input" type="checkbox" 
                                               name="competencias[]" 
                                               value="<?= $comp['id'] ?>" <?= $checked ?>>
                                    </td>
                                    <td class="align-middle"><?= htmlspecialchars($comp['codigo_competencia']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">No hay competencias disponibles.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Criterios -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light fw-bold">Criterios de Evaluación</div>
                <div class="card-body p-0 table-responsive" style="max-height: 300px;">
                    <table class="table table-hover mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="text-center" style="width: 50px;"><i class="fas fa-check"></i></th>
                                <th>Código</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($criterios_evaluacion)): ?>
                                <?php foreach ($criterios_evaluacion as $crit): ?>
                                    <?php 
                                        // Verificamos si está seleccionado
                                        $checked = in_array($crit['id'], $criterios_seleccionados) ? 'checked' : ''; 
                                    ?>
                                <tr>
                                    <td class="text-center align-middle">
                                        <input class="form-check-input" type="checkbox" 
                                               name="criterios[]" 
                                               value="<?= $crit['id'] ?>" <?= $checked ?>>
                                    </td>
                                    <td class="align-middle"><?= htmlspecialchars($crit['codigo_criterio']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted">No hay criterios disponibles.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="es_publico" value="1" id="es_publico" <?= $actividad['es_publico'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="es_publico">
            Actividad pública (visible para alumnos)
        </label>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-lg">Guardar Cambios</button>
        <a href="/actividades" class="btn btn-outline-secondary btn-lg">Cancelar</a>
    </div>

</form>

<!-- Mismo script que en create.php para la cascada de selects -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const asignaturasOps = document.querySelectorAll('.asignatura-option');
    const unidadesOps = document.querySelectorAll('.unidad-option');
    const btnAsignatura = document.getElementById('btnAsignatura');
    const btnUnidad = document.getElementById('btnUnidad');
    const inputAsig = document.getElementById('asignatura_id_hidden');
    const inputUnidad = document.getElementById('unidad_id_hidden');

    // Funciones de filtrado (igual que en create)
    function filtrarAsignaturas(cursoId) {
        asignaturasOps.forEach(li => {
            if(li.getAttribute('data-curso-id') == cursoId) {
                li.style.display = 'block';
            } else {
                li.style.display = 'none';
            }
        });
    }

    function filtrarUnidades(asignaturaId) {
        unidadesOps.forEach(li => {
            if(li.getAttribute('data-asignatura-id') == asignaturaId) {
                li.style.display = 'block';
            } else {
                li.style.display = 'none';
            }
        });
    }

    // Inicialización: Asegurar que los desplegables muestren las opciones correctas al cargar
    // basado en lo que ya tiene la actividad guardada
    const cursoInicial = document.getElementById('curso_id_hidden').value;
    const asigInicial = document.getElementById('asignatura_id_hidden').value;
    
    if(cursoInicial) filtrarAsignaturas(cursoInicial);
    if(asigInicial) filtrarUnidades(asigInicial);

    // Manejador de clics (igual que en create)
    document.querySelectorAll('.select-item-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.getAttribute('data-type');
            const val = this.getAttribute('data-value');
            const label = this.textContent.trim();
            const targetInput = document.getElementById(this.getAttribute('data-target-id'));
            const targetBtn = document.getElementById(this.getAttribute('data-target-btn'));

            targetInput.value = val;
            targetBtn.textContent = label;

            if(type === 'curso') {
                inputAsig.value = '';
                btnAsignatura.textContent = 'Seleccionar Asignatura';
                inputUnidad.value = '';
                btnUnidad.textContent = 'Seleccionar Unidad';
                filtrarUnidades(null);
                filtrarAsignaturas(val);
                btnAsignatura.click();
            }

            if(type === 'asignatura') {
                inputUnidad.value = '';
                btnUnidad.textContent = 'Seleccionar Unidad';
                filtrarUnidades(val);
                btnUnidad.click();
            }
        });
    });
});
</script>