<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nueva Actividad</h1>
    <a href="/actividades" class="btn btn-secondary">Volver</a>
</div>

<form method="POST" action="/actividades/crear">

    <!-- ======================================================= -->
    <!-- 1. DATOS BÁSICOS Y UBICACIÓN (Curso -> Asignatura -> Unidad) -->
    <!-- ======================================================= -->
    <div class="card mb-4">
        <div class="card-header">Datos Generales</div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="nombre_actividad" class="form-label">Nombre de la Actividad <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre_actividad" name="nombre_actividad" required placeholder="Ej: Trabajo Práctico N°1">
                </div>
                <div class="col-md-4">
                    <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                    <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega">
                </div>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
            </div>

            <div class="row">
                <!-- Curso -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Curso <span class="text-danger">*</span></label>
                    <input type="hidden" name="curso_id" id="curso_id_hidden" required>
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                                id="btnCurso" data-bs-toggle="dropdown" aria-expanded="false">
                            Seleccionar Curso
                        </button>
                        <ul class="dropdown-menu w-100">
                            <?php if (!empty($cursos)): ?>
                                <?php foreach ($cursos as $c): ?>
                                    <li><button class="dropdown-item select-item-btn" type="button" 
                                        data-target-id="curso_id_hidden" 
                                        data-target-btn="btnCurso" 
                                        data-type="curso"
                                        data-value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre_curso']) ?></button></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Asignatura -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Asignatura <span class="text-danger">*</span></label>
                    <input type="hidden" name="asignatura_id" id="asignatura_id_hidden" required>
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                                id="btnAsignatura" data-bs-toggle="dropdown" aria-expanded="false">
                            Seleccionar Asignatura
                        </button>
                        <ul class="dropdown-menu w-100">
                            <?php if (!empty($asignaturas)): ?>
                                <?php foreach ($asignaturas as $a): ?>
                                    <!-- Se mantiene data-curso-id solo para filtrar este dropdown, no las tablas -->
                                    <li class="asignatura-option" data-curso-id="<?= $a['curso_id'] ?>" style="display:none;">
                                        <button class="dropdown-item select-item-btn" type="button" 
                                            data-target-id="asignatura_id_hidden" 
                                            data-target-btn="btnAsignatura" 
                                            data-type="asignatura"
                                            data-value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre_asignatura']) ?></button>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Unidad -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Unidad Didáctica <span class="text-danger">*</span></label>
                    <input type="hidden" name="unidad_id" id="unidad_id_hidden" required>
                    <div class="dropdown w-100">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" 
                                id="btnUnidad" data-bs-toggle="dropdown" aria-expanded="false">
                            Seleccionar Unidad
                        </button>
                        <ul class="dropdown-menu w-100">
                            <?php if (!empty($unidades)): ?>
                                <?php foreach ($unidades as $u): ?>
                                    <li class="unidad-option" data-asignatura-id="<?= $u['asignatura_id'] ?>" style="display:none;">
                                        <button class="dropdown-item select-item-btn" type="button" 
                                            data-target-id="unidad_id_hidden" 
                                            data-target-btn="btnUnidad" 
                                            data-type="unidad"
                                            data-value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre_unidad']) ?></button>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- 2. VINCULACIÓN CURRICULAR (TABLAS SIN FILTROS) -->
    <!-- ======================================================= -->
    <div class="row">
        
        <!-- TABLA COMPETENCIAS -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light"><strong>Competencias asociadas</strong></div>
                <div class="card-body p-0 table-responsive" style="max-height: 300px;">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"><i class="fas fa-check"></i></th>
                                <th>Competencias</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($competencias)): ?>
                                <?php foreach ($competencias as $comp): ?>
                                    <tr>
                                        <td class="text-center">
                                            <!-- AQUÍ ESTÁ LA CLAVE: value tiene el ID -->
                                            <input class="form-check-input" type="checkbox" 
                                                name="competencias[]" 
                                                value="<?= $comp['id'] ?>">
                                        </td>
                                        <td><?= htmlspecialchars($comp['codigo_competencia']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center">No hay competencias.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TABLA CRITERIOS -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light"><strong>Criterios de Evaluación</strong></div>
                <div class="card-body p-0 table-responsive" style="max-height: 300px;">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"><i class="fas fa-check"></i></th>
                                <th>Criterios de Evaluación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($criterios_evaluacion)): ?>
                                <?php foreach ($criterios_evaluacion as $crit): ?>
                                    <tr>
                                        <td class="text-center">
                                            <!-- AQUÍ ESTÁ LA CLAVE: value tiene el ID -->
                                            <input class="form-check-input" type="checkbox" 
                                                name="criterios[]" 
                                                value="<?= $crit['id'] ?>">
                                        </td>
                                        <td><?= htmlspecialchars($crit['codigo_criterio']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center">No hay criterios.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- Checkbox Público/Privado -->
    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="es_publico" value="1" id="es_publico" checked>
        <label class="form-check-label" for="es_publico">
            Actividad pública (visible para alumnos)
        </label>
    </div>

    <!-- Botones -->
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-lg">Guardar Actividad</button>
        <a href="/actividades" class="btn btn-outline-secondary btn-lg">Cancelar</a>
    </div>

</form>

<!-- ======================================================= -->
<!-- JAVASCRIPT: Solo Cascada de Ubicación (Curso -> Asig -> Unidad) -->
<!-- ======================================================= -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Referencias para la cascada de ubicación
    const asignaturasOps = document.querySelectorAll('.asignatura-option');
    const unidadesOps = document.querySelectorAll('.unidad-option');
    
    const btnAsignatura = document.getElementById('btnAsignatura');
    const btnUnidad = document.getElementById('btnUnidad');
    const inputAsig = document.getElementById('asignatura_id_hidden');
    const inputUnidad = document.getElementById('unidad_id_hidden');

    // Función A: Filtrar Asignaturas cuando se elige Curso
    function filtrarAsignaturas(cursoId) {
        asignaturasOps.forEach(li => {
            if(li.getAttribute('data-curso-id') === cursoId) {
                li.style.display = 'block';
            } else {
                li.style.display = 'none';
            }
        });
    }

    // Función B: Filtrar Unidades cuando se elige Asignatura
    // NOTA: Ya NO filtramos las tablas de competencias/criterios
    function filtrarUnidades(asignaturaId) {
        let hayUnidades = false;
        unidadesOps.forEach(li => {
            if(li.getAttribute('data-asignatura-id') === asignaturaId) {
                li.style.display = 'block';
                hayUnidades = true;
            } else {
                li.style.display = 'none';
            }
        });
    }

    // Manejador de clics en los dropdowns
    document.querySelectorAll('.select-item-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.getAttribute('data-type');
            const val = this.getAttribute('data-value');
            const label = this.textContent.trim();
            const targetInput = document.getElementById(this.getAttribute('data-target-id'));
            const targetBtn = document.getElementById(this.getAttribute('data-target-btn'));

            // Actualizar valor y texto del botón
            targetInput.value = val;
            targetBtn.textContent = label;

            // Lógica Cascada
            if(type === 'curso') {
                // Resetear Asignatura y Unidad
                inputAsig.value = '';
                btnAsignatura.textContent = 'Seleccionar Asignatura';
                inputUnidad.value = '';
                btnUnidad.textContent = 'Seleccionar Unidad';
                
                // Ocultar unidades temporalmente
                filtrarUnidades(null);

                // Filtrar asignaturas disponibles
                filtrarAsignaturas(val);
                
                // Auto-abrir siguiente
                btnAsignatura.click();
            }

            if(type === 'asignatura') {
                // Resetear Unidad
                inputUnidad.value = '';
                btnUnidad.textContent = 'Seleccionar Unidad';

                // Filtrar Unidades
                filtrarUnidades(val);

                // Auto-abrir siguiente
                btnUnidad.click();
            }
        });
    });
});
</script>