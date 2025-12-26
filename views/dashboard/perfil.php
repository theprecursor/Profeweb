<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mi Perfil Profesional</h1>
        
        <!-- Botón para ver cómo lo ven los demás (Perfil Público) -->
        <a href="/profesor/<?= $usuario['id'] ?>" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-external-link-alt me-2"></i>Ver mi Perfil Público
        </a>
    </div>

    <!-- MODO VISUALIZACIÓN (Por defecto visible) -->
    <div id="view-mode" class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Datos del Profesor</h6>
            <button id="btn-edit" class="btn btn-sm btn-warning text-dark">
                <i class="fas fa-edit me-1"></i> Editar Perfil
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <!-- Avatar con iniciales -->
                    <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center shadow" 
                        style="width: 120px; height: 120px; font-size: 3rem; line-height: 1;">
                        <?= strtoupper(mb_substr($usuario['nombre'], 0, 1, 'UTF-8')) ?>
                    </div>
                </div>
                <div class="col-md-9">
                    <h3 class="mb-1"><?= htmlspecialchars($usuario['nombre']) ?></h3>
                    <p class="text-muted mb-3"><i class="fas fa-envelope me-2"></i><?= htmlspecialchars($usuario['email']) ?></p>
                    
                    <hr>
                    
                    <h5 class="text-dark"><i class="fas fa-book-reader me-2"></i>Biografía</h5>
                    <p class="text-secondary">
                        <?= !empty($usuario['biografia']) ? nl2br(htmlspecialchars($usuario['biografia'])) : '<em>No has añadido una biografía todavía.</em>' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODO EDICIÓN (Por defecto oculto class="d-none") -->
    <form id="edit-mode" action="/dashboard/perfil/update" method="POST" class="card shadow mb-4 d-none">
        <div class="card-header py-3 bg-warning bg-opacity-10">
            <h6 class="m-0 font-weight-bold text-dark">Editando Información</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" 
                           value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="biografia" class="form-label">Biografía / Presentación</label>
                <textarea class="form-control" id="biografia" name="biografia" rows="5" 
                          placeholder="Cuéntale a tus alumnos y sus familias sobre tu experiencia y metodología..."><?= htmlspecialchars($usuario['biografia']) ?></textarea>
                <div class="form-text">Esta información será visible en tu perfil público.</div>
            </div>

            <div class="alert alert-info py-2">
                <i class="fas fa-info-circle me-1"></i> El correo electrónico (<?= $usuario['email'] ?>) no se puede modificar desde aquí por seguridad.
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button" id="btn-cancel" class="btn btn-secondary">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>
<!-- NUEVA SECCIÓN: DATOS DE ACCESO -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-lock me-1"></i> Datos de Acceso y Seguridad</h6>
    </div>
    <div class="card-body">
        
        <!-- Fila EMAIL -->
        <div class="row align-items-center mb-3">
            <div class="col-md-3 fw-bold">Correo Electrónico:</div>
            <div class="col-md-6">
                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($usuario['email']) ?>" readonly>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEmail">
                    <i class="fas fa-exchange-alt"></i> Cambiar
                </button>
            </div>
        </div>

        <!-- Fila CONTRASEÑA -->
        <div class="row align-items-center">
            <div class="col-md-3 fw-bold">Contraseña:</div>
            <div class="col-md-6">
                <!-- Mostramos asteriscos visuales -->
                <input type="password" class="form-control bg-light" value="********" readonly>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalPass">
                    <i class="fas fa-key"></i> Cambiar
                </button>
            </div>
        </div>

    </div>
</div>

<!-- ================= MODALES ================= -->

<!-- Modal Cambiar Email -->
<div class="modal fade" id="modalEmail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="/dashboard/perfil/cambiar-email" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Correo Electrónico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nuevo Correo</label>
                    <input type="email" name="email" class="form-control" required placeholder="nuevo@ejemplo.com">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Cambiar Contraseña -->
<div class="modal fade" id="modalPass" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="/dashboard/perfil/cambiar-pass" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Contraseña Actual (Seguridad) -->
                <div class="mb-3">
                    <label class="form-label">Contraseña Actual</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                
                <hr>

                <!-- Nueva Contraseña con Ojo -->
                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña</label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="new_password" class="form-control" required minlength="8">
                        <button class="btn btn-outline-secondary" type="button" id="btnTogglePass">
                            <i class="fas fa-eye" id="iconEye"></i>
                        </button>
                    </div>
                    <div class="form-text">Mínimo 8 caracteres.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Actualizar Contraseña</button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT PARA VER/OCULTAR CONTRASEÑA -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnToggle = document.getElementById('btnTogglePass');
    const inputPass = document.getElementById('new_password');
    const icon = document.getElementById('iconEye');

    btnToggle.addEventListener('click', function() {
        // Cambiamos el tipo de input
        if (inputPass.type === 'password') {
            inputPass.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash'); // Cambia icono a ojo tachado
        } else {
            inputPass.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye'); // Vuelve a ojo normal
        }
    });
});
</script>

<!-- JAVASCRIPT PARA ALTERNAR MODOS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewMode = document.getElementById('view-mode');
    const editMode = document.getElementById('edit-mode');
    const btnEdit = document.getElementById('btn-edit');
    const btnCancel = document.getElementById('btn-cancel');

    // Al hacer clic en "Editar"
    btnEdit.addEventListener('click', function() {
        viewMode.classList.add('d-none'); // Ocultar vista
        editMode.classList.remove('d-none'); // Mostrar formulario
    });

    // Al hacer clic en "Cancelar"
    btnCancel.addEventListener('click', function() {
        editMode.classList.add('d-none'); // Ocultar formulario
        viewMode.classList.remove('d-none'); // Volver a vista
        // Opcional: Resetear el formulario al cancelar para deshacer cambios no guardados
        editMode.reset();
    });
});
</script>