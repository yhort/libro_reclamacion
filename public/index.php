<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/init.php';

// Forzamos que si no hay nada, cargue Reclamo
$c = $_GET['c'] ?? 'Reclamo';
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
    $obj->$a();
} else {
    die("Error: El archivo app/controllers/$controllerName.php no existe o el nombre de la clase es incorrecto.");
}