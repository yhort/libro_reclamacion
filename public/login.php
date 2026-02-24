<?php
//declare(strict_types=1);
ini_set('display_errors', 1);
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

        /* Mejor 100dvh para móviles modernos + fallback */
        body.login-page {
            background: #f1f5f9;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Roboto, sans-serif;
            padding: 24px 12px;
            /* ✅ espacio en móviles */
        }

        /* ✅ ancho fluido y máximo */
        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, .15);
            overflow: hidden;
        }

        .brand-header {
            background: var(--primary-gradient);
            padding: 2rem 1.5rem;
            text-align: center;
            color: #fff;
        }

        .login-logo b {
            font-size: 1.8rem;
            letter-spacing: -1px;
        }

        .login-card-body {
            padding: 1.5rem;
        }

        .form-control {
            border-radius: .6rem;
            padding: .75rem 1rem;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            border-radius: .6rem;
            padding: .85rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .input-group-text {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #94a3b8;
            border-radius: 0 .6rem .6rem 0 !important;
        }

        /* ✅ Ajustes en pantallas pequeñas */
        @media (max-width: 576px) {
            body.login-page {
                padding: 18px 10px;
            }

            .brand-header {
                padding: 1.5rem 1.25rem;
            }

            .login-logo b {
                font-size: 1.55rem;
            }

            .login-card-body {
                padding: 1.25rem;
            }
        }

        /* ✅ Ajustes en pantallas grandes */
        @media (min-width: 992px) {
            .brand-header {
                padding: 2.2rem 2rem;
            }

            .login-card-body {
                padding: 2rem 2.5rem;
            }
        }

        .brand-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }

        .brand-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(96, 165, 250, .18);
            border: 1px solid rgba(96, 165, 250, .25);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .18);
        }

        .brand-icon i {
            font-size: 1.7rem;
            color: #93c5fd;
        }

        .brand-text {
            text-align: left;
            line-height: 1.2;
        }

        .brand-title {
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex-wrap: wrap;
        }

        .brand-name {
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .brand-sub {
            font-size: 1.15rem;
            font-weight: 700;
            opacity: .9;
        }

        .brand-dot {
            opacity: .65;
        }

        .brand-desc {
            margin-top: 6px;
            font-size: .9rem;
            opacity: .8;
        }

        /* Móvil: centra texto y ajusta tamaños */
        @media (max-width:576px) {
            .brand-wrap {
                flex-direction: column;
                gap: 10px;
            }

            .brand-text {
                text-align: center;
            }

            .brand-name {
                font-size: 1.35rem;
            }

            .brand-sub {
                font-size: 1.05rem;
            }

            .brand-desc {
                font-size: .85rem;
            }
        }
    </style>
</head>

<body class="login-page">

    <div class="login-box">
        <div class="card">
            <div class="brand-header">
                <div class="brand-wrap">
                    <div class="brand-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>

                    <div class="brand-text">
                        <div class="brand-title">
                            <span class="brand-name">Reclama</span>
                            <span class="brand-dot">•</span>
                            <span class="brand-sub">Tykesoft</span>
                        </div>
                        <div class="brand-desc">Panel Administrativo · Libro de Reclamaciones</div>
                    </div>
                </div>
            </div>

            <div class="card-body login-card-body">
                <?php if ($error): ?>
                    <div class="alert alert-<?= $timeoutError ? 'warning' : 'danger' ?> small mb-4 py-2" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post" autocomplete="off" class="mt-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">USUARIO</label>
                        <div class="input-group">
                            <input type="text" name="username" inputmode="text" class="form-control" placeholder="Ej: admin.central" value="<?= htmlspecialchars($username) ?>" autofocus required>
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
            &copy; <?= date('Y') ?> Libro Reclamacion - TykeSoft
        </p>
    </div>

    <script src="<?= htmlspecialchars($jsBootstrap) ?>"></script>
</body>

</html>