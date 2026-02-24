<?php

declare(strict_types=1);
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();



require_once __DIR__ . '/../app/core/Env.php';
Env::load(__DIR__ . '/../.env');

require_once __DIR__ . '/../app/config/constants.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/Db.php';
require_once __DIR__ . '/../app/models/Usuario.php';

if (isset($_SESSION['usuario_id'])) {
    // Ya está logueado → enviamos al dashboard
    header('Location: index.php?c=dashboard&a=index');
    exit;
}

$error = null;
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Ingresa usuario y contraseña.';
    } else {
        $user = Usuario::findActiveByUsername($username);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = 'Usuario o contraseña incorrectos.';
        } else {
            // Login exitoso
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int)$user['usuario_id'];
            $_SESSION['rol']        = $user['rol'];
            $_SESSION['username']   = $user['username'];
            $_SESSION['ultima_actividad'] = time();

            // REDIRECCIÓN SEGÚN TIPO DE USUARIO
            if (in_array($user['rol'], ['admin', 'operador'], true)) {
                header('Location: index.php?c=dashboard&a=index');
            } else {
                header('Location: index.php?c=reclamo&a=index');
            }
            exit;
        }
    }
}

// 3. Gestión de errores por URL (ej: cuando Auth lo expulsa por inactividad)
$timeoutError = ($_GET['error'] ?? '') === 'timeout';
if ($timeoutError) {
    $error = 'Su sesión ha expirado por inactividad. Por favor, ingrese de nuevo.';
}

// Assets
$cssBootstrap = asset('adminlte/css/bootstrap.min.css');
$cssAdminLTE  = asset('adminlte/css/adminlte.min.css');
$jsBootstrap  = asset('adminlte/js/bootstrap.bundle.min.js');
$cssIcons     = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso | Galería Comercial</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($cssBootstrap) ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($cssAdminLTE) ?>">
    <link rel="stylesheet" href="<?= $cssIcons ?>">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        body.login-page {
            background: #f1f5f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Roboto, sans-serif;
        }

        .login-box {
            width: 420px;
        }

        .card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .brand-header {
            background: var(--primary-gradient);
            padding: 2.5rem 2rem;
            text-align: center;
            color: white;
        }

        .login-logo b {
            font-size: 1.8rem;
            letter-spacing: -1px;
        }

        .login-card-body {
            padding: 2rem 2.5rem;
        }

        .form-control {
            border-radius: 0.6rem;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            border-radius: 0.6rem;
            padding: 0.75rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .input-group-text {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #94a3b8;
            border-radius: 0 0.6rem 0.6rem 0 !important;
        }
    </style>
</head>

<body class="login-page">

    <div class="login-box">
        <div class="card">
            <div class="brand-header">
                <div class="login-logo mb-0">
                    <i class="bi bi-shield-lock-fill d-block mb-2" style="font-size: 2.5rem; color: #60a5fa;"></i>
                    <b>GALERÍA</b> GESTIÓN
                </div>
                <p class="small text-blue-200 opacity-75 mb-0">Panel Administrativo - Libro Reclamación</p>
            </div>

            <div class="card-body login-card-body">
                <?php if ($error): ?>
                    <div class="alert alert-<?= $timeoutError ? 'warning' : 'danger' ?> small mb-4 py-2" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">USUARIO</label>
                        <div class="input-group">
                            <input type="text" name="username" class="form-control" placeholder="Ej: admin.central" value="<?= htmlspecialchars($username) ?>" autofocus required>
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">CONTRASEÑA</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Iniciar Sesión <i class="bi bi-box-arrow-in-right ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
        <p class="text-center mt-4 text-muted small">
            &copy; <?= date('Y') ?> Galería Comercial - Versión 2.0
        </p>
    </div>

    <script src="<?= htmlspecialchars($jsBootstrap) ?>"></script>
</body>

</html>