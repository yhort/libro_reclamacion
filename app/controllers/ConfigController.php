<?php

declare(strict_types=1);

final class ConfigController
{
    private Configuracion $model;

    public function __construct()
    {
        require_once __DIR__ . '/../models/Configuracion.php';
        Auth::only(['ADMIN']); // Solo el jefe cambia esto
        $this->model = new Configuracion();
    }

    public function empresa(): void
    {
        $config = $this->model->obtener();
        View::render('config/empresa', [
            'pageTitle' => 'Datos de la Galería',
            'config'    => $config
        ]);
    }

    public function guardarEmpresa(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $currentConfig = $this->model->obtener();
        $logoPath = $currentConfig['logo_path'];

        // Manejo de la subida del Logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/img/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $fileName = 'logo_galeria.' . $extension;
            $fullPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $fullPath)) {
                $logoPath = $fullPath;
            }
        }

        $data = [
            'nombre_galeria' => $_POST['nombre_galeria'],
            'ruc'            => $_POST['ruc'],
            'direccion'      => $_POST['direccion'],
            'telefono'       => $_POST['telefono'],
            'email_contacto' => $_POST['email_contacto'],
            'moneda'         => $_POST['moneda'],
            'mensaje_ticket' => $_POST['mensaje_ticket'],
            'logo_path'      => $logoPath
        ];

        if ($this->model->actualizar($data)) {
            header('Location: index.php?c=config&a=empresa&success=1');
        } else {
            header('Location: index.php?c=config&a=empresa&error=1');
        }
    }
}
