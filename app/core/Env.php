<?php
declare(strict_types=1);

class Env {
    public static function load(string $path): void {
        if (!file_exists($path)) {
            // Esto te avisará si la ruta del .env está mal
            return; 
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Ignorar comentarios y líneas vacías
            if (empty($line) || strpos($line, '#') === 0) continue;

            // Separar nombre y valor
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                // Forzamos la carga en múltiples sitios para que PHP lo vea sí o sí
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
                putenv("{$name}={$value}");
            }
        }
    }
}