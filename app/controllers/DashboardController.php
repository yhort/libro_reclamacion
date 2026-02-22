<?php
declare(strict_types=1);

// Importaciones de PHPMailer (Vienen de vendor/ en la raíz vía el autoload de index.php)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Importaciones de Dompdf (Vienen de libs/)
use Dompdf\Dompdf;
use Dompdf\Options;

class DashboardController {

    public function __construct() {
        // Protección de sesión mediante tu clase Auth
        Auth::requireLogin();
    }

    public function index() {
        $db = Db::pdo();
        
        $stats = [
            'total'         => $db->query("SELECT COUNT(*) FROM reclamaciones")->fetchColumn(),
            'solo_reclamos' => $db->query("SELECT COUNT(*) FROM reclamaciones WHERE tipo_incidencia = 'Reclamo'")->fetchColumn(),
            'solo_quejas'   => $db->query("SELECT COUNT(*) FROM reclamaciones WHERE tipo_incidencia = 'Queja'")->fetchColumn(),
            'pendientes'    => $db->query("SELECT COUNT(*) FROM reclamaciones WHERE estado = 'Pendiente'")->fetchColumn(),
        ];

        $anioActual = date('Y');
        $queryGrafico = $db->prepare("
            SELECT MONTHNAME(fecha_registro) as mes, COUNT(*) as total 
            FROM reclamaciones 
            WHERE YEAR(fecha_registro) = ? 
            GROUP BY MONTH(fecha_registro) 
            ORDER BY MONTH(fecha_registro) ASC
        ");
        $queryGrafico->execute([$anioActual]);
        $dataGrafico = $queryGrafico->fetchAll();

        $labels = []; $valores = [];
        foreach ($dataGrafico as $row) {
            $labels[] = $row['mes'];
            $valores[] = (int)$row['total'];
        }

        $ultimosReclamos = $db->query("SELECT * FROM reclamaciones ORDER BY fecha_registro DESC LIMIT 5")->fetchAll();

        View::render('dashboard/index', [
            'stats'           => $stats,
            'labels'          => json_encode($labels ?: [date('M')]),
            'valores'         => json_encode($valores ?: [0]),
            'ultimosReclamos' => $ultimosReclamos 
        ]);
    }

    public function ver() {
        $id = $_GET['id'] ?? null;
        if (!$id) header("Location: index.php?c=dashboard");

        $db = Db::pdo();
        $stmt = $db->prepare("SELECT * FROM reclamaciones WHERE id = ?");
        $stmt->execute([$id]);
        $r = $stmt->fetch();

        if (!$r) die("Reclamo no encontrado.");

        View::render('dashboard/ver', ['r' => $r]);
    }

    public function responder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $respuesta = $_POST['respuesta_negocio'];
            
            $db = Db::pdo();
            $stmt = $db->prepare("UPDATE reclamaciones SET respuesta_negocio = ?, estado = 'Atendido', fecha_respuesta = NOW() WHERE id = ?");
            $stmt->execute([$respuesta, $id]);

            $stmt = $db->prepare("SELECT email, nombre_completo, correlativo FROM reclamaciones WHERE id = ?");
            $stmt->execute([$id]);
            $cliente = $stmt->fetch();

            $this->enviarNotificacionEmail($cliente['email'], $cliente['nombre_completo'], $cliente['correlativo'], $respuesta);

            header("Location: index.php?c=dashboard&a=index&msj=respondido");
            exit;
        }
    }

    public function imprimir() {
        $id = $_GET['id'] ?? null;
        $db = Db::pdo();
        $stmt = $db->prepare("SELECT * FROM reclamaciones WHERE id = ?");
        $stmt->execute([$id]);
        $r = $stmt->fetch();

        if (!$r) die("No existe el registro");

        // RUTA CORRECTA para Dompdf según tu estructura en libs/
        // Asegúrate de que esta ruta sea exacta a donde tienes el autoload de dompdf
        require_once __DIR__ . '/../../libs/dompdf/autoload.inc.php';

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Útil si tienes imágenes con URL en el PDF
        $dompdf = new Dompdf($options);
        
        // El HTML se toma de tu vista específica para el PDF
        ob_start();
        // Pasamos la variable $r para que esté disponible en formato_pdf.php
        require __DIR__ . '/../views/dashboard/formato_pdf.php'; 
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Salida al navegador: Attachment false abre el PDF en la pestaña, true lo descarga.
        $dompdf->stream("Reclamo_" . $r['correlativo'] . ".pdf", ["Attachment" => false]);
    }

    private function enviarNotificacionEmail($emailCliente, $nombreCliente, $correlativo, $respuesta) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USER;
            $mail->Password   = MAIL_PASS;
            // Hostinger suele requerir SMTPS (465) o STARTTLS (587)
            $mail->SMTPSecure = (MAIL_PORT == 465) ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(MAIL_USER, MAIL_FROM_NAME);
            $mail->addAddress($emailCliente, $nombreCliente);

            $mail->isHTML(true);
            $mail->Subject = "Respuesta a su Reclamacion N° $correlativo - " . MAIL_FROM_NAME;
            $mail->Body = "
                <div style='font-family: Arial; padding: 20px; border: 1px solid #eee;'>
                    <h2 style='color: #004a99;'>Respuesta Oficial</h2>
                    <p>Estimado(a) <strong>$nombreCliente</strong>,</p>
                    <p>Le informamos que se ha emitido una respuesta oficial a su reclamo registrado con el código <b>$correlativo</b>:</p>
                    <div style='background: #f9f9f9; padding: 15px; border-left: 4px solid #004a99; margin: 20px 0;'>
                        " . nl2br(htmlspecialchars($respuesta)) . "
                    </div>
                    <p>Atentamente,<br><strong>" . MAIL_FROM_NAME . "</strong></p>
                </div>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error PHPMailer: {$mail->ErrorInfo}");
            return false;
        }
    }
}