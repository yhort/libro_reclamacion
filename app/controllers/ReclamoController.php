<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/Reclamo.php';


class ReclamoController {
    private $modelo;

    public function __construct() {
        // Usamos tu clase Db del core directamente
        $this->modelo = new Reclamo(Db::pdo());
    }

    public function index() {
        View::render('public/formulario_view', [], false);
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // El modelo ahora devuelve el correlativo o false
            $correlativo = $this->modelo->crear($_POST);

            if ($correlativo) {
                // Redirigir a éxito pasando el código por URL
                header("Location: index.php?c=reclamo&a=exito&codigo=" . $correlativo);
                exit;
            } else {
                die("Error al guardar el reclamo en la base de datos.");
            }
        }
    }

    

public function consultar() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $num_doc = $_POST['num_doc'];
        $correlativo = $_POST['correlativo'];

        $db = Db::pdo();
        $stmt = $db->prepare("SELECT * FROM reclamaciones WHERE num_doc = ? AND correlativo = ?");
        $stmt->execute([$num_doc, $correlativo]);
        $reclamo = $stmt->fetch();

        if ($reclamo) {
            View::render('public/estado_reclamo', ['r' => $reclamo]);
        } else {
            die("No se encontró ningún registro con esos datos. Verifique la información.");
        }
    }
}


    public function exito() {
        $codigo = $_GET['codigo'] ?? 'N/A';
        View::render('public/exito', ['codigo' => $codigo]);
    }
}