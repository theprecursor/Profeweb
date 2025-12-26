<div class="container-fluid px-4">
    
    <!-- 1. Encabezado y Botón de Crear -->
    <div class="d-flex justify-content-between align-items-center my-4">
        <div>
            <h1 class="mt-2">Mis Cursos</h1>
            <p class="text-muted">Gestiona los grupos de alumnos (Ej: 1º ESO A, 2º Bachillerato).</p>
        </div>
        <a href="/curso/crear" class="btn btn-success btn-lg shadow-sm">
            <i class="fas fa-plus me-2"></i>Nuevo Curso
        </a>
    </div>

    <!-- 2. Mensajes de Feedback (Éxito/Error) -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- 3. Contenedor de Tarjetas -->
    <!-- 'g-4' añade el espacio necesario entre tarjetas -->
    <div class="row g-4">
        
        <?php if (empty($cursos)): ?>
            <!-- Estado Vacío (Si no hay cursos) -->
            <div class="col-12 text-center py-5">
                <div class="text-muted mb-3">
                    <i class="fas fa-chalkboard-teacher fa-3x"></i>
                </div>
                <h4>No tienes cursos registrados</h4>
                <p>Crea tu primer grupo de alumnos para empezar.</p>
            </div>
        <?php else: ?>
            
            <!-- Bucle PHP para generar las tarjetas -->
            <?php foreach ($cursos as $curso): ?>
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    
                    <!-- INICIO DE LA TARJETA CORREGIDA -->
                    <!-- Se añadió 'd-flex flex-column' para activar Flexbox vertical -->
                    <div class="card h-100 shadow-sm hover-card border-0 d-flex flex-column">
                        
                        <!-- CUERPO (Se expande) -->
                        <!-- Se añadió 'flex-grow-1' para empujar el footer hacia abajo -->
                        <div class="card-body flex-grow-1">
                            
                            <!-- Título del Curso -->
                            <h4 class="card-title text-primary fw-bold mb-3">
                                <?= htmlspecialchars($curso['nombre_curso']) ?>
                            </h4>

                            <!-- Badges / Etiquetas de información -->
                            <div class="mb-3">
                                <?php if(!empty($curso['turno'])): ?>
                                    <span class="badge bg-light text-dark border ms-1">
                                        <i class="far fa-clock me-1"></i> 
                                        <?= htmlspecialchars($curso['turno']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                        </div>
                        <!-- FIN CUERPO -->

                        <!-- FOOTER (Botones) -->
                        <!-- Al estar fuera del div flex-grow-1, siempre se queda al fondo -->
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <div class="d-grid gap-2">
                                <!-- Botón Editar -->
                                <a href="curso/editar/<?= $curso['id'] ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit me-2"></i>Editar
                                </a>
                                
                                <!-- Botón Eliminar (Formulario para seguridad) -->
                                <form action="curso/eliminar/<?= $curso['id'] ?>" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este curso?');" style="display:inline;">
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="fas fa-trash-alt me-2"></i>Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- FIN FOOTER -->

                    </div>
                    <!-- FIN TARJETA -->

                </div>
            <?php endforeach; ?>
            
        <?php endif; ?>
    </div>
</div>

<!-- Estilo extra para la animación hover (opcional, si no lo tienes en tu CSS) -->
<style>
    .hover-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>