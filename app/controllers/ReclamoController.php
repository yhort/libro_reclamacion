<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ReclamoController
{
    public function index()
    {
        // SIN layout (formulario público)
        View::render('reclamo/formulario_view', [], 'public');
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $db = Db::pdo();

            $nombre = trim($_POST['nombre_completo'] ?? '');
            $tipo_doc = $_POST['tipo_doc'] ?? 'DNI';
            $num_doc = trim($_POST['num_doc'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $tipo_bien = $_POST['tipo_bien'] ?? 'Producto';
            $monto = floatval($_POST['monto_reclamado'] ?? 0);
            $descripcion = $_POST['descripcion_bien'] ?? '';
            $tipo_incidencia = $_POST['tipo_incidencia'] ?? 'Reclamo';
            $detalle = $_POST['detalle_cliente'] ?? '';
            $pedido = $_POST['pedido_cliente'] ?? '';

            $year = date('Y');
            $stmt = $db->query("SELECT COUNT(*)+1 FROM reclamaciones WHERE YEAR(fecha_registro) = $year");
            $next = $stmt->fetchColumn();
            $correlativo = sprintf("R-%s-%04d", $year, $next);

            $stmt = $db->prepare("INSERT INTO reclamaciones
                (correlativo, nombre_completo, tipo_doc, num_doc, email, telefono, direccion,
                 tipo_bien, monto_reclamado, descripcion_bien, tipo_incidencia, detalle_cliente, pedido_cliente)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $correlativo,
                $nombre,
                $tipo_doc,
                $num_doc,
                $email,
                $telefono,
                $direccion,
                $tipo_bien,
                $monto,
                $descripcion,
                $tipo_incidencia,
                $detalle,
                $pedido
            ]);

            $this->enviarCorreo($email, $correlativo, $nombre);
            // SIN layout
           View::render('reclamo/exito', ['correlativo' => $correlativo], 'public');
        }
    }

    public function consultar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->consultar_form();
            return;
        }

        if (!isset($_SESSION['consulta_intentos'])) {
            $_SESSION['consulta_intentos'] = 0;
        }

        if ($_SESSION['consulta_intentos'] >= 5) {
            View::render('reclamo/consulta_form', [
                'error' => 'Demasiados intentos. Intente más tarde.'
            ], 'public');
            return;
        }

        $num_doc = trim($_POST['num_doc'] ?? '');
        $correlativo = strtoupper(trim($_POST['correlativo'] ?? ''));

        $db = Db::pdo();
        $stmt = $db->prepare("
        SELECT * FROM reclamaciones 
        WHERE TRIM(num_doc) = ? 
        AND UPPER(TRIM(correlativo)) = ?
    ");
        $stmt->execute([$num_doc, $correlativo]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$r) {
            $_SESSION['consulta_intentos']++;

            View::render('reclamo/consultar_form', [
                'error' => 'No se encontró el reclamo. Verifique los datos.'
            ], 'public');
            return;
        }

        $_SESSION['consulta_intentos'] = 0;

        View::render('reclamo/estado_reclamo', [
            'r' => $r
        ], 'public');
    }

    public function consultar_form()
    {
        View::render('reclamo/consulta_form', [], 'public');
    }

    private function enviarCorreo(string $destino, string $correlativo, string $nombre): void
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USER;
            $mail->Password   = MAIL_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;

            $mail->setFrom(MAIL_USER, MAIL_FROM_NAME);
            $mail->addAddress($destino, $nombre);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = "Confirmación de Reclamo - $correlativo";

            $mail->Body = "
            <h3>Estimado(a) {$nombre}</h3>
            <p>Su reclamo fue registrado correctamente.</p>
            <p><strong>Código:</strong> {$correlativo}</p>
            <p>Puede consultar el estado en el siguiente enlace:</p>
            <p>
                <a href='http://localhost/libro_reclamacion/invanelay/public/index.php?c=reclamo&a=consultar'>
                    Consultar mi reclamo
                </a>
            </p>
            <p>Plazo máximo de respuesta: 15 días hábiles.</p>
            <br>
            <small>Este es un mensaje automático, no responder.</small>
        ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error enviando correo: " . $mail->ErrorInfo);
        }
    }
}
