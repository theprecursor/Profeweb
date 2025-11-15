<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold"><?= htmlspecialchars($profesor['nombre']) ?></h1>
        <p class="lead text-muted">Asignaturas públicas</p>
    </div>

    <?php if (empty($asignaturas)): ?>
        <div class="text-center py-5">
            <i class="fas fa-lock fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Este profesor no tiene asignaturas públicas</h4>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($asignaturas as $a): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm text-center p-4">
                        <i class="fas fa-book fa-3x text-primary mb-3"></i>
                        <h5><?= htmlspecialchars($a['nombre_asignatura']) ?></h5>
                        <?php if ($a['descripcion']): ?>
                            <p class="text-muted small"><?= htmlspecialchars($a['descripcion']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-center mt-5">
        <a href="/" class="btn btn-outline-secondary">Volver al inicio</a>
    </div>
</div>