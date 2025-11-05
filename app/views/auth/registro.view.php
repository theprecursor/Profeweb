<!-- app/Views/auth/registro.view.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Docente - ProfeWeb</title>
    <!-- Incluir Bootstrap 5 para el diseño responsive [3, 31] -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .register-container {
            max-width: 450px;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 register-container">
            <h2 class="text-center mb-4">Registro Docente ProfeWeb</h2>
            <div class="card p-4">

                <?php 
                // Mostrar errores si existen (pasados desde el Controlador)
                if (!empty($errors)): 
                ?>
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <!-- Aseguramos la seguridad de salida (XSS) [11, 29] -->
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Formulario: method="POST" y acción que apunta al Controlador Frontal /registro -->
                <form action="<?= ROOT_URL ?>/registro" method="POST">
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               value="<?= isset($nombre) ? htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') : '' ?>" 
                               required placeholder="Tu nombre completo">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : '' ?>" 
                               required placeholder="ejemplo@docente.com">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <!-- Usamos minlength para validación de cliente (UX) [33, 34] -->
                        <input type="password" class="form-control" id="password" name="password" required 
                               minlength="8" placeholder="Mínimo 8 caracteres">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required 
                               minlength="8" placeholder="Repite la contraseña">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                </form>
            </div>
            <p class="text-center mt-3">
                ¿Ya tienes cuenta? <a href="<?= ROOT_URL ?>/login">Iniciar Sesión</a>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>