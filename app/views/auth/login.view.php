<?php 
namespace App\Controllers;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - ProfeWeb</title>

    <!-- Inclusión de Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          crossorigin="anonymous">

    <style>
        /* Definición de variables para colores si se necesitara personalizarlos fuera de Bootstrap */
        :root {
            --profe-blue: var(--bs-primary);
            --profe-green: var(--bs-success);
        }
        body {
            background-color: #f0f2f5; /* Fondo más suave que el gris por defecto */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            margin-top: 15vh; /* Centrado más alto */
        }
        .card-accent-green {
            /* Borde izquierdo sutil para integrar el color de crecimiento/educación */
            border-left: 5px solid var(--profe-green); 
            border-radius: 0.75rem; /* Bordes más redondeados para un look moderno */
        }
        /* ELIMINACIÓN DE ESTILO DE ERROR ROJO: Se confía en el estilo por defecto de Bootstrap */
    </style>
</head>
<body>
    <main class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <!-- Tarjeta llamativa y profesional -->
                <div class="card shadow-lg card-accent-green border-0">
                    <div class="card-header text-center bg-primary text-white border-0" style="border-radius: 0.70rem 0.70rem 0 0;">
                        <!-- Azul: Fiabilidad/Tecnología -->
                        <h1 class="h4 py-2 mb-0">ProfeWeb - Acceso Docente</h1>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?= ROOT_URL ?>/public/login" method="POST" novalidate> <!-- Añadir novalidate si se usa validación JS posterior -->
                            
                            <div class="mb-3">
                                <label for="email" class="form-label small text-muted">Correo Electrónico</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" required placeholder="ejemplo@docente.com">
                                <!-- Aquí aparecerían los mensajes de error de Bootstrap -->
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label small text-muted">Contraseña</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="••••••••">
                            </div>

                            <div class="d-grid gap-2">
                                <!-- Botón principal AZUL -->
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">Iniciar Sesión</button>
                            </div>
                        </form>
                        
                        <!-- Enlace de acento VERDE -->
                        <p class="mt-4 text-center small">
                            ¿Aún no tienes cuenta? <a href="<?php echo ROOT_URL ?>/public/registro" class="text-success fw-bold">Regístrate ahora</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
            crossorigin="anonymous"></script>
</body>
</html>
