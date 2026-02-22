<?php
declare(strict_types=1);

class Reclamo {
    private \PDO $db;

    public function __construct(\PDO $pdo) {
        $this->db = $pdo;
    }

    public function crear(array $datos) {
        try {
            $anio = date("Y");
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM reclamaciones WHERE YEAR(fecha_registro) = ?");
            $stmt->execute([$anio]);
            $count = (int)$stmt->fetchColumn() + 1;
            $correlativo = "R-$anio-" . str_pad((string)$count, 4, "0", STR_PAD_LEFT);

            $sql = "INSERT INTO reclamaciones (
                correlativo, nombre_completo, tipo_doc, num_doc, email, 
                telefono, direccion, tipo_bien, monto_reclamado, 
                descripcion_bien, tipo_incidencia, detalle_cliente, pedido_cliente, fecha_registro
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $this->db->prepare($sql);
            $res = $stmt->execute([
                $correlativo, $datos['nombre_completo'], $datos['tipo_doc'], $datos['num_doc'],
                $datos['email'], $datos['telefono'] ?? '', $datos['direccion'],
                $datos['tipo_bien'], $datos['monto_reclamado'] ?? 0, $datos['descripcion_bien'] ?? '',
                $datos['tipo_incidencia'], $datos['detalle_cliente'], $datos['pedido_cliente']
            ]);

            return $res ? $correlativo : false;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}