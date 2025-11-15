<h2><i class="fas fa-edit text-primary"></i> Editar Asignatura</h2>

<form method="POST" action="/asignatura/<?= $asignatura['id'] ?>/editar" class="mt-4">
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($asignatura['nombre']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Curso</label>
        <input type="text" name="curso" value="<?= htmlspecialchars($asignatura['curso'] ?? '') ?>" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Guardar cambios</button>
    <a href="/asignaturas" class="btn btn-secondary">Cancelar</a>
</form>