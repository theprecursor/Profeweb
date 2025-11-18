<h2><i class="fas fa-edit text-primary"></i> Editar Asignatura</h2>

<form method="POST" action="/asignatura/editar/<?= $asignatura['id'] ?>" class="mt-4">
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre_asignatura" value="<?= htmlspecialchars($asignatura['nombre_asignatura']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <input type="text" name="descripcion" value="<?= htmlspecialchars($asignatura['descripcion'] ?? '') ?>" class="form-control">
    </div>
    <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="es_publico" 
                           id="es_publico"
                           checked="<?= htmlspecialchars($asignatura['es_publico']) ?>" 
                           value="<?= htmlspecialchars($asignatura['es_publico']) ?>">
                    <label class="form-check-label fw-bold" for="es_publico">
                        Asignatura pública
                    </label>
                </div>
            </div>
    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="/asignaturas" class="btn btn-secondary">Cancelar</a>
</form>