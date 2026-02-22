<?php
// Cargar el autoloader de la raíz (sube un nivel desde /public)
//require_once __DIR__ . '/../vendor/autoload.php';

// public/index.php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/app/init.php';

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

Env::load(__DIR__ . '/../.env');

require_once __DIR__ . '/../app/config/constants.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/Db.php';
require_once __DIR__ . '/../app/core/View.php';
require_once __DIR__ . '/../app/core/Auth.php';



session_start();

// 2. Definir controlador y acción por defecto
// Si el usuario entra a la raíz, cargamos el formulario de reclamo
$controllerName = strtolower($_GET['c'] ?? 'reclamo');
$action         = strtolower($_GET['a'] ?? 'index');

// 3. Verificación de seguridad (LISTA BLANCA)
// El controlador 'reclamo' es público para que cualquier cliente lo use
$publicControllers = ['reclamo']; 

try {
    if (!in_array($controllerName, $publicControllers)) {
        Auth::requireLogin(); // Protege el dashboard, reportes, etc.
    }
} catch (Exception $e) {
    // Si no está logueado y trata de entrar al admin, va al login
    header("Location: login.php");
    exit;
}

// 4. Carga dinámica del Controlador
$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile  = __DIR__ . '/../app/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    header("HTTP/1.0 404 Not Found");
    die("Error 404: El controlador no existe.");
}

require_once $controllerFile;
$instance = new $controllerClass();

if (!method_exists($instance, $action)) {
    die("Error: La acción '$action' no existe en $controllerClass");
}

// Ejecutar la acción
$instance->$action();