<?php
// views/dashboard/layout.php
// Layout privado del profesor con menú lateral
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - ProfeWeb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #343a40; }
        .sidebar .nav-link { color: #adb5bd; border-radius: 0.375rem; margin: 0.25rem 1rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: #495057; color: white; }
        .main-content { padding: 2rem; }
        
    </style>
</head>
<body>

<div class="d-flex">
    <!-- MENÚ LATERAL -->
    <div class="sidebar text-white p-4" style="width: 280px;">
        <div class="text-center mb-5">
            <h4 class="fw-bold"><i class="fas fa-graduation-cap text-primary"></i> ProfeWeb</h4>
            <hr class="bg-light">
            <p class="mb-1"><?= htmlspecialchars($_SESSION['user_name']) ?></p>
            <small class="text-muted"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></small>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link <?= ($current_page ?? '') === 'dashboard' ? 'active' : '' ?>" href="/dashboard">
                <i class="fas fa-tachometer-alt me-2"></i> Inicio
            </a>
            <a class="nav-link <?= ($current_page ?? '') === 'asignaturas' ? 'active' : '' ?>" href="/asignaturas">
                <i class="fas fa-book me-2"></i> Mis Asignaturas
            </a>
            <hr class="bg-light my-4">
            <a class="nav-link text-danger" href="/logout">
                <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
            </a>
        </nav>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="flex-grow-1 main-content">
        <div class="bg-white rounded shadow-sm p-4">
            <?= $content ?? '' ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>