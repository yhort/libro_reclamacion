<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class DashboardController
{
    public function __construct()
    {
        // Solo usuarios internos
        // Auth::requireLogin();
    }

    public function index()
    {
        try {
            $db = Db::pdo();

            // ========================
            // FILTROS
            // ========================
            $num_doc     = trim($_GET['num_doc'] ?? '');
            $correlativo = trim($_GET['correlativo'] ?? '');
            $estado      = trim($_GET['estado'] ?? '');
            $tipo        = trim($_GET['tipo'] ?? '');
            $desde       = trim($_GET['desde'] ?? '');
            $hasta       = trim($_GET['hasta'] ?? '');

            // ✅ TODO named params
            $where  = [];
            $params = [];

            if ($num_doc !== '') {
                $where[] = "num_doc LIKE :num_doc";
                $params[':num_doc'] = "%{$num_doc}%";
            }
            if ($correlativo !== '') {
                $where[] = "correlativo LIKE :correlativo";
                $params[':correlativo'] = "%{$correlativo}%";
            }
            if ($estado !== '') {
                $where[] = "estado = :estado";
                $params[':estado'] = $estado;
            }
            if ($tipo !== '') {
                $where[] = "tipo_incidencia = :tipo";
                $params[':tipo'] = $tipo;
            }
            if ($desde !== '' && $hasta !== '') {
                $where[] = "DATE(fecha_registro) BETWEEN :desde AND :hasta";
                $params[':desde'] = $desde;
                $params[':hasta'] = $hasta;
            }

            $sqlWhere = $where ? "WHERE " . implode(" AND ", $where) : "";

            // ========================
            // PAGINACIÓN
            // ========================
            $porPagina = 10;
            $pagina = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $pagina = max(1, $pagina);
            $offset = ($pagina - 1) * $porPagina;

            // TOTAL
            $stmtTotal = $db->prepare("SELECT COUNT(*) FROM reclamaciones $sqlWhere");
            $stmtTotal->execute($params);
            $totalRegistros = (int)$stmtTotal->fetchColumn();
            $totalPaginas = max(1, (int)ceil($totalRegistros / $porPagina));

            // LISTA
            $sql = "SELECT * FROM reclamaciones $sqlWhere
                    ORDER BY fecha_registro DESC
                    LIMIT :limit OFFSET :offset";

            $stmt = $db->prepare($sql);

            // bind filtros
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            // bind paginación
            $stmt->bindValue(':limit', $porPagina, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            $reclamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // ========================
            // STATS
            // ========================
            $stats = [
                'total'         => (int)$db->query("SELECT COUNT(*) FROM reclamaciones")->fetchColumn(),
                'solo_reclamos' => (int)$db->query("SELECT COUNT(*) FROM reclamaciones WHERE tipo_incidencia='Reclamo'")->fetchColumn(),
                'solo_quejas'   => (int)$db->query("SELECT COUNT(*) FROM reclamaciones WHERE tipo_incidencia='Queja'")->fetchColumn(),
                'pendientes'    => (int)$db->query("SELECT COUNT(*) FROM reclamaciones WHERE estado='Pendiente'")->fetchColumn(),
            ];

            // ========================
            // GRÁFICO
            // ========================
            $labels  = [];
            $valores = [];

            $stmtChart = $db->query("
                SELECT DATE_FORMAT(fecha_registro,'%Y-%m') AS mes, COUNT(*) AS total
                FROM reclamaciones
                GROUP BY mes
                ORDER BY mes ASC
            ");

            foreach ($stmtChart->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $labels[]  = $row['mes'];
                $valores[] = (int)$row['total'];
            }

            View::render('dashboard/index', [
                'stats' => $stats,
                'reclamos' => $reclamos,
                'pagina' => $pagina,
                'totalPaginas' => $totalPaginas,
                'labels' => json_encode($labels),
                'valores' => json_encode($valores),
            ]);

        } catch (\Throwable $e) {
            echo "<h1>ERROR EN DASHBOARD</h1>";
            echo "<pre>";
            echo $e->getMessage();
            echo "\n\n";
            echo $e->getFile() . " : " . $e->getLine();
            echo "</pre>";
            exit;
        }
    }

    public function ver()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) header("Location: index.php?c=dashboard&a=index");

        $db = Db::pdo();
        $stmt = $db->prepare("SELECT * FROM reclamaciones WHERE id = ?");
        $stmt->execute([$id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$r) die("Reclamo no encontrado.");

        View::render('dashboard/ver', ['r' => $r]);
    }

    public function responder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                header("Location: index.php?c=dashboard&a=index");
                exit;
            }

            $respuesta = (string)($_POST['respuesta_negocio'] ?? '');

            $db = Db::pdo();
            $stmt = $db->prepare("UPDATE reclamaciones SET respuesta_negocio = ?, estado='Atendido', fecha_respuesta=NOW() WHERE id=?");
            $stmt->execute([$respuesta, $id]);

            $stmt = $db->prepare("SELECT email, nombre_completo, correlativo FROM reclamaciones WHERE id=?");
            $stmt->execute([$id]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

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