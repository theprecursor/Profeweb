<div class="container-fluid px-4">
    <!-- Encabezado y Migas de pan -->
    <h1 class="mt-4">Editar Curso</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="cursos">Cursos</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Modificar Datos del Curso
        </div>
        <div class="card-body">
            <!-- El formulario envía los datos a la ruta de actualización -->
            <form action="/curso/editar/<?= $curso['id'] ?>" method="POST">
                
                <!-- Campo: Nombre del Curso -->
                <div class="mb-3">
                    <label for="nombre_curso" class="form-label">Nombre del Curso *</label>
                    <input type="text" 
                           class="form-control" 
                           id="nombre_curso" 
                           name="nombre_curso" 
                           value="<?= htmlspecialchars($curso['nombre_curso']) ?>" 
                           required 
                           autofocus>
                    <div class="form-text">Ej: 1º ESO A, Matemáticas II.</div>
                </div>

                <!-- Campo: Es Público (Checkbox) -->
                <div class="mb-3 form-check">
                    <!-- El valor 1 se envía si está marcado. Si no, no se envía nada (el controlador debe manejarlo) -->
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="es_publico" 
                           name="es_publico" 
                           value="1" 
                           <?= $curso['es_publico'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="es_publico">
                        <strong>Hacer público este curso</strong>
                    </label>
                    <p class="form-text text-muted">
                        Si activas esta opción, el curso y sus asignaturas públicas serán visibles en tu perfil público de profesor.
                    </p>
                </div>

                <!-- Botones de Acción -->
                <div class="mt-4 mb-0 d-flex justify-content-end gap-2">
                    <a href="cursos" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>

            </form>
        </div>
    </div>
</div>