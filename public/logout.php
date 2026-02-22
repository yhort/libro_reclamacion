<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../app/config/constants.php';

// 1. Capturamos el motivo antes de borrar la sesión si existe en la URL
// Opcional: podrías guardar un mensaje personalizado
$error_type = isset($_GET['error']) ? $_GET['error'] : '';

// 2. Limpia sesión (Tu lógica original que es muy correcta)
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'], $params['secure'], $params['httponly']
    );
}

session_destroy();

// 3. Redirección inteligente
// Si el JS mandó ?error=timeout, lo pasamos al login.php
if ($error_type === 'timeout') {
    header('Location: ' . BASE_URL . '/login.php?error=timeout');
} else {
    header('Location: ' . BASE_URL . '/login.php');
}
exit;