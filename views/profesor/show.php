<div class="container py-5">
    
    <!-- 1. PERFIL DEL PROFESOR (Igual que antes) -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-8 text-center">
            <div class="mb-3">
                <div class="avatar-circle bg-primary text-white d-inline-flex align-items-center justify-content-center shadow" style="width: 100px; height: 100px; font-size: 2.5rem; border-radius: 50%;">
                    <?= strtoupper(substr($profesor['nombre'], 0, 1)) . strtoupper(substr($profesor['apellidos'] ?? '', 0, 1)) ?>
                </div>
            </div>
            <h1 class="display-5 fw-bold text-dark mb-2">
                <?= htmlspecialchars($profesor['nombre'] . ' ' . ($profesor['apellidos'] ?? '')) ?>
            </h1>
            <p class="lead text-secondary mx-auto" style="max-width: 700px;">
                <?= nl2br(htmlspecialchars($profesor['biografia'] ?? 'Bienvenido a mi perfil docente.')) ?>
            </p>
        </div>
    </div>

    <!-- 2. BARRA DE FILTROS -->
    <?php if (!empty($estructura_academica)): ?>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="card bg-light border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <select class="form-select" id="filtroCurso">
                                    <option value="">Todos los Cursos</option>
                                    <!-- Se llenará con PHP -->
                                    <?php foreach ($estructura_academica as $curso => $asignaturas): ?>
                                        <option value="<?= htmlspecialchars($curso) ?>"><?= htmlspecialchars($curso) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select class="form-select" id="filtroAsignatura">
                                    <option value="">Todas las Asignaturas</option>
                                    <!-- Recolectamos todas las asignaturas únicas para el filtro -->
                                    <?php 
                                    $todasAsignaturas = [];
                                    foreach ($estructura_academica as $curso => $asigs) {
                                        foreach ($asigs as $a) {
                                            $todasAsignaturas[$a['id']] = $a['nombre_asignatura'];
                                        }
                                    }
                                    // Eliminamos duplicados si una asignatura se llama igual en varios cursos (opcional)
                                    $todasAsignaturas = array_unique($todasAsignaturas);
                                    ?>
                                    <?php foreach ($todasAsignaturas as $idAsig => $nomAsig): ?>
                                        <option value="<?= $idAsig ?>"><?= htmlspecialchars($nomAsig) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-secondary w-100" id="btnLimpiar">
                                    <i class="fas fa-undo me-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- 3. CONTENIDO ACADÉMICO (Jerarquía de Acordeones) -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <?php if (empty($estructura_academica)): ?>
                <div class="alert alert-info text-center shadow-sm">
                    <i class="fas fa-info-circle me-2"></i> Este profesor aún no ha publicado contenido.
                </div>
            <?php else: ?>

                <!-- NIVEL 1: ACORDEÓN DE CURSOS -->
                <div class="accordion" id="accordionPrincipal">
                    <?php $cursoIndex = 0; ?>
                    <?php foreach ($estructura_academica as $nombre_curso => $asignaturas): ?>
                        <?php 
                            $cursoIndex++; 
                            $cursoIdAttr = "curso-" . $cursoIndex; 
                        ?>
                        
                        <!-- Item de Curso -->
                        <div class="accordion-item mb-3 border-0 shadow-sm item-curso" data-nombre-curso="<?= htmlspecialchars($nombre_curso) ?>">
                            <h2 class="accordion-header" id="heading-<?= $cursoIdAttr ?>">
                                <button class="accordion-button collapsed bg-white text-primary fw-bold fs-5" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#collapse-<?= $cursoIdAttr ?>">
                                    <i class="fas fa-graduation-cap me-2"></i> <?= htmlspecialchars($nombre_curso) ?>
                                </button>
                            </h2>
                            <div id="collapse-<?= $cursoIdAttr ?>" class="accordion-collapse collapse" 
                                 data-bs-parent="#accordionPrincipal">
                                <div class="accordion-body bg-light">
                                    
                                    <!-- NIVEL 2: ACORDEÓN DE ASIGNATURAS -->
                                    <div class="accordion" id="subAccordion-<?= $cursoIdAttr ?>">
                                        <?php foreach ($asignaturas as $asig): ?>
                                            <?php $asigIdAttr = "asig-" . $asig['id']; ?>
                                            
                                            <div class="accordion-item mb-2 border item-asignatura" data-id-asignatura="<?= $asig['id'] ?>">
                                                <h2 class="accordion-header" id="heading-<?= $asigIdAttr ?>">
                                                    <button class="accordion-button collapsed" type="button" 
                                                            data-bs-toggle="collapse" data-bs-target="#collapse-<?= $asigIdAttr ?>">
                                                        <span class="fw-bold text-dark"><?= htmlspecialchars($asig['nombre_asignatura']) ?></span>
                                                    </button>
                                                </h2>
                                                <div id="collapse-<?= $asigIdAttr ?>" class="accordion-collapse collapse" 
                                                     data-bs-parent="#subAccordion-<?= $cursoIdAttr ?>">
                                                    <div class="accordion-body p-3">
                                                        <p class="small text-muted mb-3"><?= htmlspecialchars($asig['descripcion']) ?></p>

                                                        <!-- NIVEL 3: ACORDEÓN DE UNIDADES (Ya existente) -->
                                                        <?php if (!empty($asig['unidades'])): ?>
                                                            <div class="accordion" id="accordion-units-<?= $asig['id'] ?>">
                                                                <?php foreach ($asig['unidades'] as $unidad): ?>
                                                                    <?php $unidadIdAttr = "unidad-" . $unidad['id']; ?>
                                                                    
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-<?= $unidadIdAttr ?>">
                                                                            <button class="accordion-button collapsed bg-white text-secondary py-2" type="button" 
                                                                                    data-bs-toggle="collapse" data-bs-target="#collapse-<?= $unidadIdAttr ?>">
                                                                                <small><strong>UNIDAD:</strong> <?= htmlspecialchars($unidad['nombre_unidad']) ?></small>
                                                                            </button>
                                                                        </h2>
                                                                        <div id="collapse-<?= $unidadIdAttr ?>" class="accordion-collapse collapse" 
                                                                             data-bs-parent="#accordion-units-<?= $asig['id'] ?>">
                                                                            <div class="accordion-body p-0">
                                                                                <!-- NIVEL 4: LISTA DE ACTIVIDADES -->
                                                                                <div class="list-group list-group-flush">
                                                                                    <?php if (!empty($unidad['actividades'])): ?>
                                                                                        <?php foreach ($unidad['actividades'] as $act): ?>
                                                                                            <a href="#" class="list-group-item list-group-item-action py-3 btn-ver-actividad"
                                                                                               data-bs-toggle="modal" 
                                                                                               data-bs-target="#modalActividad"
                                                                                               data-titulo="<?= htmlspecialchars($act['nombre_actividad']) ?>"
                                                                                               data-desc="<?= htmlspecialchars($act['descripcion']) ?>"
                                                                                               data-fecha="<?= $act['fecha_entrega'] ? date('d/m/Y', strtotime($act['fecha_entrega'])) : '' ?>"
                                                                                               data-curso="<?= htmlspecialchars($nombre_curso) ?>"
                                                                                               data-asignatura="<?= htmlspecialchars($asig['nombre_asignatura']) ?>"
                                                                                               data-unidad="<?= htmlspecialchars($unidad['nombre_unidad']) ?>"
                                                                                               data-competencias='<?= json_encode($act['competencias']) ?>'
                                                                                               data-criterios='<?= json_encode($act['criterios']) ?>'>
                                                                                                <div class="d-flex w-100 justify-content-between align-items-center">
                                                                                                    <div>
                                                                                                        <i class="far fa-file-alt text-success me-2"></i>
                                                                                                        <?= htmlspecialchars($act['nombre_actividad']) ?>
                                                                                                    </div>
                                                                                                    <?php if($act['fecha_entrega']): ?>
                                                                                                        <span class="badge rounded-pill bg-light text-dark border">
                                                                                                            <i class="far fa-clock me-1"></i> <?= date('d/m', strtotime($act['fecha_entrega'])) ?>
                                                                                                        </span>
                                                                                                    <?php endif; ?>
                                                                                                </div>
                                                                                            </a>
                                                                                        <?php endforeach; ?>
                                                                                    <?php else: ?>
                                                                                        <div class="p-3 text-muted small">Sin actividades públicas.</div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="alert alert-secondary py-2 small mb-0">No hay unidades publicadas.</div>
                                                        <?php endif; ?>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- MODAL DE ACTIVIDAD (Mismo que tenías, no cambia) -->
