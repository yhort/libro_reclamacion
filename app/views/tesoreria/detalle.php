<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-8">
                <h3 class="fw-bold text-dark mb-0">
                    <i class="bi bi-clock-history text-primary me-2"></i>Historial: Caja <?= htmlspecialchars($caja['modulo']) ?>
                </h3>
                <p class="text-muted small mb-0">Auditoría detallada de flujos de efectivo</p>
            </div>
            <div class="col-sm-4 text-end">
                <a href="index.php?c=tesoreria" class="btn btn-light border rounded-pill px-3 shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Consolidado
                </a>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <form action="index.php" method="GET" class="row g-2 align-items-end">
                            <input type="hidden" name="c" value="tesoreria">
                            <input type="hidden" name="a" value="verDetalle">
                            <input type="hidden" name="id" value="<?= $caja['caja_id'] ?>">

                            <div class="col-sm-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Fecha de consulta</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar3 text-muted"></i></span>
                                    <input type="date" name="fecha" class="form-control border-start-0" value="<?= $fecha ?>">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                                    <i class="bi bi-search me-1"></i> Buscar
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-5 text-md-end mt-3 mt-md-0">
                        <span class="d-block small fw-bold text-muted text-uppercase mb-2">Exportar reporte</span>
                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                            <a href="index.php?c=tesoreria&a=exportarDetalleExcel&id=<?= $caja['caja_id'] ?>&fecha=<?= $fecha ?>" class="btn btn-outline-success border-end-0 px-3">
                                <i class="bi bi-file-earmark-excel"></i> Excel
                            </a>
                            <a href="index.php?c=tesoreria&a=imprimirDetallePDF&id=<?= $caja['caja_id'] ?>&fecha=<?= $fecha ?>" target="_blank" class="btn btn-outline-danger px-3">
                                <i class="bi bi-file-earmark-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Movimientos: <span class="text-primary"><?= date('d/m/Y', strtotime($fecha)) ?></span></h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill"><?= count($movimientos) ?> registros</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary small fw-bold text-uppercase">
                            <tr>
                                <th class="ps-4">Hora</th>
                                <th>Concepto / Motivo</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-end">Monto</th>
                                <th class="pe-4">Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_ing = 0;
                            $total_egr = 0;
                            if (empty($movimientos)):
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-cloud-slash text-muted mb-3" style="font-size: 3rem;"></i>
                                            <p class="text-muted fw-medium">No se registraron movimientos en esta fecha.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($movimientos as $m):
                                    $esIngreso = ($m['tipo'] == 'INGRESO');
                                    if ($esIngreso) $total_ing += $m['monto'];
                                    else $total_egr += $m['monto'];
                                ?>
                                    <tr>
                                        <td class="ps-4 text-muted small">
                                            <i class="bi bi-clock me-1"></i> <?= date('h:i A', strtotime($m['fecha_hora'])) ?>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-medium"><?= htmlspecialchars($m['concepto']) ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill <?= $esIngreso ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> px-3">
                                                <?= $m['tipo'] ?>
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold <?= $esIngreso ? 'text-success' : 'text-danger' ?>">
                                            <?= $esIngreso ? '+' : '-' ?> S/ <?= number_format($m['monto'], 2) ?>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex align-items-center bg-light rounded-pill px-2 py-1 border">
                                                <i class="bi bi-person-circle text-secondary me-1"></i>
                                                <small class="fw-bold text-dark"><?= htmlspecialchars($m['usuario']) ?></small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-white border-top-0 p-4">
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 bg-light">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Total Ingresos:</span>
                                <span class="text-success fw-bold">+ S/ <?= number_format($total_ing, 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Total Gastos:</span>
                                <span class="text-danger fw-bold">- S/ <?= number_format($total_egr, 2) ?></span>
                            </div>
                            <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">Saldo del día:</span>
                                <span class="h4 fw-bold mb-0 text-primary">S/ <?= number_format($total_ing - $total_egr, 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos locales para reforzar el look SaaS */
    .table thead th { border-top: none; }
    .bg-success-subtle { background-color: #d1fae5 !important; color: #065f46 !important; }
    .bg-danger-subtle { background-color: #fee2e2 !important; color: #991b1b !important; }
    .bg-primary-subtle { background-color: #e0ecff !important; color: #2563eb !important; }
    .rounded-4 { border-radius: 1rem !important; }
</style>