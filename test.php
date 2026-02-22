<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Quita el "/.." porque test.php ya está en la carpeta galeria_web
$file = __DIR__ . '/app/core/Env.php'; 

if (!file_exists($file)) {
    die("Error: Todavía no lo encuentro. Ruta intentada: " . $file);
}

require_once $file;
// Lo mismo para el .env, está en la misma carpeta que este test.php
Env::load(__DIR__ . '/.env');

echo "<h1>¡Conectado!</h1>";
echo "El host de mi DB es: " . ($_ENV['DB_HOST'] ?? 'No se leyó el .env');