<div class="modal fade" id="modalActividad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitulo"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4 pb-3 border-bottom">
                    <span class="badge bg-secondary mb-2" id="modalCurso"></span>
                    <span class="badge bg-info text-dark mb-2" id="modalAsignatura"></span>
                    <span class="badge bg-light text-dark border mb-2" id="modalUnidad"></span>
                    <div class="text-end float-end" id="boxFecha">
                        <small class="text-muted fw-bold">Entrega:</small> 
                        <span class="text-danger fw-bold" id="modalFecha"></span>
                    </div>
                </div>
                <div class="mb-4">
                    <h6 class="fw-bold text-dark text-uppercase small ls-1">Descripción</h6>
                    <p class="text-secondary" id="modalDescripcion"></p>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold text-primary mb-3"><i class="fas fa-brain me-2"></i>Competencias</h6>
                                <ul class="list-unstyled mb-0 small" id="listCompetencias"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold text-success mb-3"><i class="fas fa-clipboard-check me-2"></i>Criterios</h6>
                                <ul class="list-unstyled mb-0 small" id="listCriterios"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- JAVASCRIPT: Lógica de Filtros y Modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. LÓGICA DEL FILTRO ---
    const filtroCurso = document.getElementById('filtroCurso');
    const filtroAsignatura = document.getElementById('filtroAsignatura');
    const btnLimpiar = document.getElementById('btnLimpiar');

    function aplicarFiltros() {
        const cursoSeleccionado = filtroCurso.value;
        const asigSeleccionada = filtroAsignatura.value;

        // Recorremos todos los cursos (Nivel 1)
        document.querySelectorAll('.item-curso').forEach(cursoItem => {
            const nombreCurso = cursoItem.getAttribute('data-nombre-curso');
            let cursoVisible = true;

            // Filtro por Nombre de Curso
            if (cursoSeleccionado && nombreCurso !== cursoSeleccionado) {
                cursoVisible = false;
            }

            // Si el curso pasa el primer filtro, verificamos sus asignaturas internas
            let algunaAsigVisible = false;
            const asignaturasItems = cursoItem.querySelectorAll('.item-asignatura');
            
            asignaturasItems.forEach(asigItem => {
                const idAsig = asigItem.getAttribute('data-id-asignatura');
                let asigVisible = true;

                if (asigSeleccionada && idAsig !== asigSeleccionada) {
                    asigVisible = false;
                }

                // Mostrar u ocultar la asignatura específica
                if (asigVisible) {
                    asigItem.style.display = 'block';
                    algunaAsigVisible = true; 
                    // Si filtramos por asignatura, abrimos automáticamente el acordeón
                    if(asigSeleccionada) {
                        const collapseAsig = asigItem.querySelector('.accordion-collapse');
                        if(collapseAsig) new bootstrap.Collapse(collapseAsig, { show: true, toggle: false });
                    }
                } else {
                    asigItem.style.display = 'none';
                }
            });

            // Visibilidad final del bloque Curso
            if (cursoVisible && algunaAsigVisible) {
                cursoItem.style.display = 'block';
                // Si hay filtros activos, desplegamos el curso automáticamente
                if (cursoSeleccionado || asigSeleccionada) {
                    const collapseCurso = cursoItem.querySelector('.accordion-collapse');
                    if(collapseCurso) new bootstrap.Collapse(collapseCurso, { show: true, toggle: false });
                }
            } else {
                cursoItem.style.display = 'none';
            }
        });
    }

    // Eventos
    filtroCurso.addEventListener('change', () => {
        // Al cambiar curso, podríamos resetear asignatura si se desea, o dejar que filtre cruzado
        aplicarFiltros();
    });

    filtroAsignatura.addEventListener('change', aplicarFiltros);

    btnLimpiar.addEventListener('click', () => {
        filtroCurso.value = "";
        filtroAsignatura.value = "";
        
        // Resetear visualización: Mostrar todo y colapsar acordeones
        document.querySelectorAll('.item-curso').forEach(el => {
            el.style.display = 'block';
            // Opcional: Cerrar acordeones al limpiar
            const collapse = el.querySelector('.accordion-collapse');
            if(collapse && collapse.classList.contains('show')) {
                new bootstrap.Collapse(collapse, { hide: true, toggle: false });
            }
        });
        document.querySelectorAll('.item-asignatura').forEach(el => {
            el.style.display = 'block';
            const collapse = el.querySelector('.accordion-collapse');
            if(collapse && collapse.classList.contains('show')) {
                new bootstrap.Collapse(collapse, { hide: true, toggle: false });
            }
        });
    });


    // --- 2. LÓGICA DEL MODAL (Igual que antes) ---
    const modalActividad = document.getElementById('modalActividad');
    if (modalActividad) {
        modalActividad.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            // Datos básicos
            document.getElementById('modalTitulo').textContent = button.getAttribute('data-titulo');
            document.getElementById('modalDescripcion').textContent = button.getAttribute('data-desc') || 'Sin descripción.';
            document.getElementById('modalCurso').textContent = button.getAttribute('data-curso');
            document.getElementById('modalAsignatura').textContent = button.getAttribute('data-asignatura');
            document.getElementById('modalUnidad').textContent = button.getAttribute('data-unidad');
            
            const fecha = button.getAttribute('data-fecha');
            if(fecha) {
                document.getElementById('modalFecha').textContent = fecha;
                document.getElementById('boxFecha').style.display = 'block';
            } else {
                document.getElementById('boxFecha').style.display = 'none';
            }

            // Listas
            const competencias = JSON.parse(button.getAttribute('data-competencias') || '[]');
            const criterios = JSON.parse(button.getAttribute('data-criterios') || '[]');

            const listComp = document.getElementById('listCompetencias');
            listComp.innerHTML = competencias.length ? competencias.map(c => 
                `<li class="mb-2"><i class="fas fa-check text-primary me-2"></i><strong>${c.codigo_competencia}</strong></li>`
            ).join('') : '<li class="text-muted">No especificadas</li>';

            const listCrit = document.getElementById('listCriterios');
            listCrit.innerHTML = criterios.length ? criterios.map(c => 
                `<li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>${c.codigo_criterio}</strong></li>`
            ).join('') : '<li class="text-muted">No especificados</li>';
        });
    }
});
</script>