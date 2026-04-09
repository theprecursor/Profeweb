<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3">Mis Criterios de Evaluación</h2>
    <a href="/criterios/crear" class="btn btn-success">Nuevo Criterio</a>
</div>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<hr class="my-4">

<!-- Listado de Criterios de Evaluación (Visualización) -->
<?php if (empty($criterios)): ?>
    <div class="alert alert-info">Aún no has creado ningun Criterio de Evaluación.</div>
<?php else: ?>
<div class="row g-4">
    <?php foreach ($criterios as $c): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title d-flex justify-content-between align-items-start">
                        <?= htmlspecialchars($c['codigo_criterio']) ?>
                        <?php if ($c['es_publico']): ?>
                            <span class="badge bg-success">Pública</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Privada</span>
                        <?php endif; ?>
                    </h5>                    
                    <div class="mt-3">
                        <a href="/criterios/editar/<?= $c['id'] ?>" class="btn btn-outline-primary btn-sm">Editar</a>
                        <form method="POST" action="/criterios/eliminar/<?= $c['id'] ?>" class="d-inline">
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Eliminar este criterio?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>