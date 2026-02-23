<?php
// app/init.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Db.php';
require_once __DIR__ . '/core/View.php';

// Esto es lo que "encuentra" tus controladores
spl_autoload_register(function($class) {
    $file = __DIR__ . '/controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});