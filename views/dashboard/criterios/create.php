<!-- views/dashboard/criterios/create.php -->

<h2 class="mb-4">
    Nuevo Criterio de Evaluación
</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="/criterios/crear">
            
            <!-- Nombre del criterio -->
            <div class="mb-3">
                <label for="codigo_criterio" class="form-label fw-bold">
                    Criterio de Evaluación <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="codigo_criterio" 
                       id="codigo_criterio"
                       class="form-control form-control-lg" 
                       placeholder="Criterio de Evalucación" 
                       required 
                       autofocus>
            </div>

            <!-- Visibilidad pública -->
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="es_publico" 
                           id="es_publico" 
                           value="1">
                    <label class="form-check-label fw-bold" for="es_publico">
                        Criterio público
                    </label>
                </div>
                <div class="form-text text-muted">
                    Si activas esta opción, otros profesores y alumnos podrán ver este criterio en tu perfil público.
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success btn-lg px-5">
                    Crear Criterio
                </button>
                <a href="/criterios" class="btn btn-secondary btn-lg">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>