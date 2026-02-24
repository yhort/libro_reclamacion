<?php

declare(strict_types=1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/init.php';
require_once __DIR__ . '/../app/config/mail.php';

$c = $_GET['c'] ?? 'reclamo'; // Por defecto: público
$a = $_GET['a'] ?? 'index';

$controllerName = ucfirst($c) . 'Controller';
$controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
} else {
    die("No existe el controlador: $controllerName");
}

if (class_exists($controllerName)) {
    $obj = new $controllerName();


    // Ejecutar acción
    if (method_exists($obj, $a)) {
        $obj->$a();
    } else {
        die("Método $a no existe en $controllerName");
    }
} else {
    die("Clase $controllerName no existe.");
}
