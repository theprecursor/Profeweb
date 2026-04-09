<!-- views/dashboard/asignaturas/create.php -->

<h2 class="mb-4">
    Nueva Competencia
</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="/competencias/crear">
            
            <!-- Nombre de la asignatura -->
            <div class="mb-3">
                <label for="nombre_competencia" class="form-label fw-bold">
                    Competencia <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="nombre_competencia" 
                       id="nombre_competencia"
                       class="form-control form-control-lg" 
                       placeholder="Competencia" 
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
                        Competencia pública
                    </label>
                </div>
                <div class="form-text text-muted">
                    Si activas esta opción, otros profesores y alumnos podrán ver esta competencia en tu perfil público.
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success btn-lg px-5">
                    Crear Competencia
                </button>
                <a href="/competencias" class="btn btn-secondary btn-lg">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>