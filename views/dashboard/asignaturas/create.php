<!-- views/dashboard/asignaturas/create.php -->

<h2 class="mb-4">
    Nueva Asignatura
</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="/asignatura/crear">
            
            <!-- Nombre de la asignatura -->
            <div class="mb-3">
                <label for="nombre_asignatura" class="form-label fw-bold">
                    Nombre de la asignatura <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="nombre_asignatura" 
                       id="nombre_asignatura"
                       class="form-control form-control-lg" 
                       placeholder="Ej: Matemáticas 1º ESO" 
                       required 
                       autofocus>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label for="descripcion" class="form-label fw-bold">
                    Descripción (opcional)
                </label>
                <textarea name="descripcion" 
                          id="descripcion" 
                          rows="4" 
                          class="form-control" 
                          placeholder="Breve descripción de la asignatura, objetivos, nivel..."></textarea>
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
                        Asignatura pública
                    </label>
                </div>
                <div class="form-text text-muted">
                    Si activas esta opción, otros profesores y alumnos podrán ver esta asignatura en tu perfil público.
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-success btn-lg px-5">
                    Crear asignatura
                </button>
                <a href="/asignaturas" class="btn btn-secondary btn-lg">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>