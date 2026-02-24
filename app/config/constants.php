<?php
declare(strict_types=1);

date_default_timezone_set('America/Lima');


/* // Usamos la variable de entorno para decidir la URL automáticamente
if (isset($_ENV['ENTORNO']) && $_ENV['ENTORNO'] === 'local') {
    // Tu ruta en la computadora
    //define('BASE_URL', 'http://localhost/galeria_web/public');
    define('BASE_URL', 'http://localhost/invanelay/public');
} else {
    // Tu ruta en el hosting real
    define('BASE_URL', 'https://tykesoft.com');
}
 */

date_default_timezone_set('America/Lima');

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');

define('BASE_URL', $scheme . '://' . $host . $basePath);
/**
 * Genera URLs para los assets (CSS, JS, Imágenes)
 */
function asset(string $path): string {
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Genera URLs para los enlaces internos (Rutas)
 */
function url(string $path): string {
    return BASE_URL . '/' . ltrim($path, '/');
}