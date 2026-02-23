<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Dompdf\Dompdf;
use Dompdf\Options;

class DashboardController
{

    public function __construct()
    {
        // Solo usuarios internos
        Auth::requireLogin();
    }

    public function index()
    {
        $db = Db::pdo();

        // Filtros
        $num_doc     = $_GET['num_doc'] ?? '';
        $correlativo = $_GET['correlativo'] ?? '';
        $estado      = $_GET['estado'] ?? '';
        $tipo        = $_GET['tipo'] ?? '';
        $desde       = $_GET['desde'] ?? '';
        $hasta       = $_GET['hasta'] ?? '';

        $where = [];
        $params = [];

        if ($num_doc !== '') {
            $where[] = "num_doc LIKE ?";
            $params[] = "%$num_doc%";
        }
        if ($correlativo !== '') {
            $where[] = "correlativo LIKE ?";
            $params[] = "%$correlativo%";
        }
        if ($estado !== '') {
            $where[] = "estado = ?";
            $params[] = $estado;
        }
        if ($tipo !== '') {
            $where[] = "tipo_incidencia = ?";
            $params[] = $tipo;
        }
        if ($desde !== '' && $hasta !== '') {
            $where[] = "DATE(fecha_registro) BETWEEN ? AND ?";
            $params[] = $desde;
            $params[] = $hasta;
        }

        $sqlWhere = $where ? "WHERE " . implode(" AND ", $where) : "";

        // PAGINACIÓN
        $porPagina = 10;
        $pagina = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $pagina = max(1, $pagina);
        $offset = ($pagina - 1) * $porPagina;

        $stmtTotal = $db->prepare("SELECT COUNT(*) FROM reclamaciones $sqlWhere");
        $stmtTotal->execute($params);
        $totalRegistros = $stmtTotal->fetchColumn();
        $totalPaginas = ceil($totalRegistros / $porPagina);

        $stmt = $db->prepare("SELECT * FROM reclamaciones $sqlWhere ORDER BY fecha_registro DESC LIMIT $porPagina OFFSET $offset");
        $stmt->execute($params);
        $ultimosReclamos = $stmt->fetchAll();

        // Estadísticas generales
        $stats = [
            'total'         => $db->query("SELECT COUNT(*) FROM reclamaciones")->fetchColumn(),
            'solo_reclamos' => $db->query("SELECT COUNT(*) FROM reclamaciones WHERE tipo_incidencia='Reclamo'")->fetchColumn(),
            'solo_quejas'   => $db->query("SELECT COUNT(*) FROM reclamaciones WHERE tipo_incidencia='Queja'")->fetchColumn(),
            'pendientes'    => $db->query("SELECT COUNT(*) FROM reclamaciones WHERE estado='Pendiente'")->fetchColumn(),
        ];

        // Datos para gráfico mensual
        $labels = [];
        $valores = [];
        $stmtChart = $db->query("SELECT DATE_FORMAT(fecha_registro,'%Y-%m') AS mes, COUNT(*) AS total FROM reclamaciones GROUP BY mes ORDER BY mes ASC");
        $chartData = $stmtChart->fetchAll();
        foreach ($chartData as $row) {
            $labels[] = $row['mes'];
            $valores[] = $row['total'];
        }

        View::render('dashboard/index', [
            'stats' => $stats,
            'ultimosReclamos' => $ultimosReclamos,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'labels' => json_encode($labels),
            'valores' => json_encode($valores),
        ]);
    }

    public function ver()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) header("Location: index.php?c=dashboard&a=index");

        $db = Db::pdo();
        $stmt = $db->prepare("SELECT * FROM reclamaciones WHERE id = ?");
        $stmt->execute([$id]);
        $r = $stmt->fetch();

        if (!$r) die("Reclamo no encontrado.");

        View::render('dashboard/ver', ['r' => $r]);
    }

    public function responder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $id = (int) $_POST['id'];
            if ($id <= 0) {
                header("Location: index.php?c=dashboard&a=index");
                exit;
            }
            $respuesta = $_POST['respuesta_negocio'];

            $db = Db::pdo();
            $stmt = $db->prepare("UPDATE reclamaciones SET respuesta_negocio = ?, estado='Atendido', fecha_respuesta=NOW() WHERE id=?");
            $stmt->execute([$respuesta, $id]);

            $stmt = $db->prepare("SELECT email, nombre_completo, correlativo FROM reclamaciones WHERE id=?");
            $stmt->execute([$id]);
            $cliente = $stmt->fetch();

            $this->enviarNotificacionEmail($cliente['email'], $cliente['nombre_completo'], $cliente['correlativo'], $respuesta);

            header("Location: index.php?c=dashboard&a=index&msj=respondido");
            exit;
        }
    }

    private function enviarNotificacionEmail($email, $nombre, $correlativo, $respuesta)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USER;
            $mail->Password = MAIL_PASS;
            $mail->SMTPSecure = (MAIL_PORT == 465 ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS);
            $mail->Port = MAIL_PORT;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(MAIL_USER, MAIL_FROM_NAME);
            $mail->addAddress($email, $nombre);

            $mail->isHTML(true);
            $mail->Subject = "Respuesta a su Reclamo N° $correlativo";
            $mail->Body = "<p>Estimado(a) <b>$nombre</b>, su reclamo <b>$correlativo</b> ha sido respondido:</p><p>" . nl2br(htmlspecialchars($respuesta)) . "</p>";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error PHPMailer: {$mail->ErrorInfo}");
        }
    }
}
