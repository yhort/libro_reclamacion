<?php

declare(strict_types=1);

final class Configuracion
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::pdo();
    }

    public function obtener(): array
    {
        $stmt = $this->db->query("SELECT * FROM configuracion WHERE id = 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar(array $data): bool
    {
        $sql = "UPDATE configuracion SET 
                nombre_galeria = :nombre,
                ruc = :ruc,
                direccion = :direccion,
                telefono = :telefono,
                email_contacto = :email,
                moneda = :moneda,
                mensaje_ticket = :mensaje,
                logo_path = :logo
                WHERE id = 1";

        return $this->db->prepare($sql)->execute([
            ':nombre'    => $data['nombre_galeria'],
            ':ruc'       => $data['ruc'],
            ':direccion' => $data['direccion'],
            ':telefono'  => $data['telefono'],
            ':email'     => $data['email_contacto'],
            ':moneda'    => $data['moneda'],
            ':mensaje'   => $data['mensaje_ticket'],
            ':logo'      => $data['logo_path']
        ]);
    }
}
