<div class="app-content-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h3 class="fw-bold text-dark mb-0">Consolidado de Tesorería</h3>
        <p class="text-muted small">Supervisión de flujos de efectivo y estados de caja.</p>
      </div>
      <div class="col-sm-6 text-end">
        <button class="btn btn-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAjuste">
          <i class="bi bi-plus-slash-minus me-1"></i> Ajuste Manual
        </button>
      </div>
    </div>
  </div>
</div>

<div class="app-content">
  <div class="container-fluid">
    
    <div class="row mb-4">
      <?php foreach ($cajas as $caja): ?>
        <div class="col-lg-3 col-6 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-muted small fw-bold text-uppercase mb-1">Caja <?= htmlspecialchars($caja['modulo']) ?></p>
                  <h3 class="fw-bold mb-0">S/ <?= number_format($caja['saldo_actual'], 2) ?></h3>
                </div>
                <div class="bg-light rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                  <i class="bi <?= $caja['modulo'] === 'BANOS' ? 'bi-droplet text-info' : ($caja['modulo'] === 'COCHERA' ? 'bi-car-front text-primary' : 'bi-shop text-warning') ?> fs-4"></i>
                </div>
              </div>
              <div class="mt-3">
                <span class="badge <?= $caja['ya_cerrado'] ? 'bg-secondary-subtle text-secondary' : 'bg-success-subtle text-success' ?> rounded-pill small">
                  <i class="bi bi-circle-fill me-1 small"></i> <?= $caja['ya_cerrado'] ? 'Cerrado' : 'Operando' ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="col-lg-3 col-6 mb-3">
        <div class="card border-0 shadow-sm h-100 bg-dark text-white">
          <div class="card-body">
            <p class="text-white-50 small fw-bold text-uppercase mb-1">Total en Galería</p>
            <h3 class="fw-bold mb-0 text-white">S/ <?= number_format(array_sum(array_column($cajas, 'saldo_actual')), 2) ?></h3>
            <div class="mt-3">
              <small class="text-white-50"><i class="bi bi-cash-coin me-1"></i> Balance Consolidado</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 fw-bold">Control de Cierres Diarios - <span class="text-muted fw-normal"><?= date('d/m/Y') ?></span></h5>
      </div>
      <div class="card-body p-0 text-nowrap">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary small fw-bold text-uppercase">
              <tr>
                <th class="ps-4">Módulo</th>
                <th>Ingresos</th>
                <th>Gastos</th>
                <th>Saldo Actual</th>
                <th class="text-center">Estado</th>
                <th class="text-center">Historial</th>
                <th class="text-end pe-4">Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cajas as $caja): ?>
                <tr>
                  <td class="ps-4"><b><?= htmlspecialchars($caja['modulo']) ?></b></td>
                  <td class="text-success fw-medium">+ S/ <?= number_format($caja['ingresos_dia'] ?? 0, 2) ?></td>
                  <td class="text-danger fw-medium">- S/ <?= number_format($caja['gastos_dia'] ?? 0, 2) ?></td>
                  <td><strong class="text-dark">S/ <?= number_format($caja['saldo_actual'], 2) ?></strong></td>
                  <td class="text-center">
                    <?php if ($caja['ya_cerrado']): ?>
                      <span class="badge bg-light text-secondary border px-3"><i class="bi bi-lock-fill me-1"></i> CERRADO</span>
                    <?php else: ?>
                      <span class="badge bg-success-subtle text-success border border-success-subtle px-3"><i class="bi bi-unlock-fill me-1"></i> ABIERTO</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <a href="index.php?c=tesoreria&a=verDetalle&id=<?= $caja['caja_id'] ?>"
                      class="btn btn-sm btn-light border rounded-pill px-3">
                      <i class="bi bi-eye"></i> Movimientos
                    </a>
                  </td>
                  <td class="text-end pe-4">
                    <?php if (!$caja['ya_cerrado']): ?>
                      <button class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" onclick="prepararCierre(<?= $caja['caja_id'] ?>, <?= $caja['saldo_actual'] ?>)">
                        Cerrar Caja
                      </button>
                    <?php else: ?>
                      <button class="btn btn-sm btn-outline-warning rounded-pill px-3" onclick="solicitarReapertura(<?= $caja['caja_id'] ?>)">
                        Solicitar Reapertura
                      </button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAjuste" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="index.php?c=tesoreria&a=guardarAjuste" method="POST" class="modal-content border-0 shadow">
      <div class="modal-header border-bottom-0 pt-4 px-4">
        <h5 class="fw-bold">Registrar Ajuste de Caja</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4">
        <div class="mb-3">
          <label class="form-label small fw-bold text-muted">Caja a afectar</label>
          <select name="caja_id" class="form-select border-light bg-light" required>
            <?php foreach ($cajas as $caja): ?>
              <?php if (!$caja['ya_cerrado']): ?>
                <option value="<?= $caja['caja_id'] ?>"><?= $caja['modulo'] ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold text-muted">Tipo</label>
                <select name="tipo" class="form-select border-light bg-light" required>
                    <option value="INGRESO">Ingreso (+)</option>
                    <option value="GASTO">Gasto (-)</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label small fw-bold text-muted">Monto (S/)</label>
                <input type="number" name="monto" step="0.01" class="form-control border-light bg-light" placeholder="0.00" required>
            </div>
        </div>
        <div class="mb-3">
          <label class="form-label small fw-bold text-muted">Motivo (Obligatorio)</label>
          <textarea name="motivo" class="form-control border-light bg-light" rows="3" required></textarea>
        </div>
      </div>
      <div class="modal-footer border-top-0 pb-4 px-4">
        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Guardar Ajuste</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalReapertura" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="index.php?c=tesoreria&a=procesarReapertura" method="POST" class="modal-content border-0 shadow">
      <div class="modal-header border-bottom-0 pt-4 px-4">
        <h5 class="modal-title fw-bold text-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i>Autorizar Reapertura</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4">
        <input type="hidden" name="caja_id" id="reapertura_caja_id">
        <div class="mb-3">
          <label class="form-label small fw-bold text-muted">Motivo de Reapertura</label>
          <textarea name="motivo" class="form-control border-light bg-light" required placeholder="Indique por qué se reabre la caja..."></textarea>
        </div>
      </div>
      <div class="modal-footer border-top-0 pb-4 px-4">
        <button type="submit" class="btn btn-warning w-100 py-2 rounded-pill fw-bold">Confirmar Reapertura</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalCierreEfectivo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="index.php?c=tesoreria&a=procesarCierre" method="POST" class="modal-content border-0 shadow">
      <div class="modal-header border-bottom-0 pt-4 px-4">
        <h5 class="modal-title fw-bold text-danger"><i class="bi bi-lock-fill me-2"></i>Cierre y Entrega</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body px-4">
        <input type="hidden" name="caja_id" id="cierre_id_input">
        <div class="p-3 rounded-3 bg-light border-start border-info border-4 mb-4">
          <span class="text-muted small d-block">Saldo contable en sistema:</span>
          <strong class="fs-4 text-dark" id="txt_saldo_sistema"></strong>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold small text-muted text-uppercase">Monto a Retirar para Administración:</label>
          <div class="input-group input-group-lg">
            <span class="input-group-text border-0 bg-light text-muted">S/</span>
            <input type="number" name="monto_retiro" id="monto_retiro" step="0.01" class="form-control border-0 bg-light fw-bold" required>
          </div>
          <p class="small text-muted mt-2">Sugerencia: Retire el total o deje una base para el siguiente turno.</p>
        </div>
        <div class="mb-1">
          <label class="form-label small fw-bold text-muted">Comentario Adicional</label>
          <textarea name="comentario" class="form-control border-light bg-light" rows="2"></textarea>
        </div>
      </div>
      <div class="modal-footer border-top-0 pb-4 px-4">
        <button type="submit" class="btn btn-danger w-100 py-3 rounded-pill fw-bold shadow-sm">FINALIZAR DÍA Y REGISTRAR CIERRE</button>
      </div>
    </form>
  </div>
</div>

<script>
  function prepararCierre(id, saldo) {
    document.getElementById('cierre_id_input').value = id;
    document.getElementById('txt_saldo_sistema').innerText = 'S/ ' + saldo.toLocaleString('en-US', {
      minimumFractionDigits: 2
    });
    document.getElementById('monto_retiro').value = saldo;
    var myModal = new bootstrap.Modal(document.getElementById('modalCierreEfectivo'));
    myModal.show();
  }

  function solicitarReapertura(id) {
    document.getElementById('reapertura_caja_id').value = id;
    var myModal = new bootstrap.Modal(document.getElementById('modalReapertura'));
    myModal.show();
  }
</script>