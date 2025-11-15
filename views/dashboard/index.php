<?php
// views/dashboard/index.php
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-tachometer-alt me-2"></i>
            Bienvenido, <?= htmlspecialchars($profesor['nombre']) ?> 
        </h2>
        <p class="text-muted">Aquí gestionas todo tu contenido educativo</p>
    </div>
</div>

<!-- Tarjetas de estadísticas -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-lg border-0">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-book fa-3x me-3"></i>
                <div>
                    <h3 class="mb-0"><?= $stats['total_asignaturas'] ?></h3>
                    <small>Asignaturas creadas</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-lg border-0">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-list-ul fa-3x me-3"></i>
                <div>
                    <h3 class="mb-0">0</h3>
                    <small>Unidades didácticas</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white shadow-lg border-0">
            <div class="card-body d-flex align-items-center">
                <i class="fas fa-tasks fa-3x me-3"></i>
                <div>
                    <h3 class="mb-0">0</h3>
                    <small>Actividades publicadas</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="text-center">
    <h4 class="mb-4 text-muted">¿Qué quieres hacer hoy?</h4>
    <div class="row justify-content-center g-4">
        <div class="col-md-3">
            <a href="/asignatura/crear" class="btn btn-primary btn-lg px-5 py-4 w-100">
                <i class="fas fa-plus-circle fa-2x mb-2 d-block"></i>
                Crear asignatura
            </a>
        </div>
        <div class="col-md-3">
            <a href="/asignaturas" class="btn btn-outline-primary btn-lg px-5 py-4 w-100">
                <i class="fas fa-list fa-2x mb-2 d-block"></i>
                Mis asignaturas
            </a>
        </div>
    </div>
</div>