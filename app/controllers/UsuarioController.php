<?php

declare(strict_types=1);

final class UsuarioController
{
    private Usuario $model;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Usuario.php';
        Auth::only(['ADMIN']);
        $this->model = new Usuario();
    }

    public function index(): void
    {
        $usuarios = $this->model->listarTodos();
        View::render('usuarios/index', [
            'pageTitle' => 'Gestión de Usuarios',
            'usuarios'  => $usuarios
        ]);
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=usuario&a=index');
            exit;
        }

        $id = isset($_POST['id']) && $_POST['id'] !== "" ? (int)$_POST['id'] : null;
        $adminId = (int)$_SESSION['usuario_id'];

        $email = trim($_POST['email']);

        // NUEVA VALIDACIÓN: ¿Email duplicado?
        if ($this->model->existeEmail($email, $id)) {
            header('Location: index.php?c=usuario&a=index&error=email_duplicado');
            exit;
        }

        $data = [
            'id'       => $id,
            'username' => trim($_POST['username']),
            'email'    => trim($_POST['email']),
            'rol'      => $_POST['rol'],
            'activo'   => isset($_POST['activo']) ? 1 : 0,
            'password' => $_POST['password'] ?? ''
        ];

        // Redirecciones corregidas para evitar el 404
        if (empty($data['username']) || empty($data['email'])) {
            header('Location: index.php?c=usuario&a=index&error=campos_vacios');
            exit;
        }

        if ($id) {
            $exito = $this->model->actualizar($data, $adminId);
        } else {
            if (empty($data['password'])) {
                header('Location: index.php?c=usuario&a=index&error=password_requerida');
                exit;
            }
            $exito = $this->model->crear($data, $adminId);
        }

        if ($exito) {
            header('Location: index.php?c=usuario&a=index&success=1');
        } else {
            header('Location: index.php?c=usuario&a=index&error=db');
        }
        exit;
    }
}
