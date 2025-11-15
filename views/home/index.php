<div class="row">
    <div class="col-12 text-center mb-5">
        <h1 class="display-5 fw-bold text-primary">Bienvenido a ProfeWeb</h1>
        <p class="lead text-muted">Explora las asignaturas de nuestros profesores</p>
    </div>
</div>

<?php if (empty($profesores)): ?>
    <div class="alert alert-info text-center">
        <i class="fas fa-info-circle"></i> Aún no hay profesores registrados.
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/register.php" class="alert-link">¡Sé el primero en registrarte!</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($profesores as $prof): ?>
            <div class="col">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0"><?= htmlspecialchars($prof['nombre']) ?></h5>
                                <small class="text-muted"><?= htmlspecialchars($prof['email']) ?></small>
                            </div>
                        </div>
                        
                        <p class="text-muted mt-auto">
                            <i class="fas fa-book"></i> 
                            <strong><?= $prof['total_asignaturas'] ?></strong>
                            <?= $prof['total_asignaturas'] == 1 ? 'asignatura' : 'asignaturas' ?> publicada<?= $prof['total_asignaturas'] != 1 ? 's' : '' ?>
                        </p>

                        <div class="mt-3">
                            <a href="/profesor.php?id=<?= $prof['id'] ?>" class="btn btn-outline-primary w-100">
                                Ver asignaturas → 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>