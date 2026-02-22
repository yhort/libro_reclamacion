<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$pruebas = [
    ['user' => 'root', 'pass' => ''],      // Estándar XAMPP
    ['user' => 'root', 'pass' => 'root'],  // Estándar MAMP / Algunos XAMPP Mac
    ['user' => 'root', 'pass' => 'root']   // Común si la cambiaste
];

foreach ($pruebas as $p) {
    try {
        $dsn = "mysql:host=127.0.0.1;charset=utf8mb4";
        $pdo = new PDO($dsn, $p['user'], $p['pass']);
        die("<h1 style='color:green'>¡ÉXITO! Tu usuario es: {$p['user']} y tu clave es: '{$p['pass']}'</h1>");
    } catch (PDOException $e) {
        echo "Falló con usuario '{$p['user']}' y clave '{$p['pass']}': " . $e->getMessage() . "<br>";
    }
}

echo "<h1 style='color:red'>Ninguna funcionó. Revisa si el MySQL de XAMPP está en VERDE.</h1>";