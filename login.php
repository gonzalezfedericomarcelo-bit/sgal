<?php
// sgal/login.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$error_msg = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_credentials':
            $error_msg = 'Usuario o contraseña incorrectos.';
            break;
        case 'session_expired':
            $error_msg = 'Tu sesión ha expirado. Por favor, ingresa de nuevo.';
            break;
        case 'logged_out':
            $error_msg = 'Has cerrado sesión exitosamente.';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGAL - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h1 class="h3 text-center mb-1 text-primary-emphasis">S G A L</h1>
                <p class="text-center text-muted mb-4">Iniciar Sesión</p>

                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_msg; ?>
                    </div>
                <?php endif; ?>

                <form action="procesar_login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" style="background-color: #1a237e;">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>