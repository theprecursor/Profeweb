<div class="container-fluid px-4">
    
    <!-- Encabezado de Bienvenida -->
    <div class="row my-4">
        <div class="col-12">
            <h1 class="display-6 fw-bold text-primary">
                Hola, <?= htmlspecialchars($profesor['nombre']) ?>
            </h1>
            <p class="text-muted lead">
                Bienvenido a tu panel de control. Tienes <strong><?= $stats['asignaturas'] ?></strong> asignaturas activas.
            </p>
        </div>
    </div>

    <!-- SECCIÓN 1: Gestión Académica -->
    <h5 class="mb-3 text-secondary border-bottom pb-2">
        <i class="fas fa-university me-2"></i>Organización Académica
    </h5>
    
    <div class="row g-4 mb-5">
        <!-- Tarjeta CURSOS -->
        <div class="col-xl-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 border-start border-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <div class="text-uppercase fw-bold text-primary small mb-1">Cursos / Grupos</div>
                            <div class="h1 mb-0 fw-bold"><?= $stats['cursos'] ?></div>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-chalkboard-teacher fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="cursos" class="btn btn-outline-primary flex-grow-1">
                            Ver Cursos
                        </a>
                        <a href="cursos/crear" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta ASIGNATURAS -->
        <div class="col-xl-6 col-md-6">
            <div class="card shadow-sm border-0 h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <div class="text-uppercase fw-bold text-info small mb-1">Asignaturas</div>
                            <div class="h1 mb-0 fw-bold"><?= $stats['asignaturas'] ?></div>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-book fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="asignaturas" class="btn btn-outline-info flex-grow-1 text-dark">
                            Ver Asignaturas
                        </a>
                        <a href="asignatura/crear" class="btn btn-info text-white">
                            <i class="fas fa-plus"></i> Crear
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN 2: Elementos Curriculares -->
    <h5 class="mb-3 text-secondary border-bottom pb-2">
        <i class="fas fa-layer-group me-2"></i>Diseño Curricular
    </h5>

    <div class="row g-4">
        <!-- Tarjeta UNIDADES -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="card-title fw-bold text-secondary">Unidades Didácticas</h5>
                        <span class="badge bg-secondary rounded-pill fs-6"><?= $stats['unidades'] ?></span>
                    </div>
                    <p class="text-muted small mb-4">Estructura tus temas y contenidos.</p>
                    <div class="mt-auto d-grid gap-2">
                        <a href="unidades" class="btn btn-outline-secondary btn-sm">Gestionar</a>
                        <a href="unidades/crear" class="btn btn-secondary btn-sm">Nueva Unidad</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta COMPETENCIAS -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="card-title fw-bold text-warning">Competencias</h5>
                        <span class="badge bg-warning text-dark rounded-pill fs-6"><?= $stats['competencias'] ?></span>
                    </div>
                    <p class="text-muted small mb-4">Habilidades clave a desarrollar.</p>
                    <div class="mt-auto d-grid gap-2">
                        <a href="competencias" class="btn btn-outline-warning btn-sm text-dark">Gestionar</a>
                        <a href="competencias/crear" class="btn btn-warning btn-sm text-white">Nueva Competencia</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta CRITERIOS -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="card-title fw-bold text-danger">Criterios Evaluación</h5>
                        <span class="badge bg-danger rounded-pill fs-6"><?= $stats['criterios'] ?></span>
                    </div>
                    <p class="text-muted small mb-4">Estándares para evaluar el aprendizaje.</p>
                    <div class="mt-auto d-grid gap-2">
                        <a href="criterios" class="btn btn-outline-danger btn-sm">Gestionar</a>
                        <a href="criterios/crear" class="btn btn-danger btn-sm">Nuevo Criterio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>