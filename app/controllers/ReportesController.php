<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once __DIR__ . '/../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class ReportesController
{
    private $model;



    public function index(): void
    {

        // 1. Parámetros básicos
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        // 2. Lógica de Paginación
        $registrosPorPagina = 20; // Número de filas que verás antes de usar el scroll/paginador
        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($paginaActual < 1) $paginaActual = 1;
        $offset = ($paginaActual - 1) * $registrosPorPagina;

        // 3. Obtener datos del modelo (Necesitas ajustar tu modelo para que soporte LIMIT y OFFSET)
        // Obtenemos solo los movimientos de la página actual
        $movimientos = $this->model->obtenerFlujoDetalladoPaginado($fecha, $registrosPorPagina, $offset);

        // Obtenemos el total de registros para calcular las páginas (necesario para el diseño de botones)
        $totalRegistros = $this->model->contarMovimientosPorFecha($fecha);
        $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

        // 4. Saldo anterior (esto se mantiene igual ya que es un valor único del día)
        $saldoAnterior = $this->model->obtenerSaldoAnterior($fecha);

        View::render('reportes/index', [
            'pageTitle' => 'Reporte de Flujo de Caja',
            'movimientos' => $movimientos,
            'fecha' => $fecha,
            'saldoAnterior' => $saldoAnterior,
            'paginaActual' => $paginaActual,
            'totalPaginas' => $totalPaginas,
            'totalRegistros' => $totalRegistros
        ]);
    }

    // El método imprimir DEBE seguir trayendo TODOS los movimientos (sin paginación)
    // para que el PDF salga completo.
    // ReportesController.php - Actualiza el método imprimir
    public function imprimir(): void
    {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $movimientos = $this->model->obtenerFlujoDetallado($fecha);
        $saldoAnterior = $this->model->obtenerSaldoAnterior($fecha);

        // Inicializamos variables para el resumen administrativo
        $t_ing = 0;
        $t_egr = 0;
        $retiros = 0; // Para conceptos como "ENTREGA" o "RETIRO"
        $gastos_op = 0; // Gastos normales

        foreach ($movimientos as $m) {
            if ($m['tipo'] == 'INGRESO') {
                $t_ing += $m['monto'];
            } else {
                $t_egr += $m['monto'];
                // Clasificación automática para el resumen
                if (stripos($m['concepto'], 'ENTREGA') !== false || stripos($m['concepto'], 'RETIRO') !== false) {
                    $retiros += $m['monto'];
                } else {
                    $gastos_op += $m['monto'];
                }
            }
        }

        $dompdf = new \Dompdf\Dompdf();
        ob_start();
        // Pasamos las variables calculadas al archivo del formato
        require __DIR__ . '/../views/reportes/formato_pdf.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Reporte_$fecha.pdf", ["Attachment" => false]);
        exit;
    }

    public function exportarExcel(): void
    {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $movimientos = $this->model->obtenerFlujoDetallado($fecha);

        // Cabeceras para descargar Excel
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=Reporte_Caja_$fecha.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<tr><th colspan='6' style='background-color:#001f3f; color:white;'>REPORTE DE CAJA - $fecha</th></tr>";
        echo "<tr>
                <th>Hora</th>
                <th>Modulo</th>
                <th>Concepto</th>
                <th>Ingreso</th>
                <th>Egreso</th>
                <th>Usuario</th>
              </tr>";

        $t_ing = 0;
        $t_egr = 0;
        foreach ($movimientos as $m) {
            $ing = ($m['tipo'] == 'INGRESO') ? $m['monto'] : 0;
            $egr = ($m['tipo'] != 'INGRESO') ? $m['monto'] : 0;
            $t_ing += $ing;
            $t_egr += $egr;

            echo "<tr>";
            echo "<td>" . date('H:i', strtotime($m['fecha_hora'])) . "</td>";
            echo "<td>{$m['modulo']}</td>";
            echo "<td>" . htmlspecialchars($m['concepto']) . "</td>";
            echo "<td>" . number_format($ing, 2) . "</td>";
            echo "<td>" . number_format($egr, 2) . "</td>";
            echo "<td>{$m['usuario']}</td>";
            echo "</tr>";
        }
        echo "<tr>
                <th colspan='3'>TOTALES</th>
                <th>" . number_format($t_ing, 2) . "</th>
                <th>" . number_format($t_egr, 2) . "</th>
                <th>SALDO: " . number_format($t_ing - $t_egr, 2) . "</th>
              </tr>";
        echo "</table>";
        exit;
    }
}
