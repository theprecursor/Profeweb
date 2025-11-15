<?php
// views/home/index.php
// Listado público de profesores (página principal)
?>

<div class="text-center mb-5">
    <h1 class="display-5 fw-bold text-primary">
        <i class="fas fa-graduation-cap me-2"></i> Bienvenido a ProfeWeb
    </h1>
    <p class="lead text-muted">
        Explora las asignaturas y recursos de nuestros profesores
    </p>
</div>

<?php if (empty($profesores ?? [])): ?>
    <!-- No hay profesores aún -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-chalkboard-teacher fa-5x text-secondary opacity-25"></i>
        </div>
        <h3 class="text-muted">Aún no hay profesores registrados</h3>
        <p class="text-muted">
            <?php if (!isset($_SESSION['user_id'])): ?>
                ¡Sé el primero en crear tu espacio docente!
                <br><br>
                <a href="/register" class="btn btn-success btn-lg px-5">
                    <i class="fas fa-user-plus me-2"></i> Regístrate como profesor
                </a>
            <?php else: ?>
                Ya puedes empezar a crear tus asignaturas desde tu <a href="/dashboard">Dashboard</a>
            <?php endif; ?>
        </p>
    </div>

<?php else: ?>
    <!-- Listado de profesores -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($profesores as $prof): ?>
            <div class="col">
                <div class="card h-100 shadow-sm hover-shadow border-0">
                    <div class="card-body d-flex flex-column text-center p-4">
                        <!-- Avatar -->
                        <div class="mb-3">
                            <div class="bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center"
                                 style="width: 90px; height: 90px; font-size: 2.5rem;">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>

                        <!-- Nombre y email -->
                        <h5 class="card-title mb-1"><?= htmlspecialchars($prof['nombre']) ?></h5>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-envelope me-1"></i>
                            <?= htmlspecialchars($prof['email']) ?>
                        </p>

                        <!-- Contador de asignaturas -->
                        <div class="mb-3">
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-book me-1"></i>
                                <?= $prof['total_asignaturas'] ?>
                                <?= $prof['total_asignaturas'] == 1 ? 'asignatura' : 'asignaturas' ?>
                            </span>
                        </div>

                        <!-- Botón ver perfil -->
                        <div class="mt-auto">
                            <a href="/profesor/<?= $prof['id'] ?>" class="btn btn-outline-primary w-100">
                                Ver asignaturas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Hover bonito (opcional) -->
<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}
</style>