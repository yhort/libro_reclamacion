<?php
declare(strict_types=1);

final class Db {
    private static ?\PDO $pdo = null;

    public static function pdo(): \PDO {
    if (self::$pdo instanceof \PDO) {
        return self::$pdo;
    }

    try {
        $dsn = sprintf('mysql:host=%s;port=3306;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);

        // Forzamos el uso de las constantes que definimos en database.php
        self::$pdo = new \PDO(
            $dsn,
            DB_USER, 
            DB_PASS,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        return self::$pdo;
    } catch (\PDOException $e) {
        // Este DEBUG nos dirá si realmente DB_USER es 'root'
        die("DEBUG: Conectando a " . DB_HOST . " con usuario (" . DB_USER . ") <br> Error: " . $e->getMessage());
    }
}
}