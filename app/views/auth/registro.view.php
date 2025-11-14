<?php 
namespace App\Controllers;
?>

<!-- app/Views/auth/registro.view.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro Docente - ProfeWeb</title>

    <!-- Inclusión de Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          crossorigin="anonymous">
    
    <style>
        /* Mismos estilos CSS que el login para coherencia visual */
        :root {
            --profe-blue: var(--bs-primary);
            --profe-green: var(--bs-success);
        }
        body {
            background-color: #f0f2f5; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            margin-top: 5vh; 
            margin-bottom: 5vh;
        }
        .card-accent-green {
            border-left: 5px solid var(--profe-green); 
            border-radius: 0.75rem;
        }
    </style>
</head>
<body>
    <main class="container register-container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <!-- Tarjeta llamativa y profesional, idéntica a la de Login -->
                <div class="card shadow-lg card-accent-green border-0">
                    <div class="card-header text-center bg-primary text-white border-0" style="border-radius: 0.70rem 0.70rem 0 0;">
                        <!-- AZUL: Consistencia en el encabezado -->
                        <h1 class="h4 py-2 mb-0">Registro en ProfeWeb</h1>
                    </div>
                    <div class="card-body p-4">
                        <form action="/registro" method="POST" novalidate>
                            
                            <div class="mb-3">
                                <label for="nombre" class="form-label small text-muted">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Tu nombre profesional">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label small text-muted">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="ejemplo@docente.com">
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label small text-muted">Contraseña (Mínimo 8 caracteres)</label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="8" placeholder="Crea una contraseña segura">
                            </div>

                            <div class="mb-4">
                                <label for="password_confirm" class="form-label small text-muted">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required minlength="8" placeholder="Repite la contraseña">
                            </div>

                            <div class="d-grid gap-2">
                                <!-- Botón principal AZUL -->
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">Crear Cuenta</button>
                            </div>
                        </form>

                        <!-- Enlace de acento VERDE -->
                        <p class="mt-4 text-center small">
                            ¿Ya estás registrado? <a href="<?php echo ROOT_URL ?>/public/login" class="text-success fw-bold">Ir a Iniciar Sesión</a>
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