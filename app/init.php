<?php
// app/init.php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Db.php';
require_once __DIR__ . '/core/View.php';
require_once __DIR__ . '/core/Auth.php'; // ← AGREGA ESTA LÍNEA

// Autoload para controladores
spl_autoload_register(function($class) {
    $file = __DIR__ . '/controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});