<?php
declare(strict_types=1);

// Buscamos la variable en $_ENV, si no está en $_SERVER, y si no usamos getenv()
define('DB_HOST',    $_ENV['DB_HOST']    ?? $_SERVER['DB_HOST']    ?? getenv('DB_HOST') ?: '127.0.0.1'); 
define('DB_NAME',    $_ENV['DB_NAME']    ?? $_SERVER['DB_NAME']    ?? getenv('DB_NAME') ?: 'db_reclamos_online');
define('DB_USER',    $_ENV['DB_USER']    ?? $_SERVER['DB_USER']    ?? getenv('DB_USER') ?: 'root');
define('DB_PASS',    $_ENV['DB_PASS']    ?? $_SERVER['DB_PASS']    ?? getenv('DB_PASS') ?: 'root'); // Tu clave es root
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');