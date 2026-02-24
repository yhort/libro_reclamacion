<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/core/Env.php';
Env::load(__DIR__ . '/../.env');

require_once __DIR__ . '/../app/config/constants.php';

// tu logout igual...
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'], $params['secure'], $params['httponly']
    );
}
session_destroy();

$error_type = $_GET['error'] ?? '';
$qs = $error_type === 'timeout' ? '?error=timeout' : '';
header('Location: ' . BASE_URL . '/login.php' . $qs);
exit;