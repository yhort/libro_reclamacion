<?php
$t_ingresos = 0;
$t_egresos = 0;
$saldo_inicial = $saldoAnterior ?? 0;

$resumen_conceptos = [
  'INGRESOS OPERATIVOS' => 0,
  'GASTOS OPERATIVOS' => 0,
  'LIQUIDACIÓN/RETIROS' => 0
];
?>

<style>
  /* Contenedor con scroll interno */
  .table-scroll-container {
    max-height: 450px; /* Ajusta esta altura según prefieras */
    overflow-y: auto;
    border-bottom: 2px solid #dee2e6;
  }

  /* Cabecera fija para no perder los títulos al bajar */
  .table-sticky-header thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa !important;
    z-index: 10;
    box-shadow: inset 0 -1px 0 #dee2e6;
  }

  /* Personalización del scrollbar para que se vea moderno */
  .table-scroll-container::-webkit-scrollbar {
    width: 8px;
  }
  .table-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
  }
  .table-scroll-container::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
  }
  .table-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #999;
  }
</style>

<div class="app-content-header d-print-none">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h3 class="fw-bold text-dark mb-0">Arqueo de Caja General</h3>
        <p class="text-muted small">Consolidado detallado de movimientos y conciliación física.</p>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid pt-2">
  
  <div class="card border-0 shadow-sm mb-4 d-print-none">
    <div class="card-body">
      <form action="index.php" method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="c" value="reportes">
        <div class="col-md-3">
          <label class="form-label small fw-bold text-muted text-uppercase">Fecha de Reporte</label>
          <input type="date" name="fecha" class="form-control border-light bg-light" value="<?= $fecha ?>">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-dark w-100 rounded-pill">
            <i class="bi bi-filter me-1"></i> Filtrar
          </button>
        </div>
        <div class="col-md-7 text-md-end">
          <div class="btn-group shadow-sm rounded-pill overflow-hidden">
            <a href="index.php?c=reportes&a=exportarExcel&fecha=<?= $fecha ?>" class="btn btn-outline-success px-4">
              <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>
            <a href="index.php?c=reportes&a=imprimir&fecha=<?= $fecha ?>" target="_blank" class="btn btn-outline-primary px-4">
              <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card border-0 shadow-sm overflow-hidden mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>Detalle de Movimientos</h5>
        <span class="badge bg-light text-dark border fw-normal">Mostrando registros del día</span>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive table-scroll-container">
        <table class="table table-hover align-middle mb-0 table-sticky-header">
          <thead class="bg-light text-secondary small fw-bold text-uppercase">
            <tr>
              <th class="ps-4">Hora</th>
              <th>Módulo</th>
              <th>Concepto</th>
              <th class="text-end">Ingreso</th>
              <th class="text-end d-none d-md-table-cell">Egreso</th>
              <th class="pe-4 d-none d-md-table-cell text-center">Usuario</th>
            </tr>
          </thead>
          <tbody>
            <tr class="bg-primary-subtle bg-opacity-10" style="position: sticky; top: 35px; z-index: 5; background: #eef4ff;">
              <td class="ps-4 text-primary fw-bold">00:00</td>
              <td><span class="badge bg-primary rounded-pill">SISTEMA</span></td>
              <td class="fw-bold">SALDO INICIAL <span class="text-muted fw-normal">(Arrastre de caja anterior)</span></td>
              <td class="text-end fw-bold text-primary">S/ <?= number_format($saldo_inicial, 2) ?></td>
              <td class="text-end d-none d-md-table-cell">-</td>
              <td class="pe-4 d-none d-md-table-cell text-center text-muted"><small>Automático</small></td>
            </tr>

            <?php if (empty($movimientos)): ?>
              <tr>
                <td colspan="6" class="text-center py-5 text-muted">No se encontraron movimientos en la fecha seleccionada.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($movimientos as $m):
                $esIngreso = ($m['tipo'] == 'INGRESO');
                if ($esIngreso) {
                  $t_ingresos += $m['monto'];
                  $resumen_conceptos['INGRESOS OPERATIVOS'] += $m['monto'];
                } else {
                  $t_egresos += $m['monto'];
                  if (stripos($m['concepto'], 'ENTREGA') !== false || stripos($m['concepto'], 'RETIRO') !== false) {
                    $resumen_conceptos['LIQUIDACIÓN/RETIROS'] += $m['monto'];
                  } else {
                    $resumen_conceptos['GASTOS OPERATIVOS'] += $m['monto'];
                  }
                }
              ?>
                <tr>
                  <td class="ps-4 text-muted small"><?= date('H:i', strtotime($m['fecha_hora'])) ?></td>
                  <td><span class="badge border text-secondary rounded-pill fw-normal px-3"><?= $m['modulo'] ?></span></td>
                  <td class="small"><?= htmlspecialchars($m['concepto']) ?></td>
                  <td class="text-end fw-bold text-success"><?= $esIngreso ? 'S/ ' . number_format($m['monto'], 2) : '-' ?></td>
                  <td class="text-end fw-bold text-danger d-none d-md-table-cell"><?= !$esIngreso ? 'S/ ' . number_format($m['monto'], 2) : '-' ?></td>
                  <td class="pe-4 d-none d-md-table-cell text-center">
                    <div class="d-inline-flex align-items-center bg-light border rounded-pill px-2">
                        <small class="text-dark fw-medium"><?= $m['usuario'] ?></small>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <table class="table mb-0">
        <tfoot class="bg-dark text-white">
            <tr>
              <td class="ps-4 fw-bold py-3" style="width: 40%;">RESUMEN CONSOLIDADO</td>
              <td class="text-end fw-bold text-success-subtle">S/ <?= number_format($t_ingresos, 2) ?></td>
              <td class="text-end fw-bold text-danger-subtle d-none d-md-table-cell">S/ <?= number_format($t_egresos, 2) ?></td>
              <td class="pe-4 text-end">
                <span class="small text-white-50">FINAL:</span>
                <span class="h5 fw-bold ms-2 mb-0 text-white">S/ <?= number_format(($saldo_inicial + $t_ingresos) - $t_egresos, 2) ?></span>
              </td>
            </tr>
          </tfoot>
      </table>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white py-3 border-bottom">
          <h6 class="card-title mb-0 fw-bold text-primary text-uppercase small">Flujo Neto del Día</h6>
        </div>
        <div class="card-body px-0 py-2">
          <table class="table table-borderless mb-0">
            <tr class="border-bottom border-light">
              <td class="ps-4 py-3">Total Recaudado (Ingresos)</td>
              <td class="pe-4 py-3 text-end text-success fw-bold">+ S/ <?= number_format($resumen_conceptos['INGRESOS OPERATIVOS'], 2) ?></td>
            </tr>
            <tr class="border-bottom border-light">
              <td class="ps-4 py-3">Gastos Operativos (Pagos/Compras)</td>
              <td class="pe-4 py-3 text-end text-danger fw-bold">- S/ <?= number_format($resumen_conceptos['GASTOS OPERATIVOS'], 2) ?></td>
            </tr>
            <tr class="bg-light">
              <td class="ps-4 py-3 fw-bold text-dark">BALANCE GENERADO:</td>
              <td class="pe-4 py-3 text-end fw-bold text-dark fs-5">S/ <?= number_format($resumen_conceptos['INGRESOS OPERATIVOS'] - $resumen_conceptos['GASTOS OPERATIVOS'], 2) ?></td>
            </tr>
            <tr>
              <td class="ps-4 py-3 text-primary">Retiros/Entregas a Administración</td>
              <td class="pe-4 py-3 text-end text-primary fw-bold">S/ <?= number_format($resumen_conceptos['LIQUIDACIÓN/RETIROS'], 2) ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-0 shadow-lg bg-dark text-white h-100">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-4 d-flex align-items-center">
            <i class="bi bi-safe2 me-2 text-warning"></i> CONCILIACIÓN FÍSICA
          </h5>
          <div class="d-flex justify-content-between mb-3 text-white-50">
            <span>Saldo Inicial (Caja Anterior)</span>
            <span class="text-white fw-bold">S/ <?= number_format($saldo_inicial, 2) ?></span>
          </div>
          <div class="d-flex justify-content-between mb-3 text-white-50">
            <span>(+) Total Ingresos del Día</span>
            <span class="text-success fw-bold">S/ <?= number_format($t_ingresos, 2) ?></span>
          </div>
          <div class="d-flex justify-content-between mb-3 text-white-50">
            <span>(-) Total Egresos (Gastos + Retiros)</span>
            <span class="text-danger fw-bold">S/ <?= number_format($t_egresos, 2) ?></span>
          </div>
          <hr class="my-4 border-secondary">
          <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0">TOTAL EN CAJA</h4>
                <small class="text-warning">Debe estar físicamente en gaveta</small>
            </div>
            <h2 class="fw-bold text-white mb-0">S/ <?= number_format(($saldo_inicial + $t_ingresos) - $t_egresos, 2) ?></h2>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>