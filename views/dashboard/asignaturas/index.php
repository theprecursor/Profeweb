<h2 class="mb-4">Mis Asignaturas</h2>

<a href="/asignatura/crear" class="btn btn-success mb-4">
    Nueva asignatura
</a>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (empty($asignaturas)): ?>
    <div class="text-center py-5">
        <h4>Aún no has creado ninguna asignatura</h4>
        <a href="/asignatura/crear" class="btn btn-primary mt-3">Crear mi primera asignatura</a>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($asignaturas as $a): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between align-items-start">
                            <?= htmlspecialchars($a['nombre_asignatura']) ?>
                            <?php if ($a['es_publico']): ?>
                                <span class="badge bg-success">Pública</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Privada</span>
                            <?php endif; ?>
                        </h5>
                        
                        <?php if ($a['descripcion']): ?>
                            <p class="text-muted small"><?= nl2br(htmlspecialchars($a['descripcion'])) ?></p>
                        <?php endif; ?>

                        <div class="mt-3">
                            <a href="/asignatura/<?= $a['id'] ?>/editar" class="btn btn-outline-primary btn-sm">
                                Editar
                            </a>
                            <form method="POST" action="/asignatura/<?= $a['id'] ?>/eliminar" class="d-inline">
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('¿Eliminar esta asignatura y todo su contenido?')">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>