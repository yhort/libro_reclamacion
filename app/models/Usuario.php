<?php

declare(strict_types=1);

final class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Db::pdo();
    }

    /**
     * Para el Login: Busca usuario activo por username.
     */
    public static function findActiveByUsername(string $username): ?array
    {
        $sql = "SELECT usuario_id, username, password_hash, rol, activo
                FROM usuarios
                WHERE username = :username AND activo = 1
                LIMIT 1";

        $stmt = Db::pdo()->prepare($sql);
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Lista todos los usuarios con los nombres de quienes crearon/editaron.
     */
    public function listarTodos(): array
    {
        $sql = "SELECT u.*, 
                creator.username as creador_nombre,
                editor.username as editor_nombre
                FROM usuarios u
                LEFT JOIN usuarios creator ON u.creado_por = creator.usuario_id
                LEFT JOIN usuarios editor ON u.editado_por = editor.usuario_id
                ORDER BY u.creado_en DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo usuario capturando quién lo hace.
     */
    public function crear(array $data, int $adminId): bool
    {
        $sql = "INSERT INTO usuarios (username, email, password_hash, rol, activo, creado_por, creado_en) 
                VALUES (:username, :email, :password, :rol, :activo, :adminId, NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':username' => $data['username'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':rol'      => $data['rol'],
            ':activo'   => (int)$data['activo'],
            ':adminId'  => $adminId
        ]);
    }

    /**
     * Actualiza datos y registra quién hizo el último cambio.
     */
    public function actualizar(array $data, int $adminId): bool
    {
        // La contraseña solo se actualiza si se envía una nueva
        $updatePass = !empty($data['password']) ? ", password_hash = :password" : "";

        $sql = "UPDATE usuarios SET 
                username = :username, 
                email = :email, 
                rol = :rol, 
                activo = :activo,
                editado_por = :adminId,
                editado_en = NOW()
                $updatePass
                WHERE usuario_id = :id";

        $params = [
            ':username' => $data['username'],
            ':email'    => $data['email'],
            ':rol'      => $data['rol'],
            ':activo'   => (int)$data['activo'],
            ':adminId'  => $adminId,
            ':id'       => $data['id']
        ];

        if (!empty($data['password'])) {
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->db->prepare($sql)->execute($params);
    }

    /**
     * Verifica si un email ya existe, excluyendo un ID (útil para edición)
     */
    public function existeEmail(string $email, ?int $excluirId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $params = [':email' => $email];

        if ($excluirId) {
            $sql .= " AND usuario_id != :id";
            $params[':id'] = $excluirId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}
