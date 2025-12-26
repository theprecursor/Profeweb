<div class="container-fluid px-4">
    <!-- 1. Encabezado y Acciones -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center my-4 gap-3">
        <div>
            <h1 class="mt-2">Mis Asignaturas</h1>
            <p class="text-muted">Gestiona tus materias y contenidos curriculares.</p>
        </div>
        
        <div class="d-flex gap-2 align-items-center">
            <!-- FORMULARIO DE FILTRO -->
            <form action="<?= ROOT_URL ?>/asignaturas" method="GET" class="d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-filter text-muted"></i>
                    </span>
                    <select name="curso_id" class="form-select border-start-0 ps-0" onchange="this.form.submit()" style="min-width: 200px;">
                        <option value="">Todos los cursos</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= $curso['id'] ?>" <?= (isset($filtro_curso) && $filtro_curso == $curso['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Si hay filtro activo, mostramos botón de limpiar -->
                <?php if (!empty($filtro_curso)): ?>
                    <a href="/asignaturas" class="btn btn-outline-secondary ms-2" title="Limpiar filtro">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </form>

            <!-- Botón Crear -->
            <a href="/asignatura/crear" class="btn btn-success shadow-sm text-nowrap">
                <i class="fas fa-plus me-2"></i>Nueva Asignatura
            </a>
        </div>
    </div>

    <!-- 2. Mensajes de Feedback -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- 3. Contenedor de Tarjetas -->
    <div class="row g-4">
        
        <?php if (empty($asignaturas)): ?>
            <!-- Estado Vacío Inteligente -->
            <div class="col-12 text-center py-5">
                <?php if (!empty($filtro_curso)): ?>
                    <!-- Caso 1: No hay asignaturas para ESTE filtro -->
                    <div class="text-muted mb-3"><i class="fas fa-search fa-3x opacity-50"></i></div>
                    <h4>No se encontraron asignaturas en este curso</h4>
                    <p>Prueba seleccionando otro curso o limpia los filtros.</p>
                    <a href="/asignaturas" class="btn btn-outline-primary mt-2">Ver todas</a>
                <?php else: ?>
                    <!-- Caso 2: No hay asignaturas en absoluto -->
                    <h4 class="mt-4 text-muted">Aún no tienes asignaturas creadas</h4>
                    <p>Comienza creando tu primer curso y asignatura.</p>
                    <a href="/asignatura/crear" class="btn btn-outline-primary mt-2">Crear ahora</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            
            <!-- Bucle de Asignaturas -->
            <?php foreach ($asignaturas as $asignatura): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 shadow-sm border-0 hover-card d-flex flex-column">
                        <!-- Header de la tarjeta -->
                        <div class="card-header bg-transparent border-0 pt-3 d-flex justify-content-between">
                            <!-- Badge de Curso (Nuevo) -->
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-chalkboard me-1"></i> 
                                <?= !empty($asignatura['nombre_curso']) ? htmlspecialchars($asignatura['nombre_curso']) : 'Sin curso' ?>
                            </span>
                        </div>

                        <!-- Cuerpo -->
                        <div class="card-body flex-grow-1">
                            <h4 class="card-title text-primary fw-bold mb-2">
                                <?= htmlspecialchars($asignatura['nombre_asignatura']) ?>
                            </h4>
                            
                            <div class="mb-3">
                                <span class="badge rounded-pill <?= $asignatura['es_publico'] ? 'bg-info text-dark' : 'bg-secondary' ?>">
                                    <?= $asignatura['es_publico'] ? 'Público' : 'Privado' ?>
                                </span>
                            </div>

                            <p class="card-text text-muted small">
                                <?= !empty($asignatura['descripcion']) 
                                    ? htmlspecialchars($asignatura['descripcion'])
                                    : 'Sin descripción.' ?>
                            </p>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0 pb-3">
                                <div class="d-grid gap-2">
                                    <!-- Botón Editar -->
                                    <a href="asignatura/editar/<?= $asignatura['curso_id'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-2"></i>Editar
                                    </a>
                                    
                                    <!-- Botón Eliminar (Formulario para seguridad) -->
                                    <form action="asignatura/eliminar/<?= $asignatura['curso_id'] ?>" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este curso?');" style="display:inline;">
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="fas fa-trash-alt me-2"></i>Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>