<div class="row justify-content-center">
  <div class="col-12 col-md-10 col-lg-7 col-xl-6">

    <div class="public-card p-4 p-md-5">
      <div class="d-flex align-items-start gap-3 mb-3">
        <div class="rounded-4 d-flex align-items-center justify-content-center"
          style="width:46px;height:46px;background:rgba(13,110,253,.10);color:#0d6efd;">
          <i class="bi bi-search fs-4"></i>
        </div>
        <div>
          <h3 class="fw-bold mb-1">Consultar estado</h3>
          <p class="text-muted mb-0">Ingresa tu documento y el código (Ej: <b>R-2026-0001</b>).</p>
        </div>
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
          <i class="bi bi-exclamation-triangle-fill mt-1"></i>
          <div>
            <div class="fw-semibold">No se pudo encontrar el reclamo</div>
            <div class="small"><?= htmlspecialchars($error) ?></div>
          </div>
        </div>
      <?php endif; ?>

      <form method="POST" action="index.php?c=reclamo&a=consultar" class="mt-3">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold">Número de documento</label>
            <input type="text" name="num_doc" class="form-control form-control-lg"
              placeholder="DNI / CE / RUC" required>
            <div class="form-text">Sin puntos ni espacios. Ej: 70223344</div>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Código de reclamo</label>
            <input type="text" name="correlativo" class="form-control form-control-lg text-uppercase"
              placeholder="R-2026-0001" required>
            <div class="form-text">Respeta guiones. Ej: R-2026-0006</div>
          </div>

          <div class="col-12 d-grid mt-2">
            <button type="submit" class="btn btn-brand btn-lg">
              <i class="bi bi-arrow-right-circle me-2"></i>Consultar
            </button>
          </div>

          <div class="col-12 text-center">
            <a href="index.php?c=reclamo&a=index" class="btn btn-link text-decoration-none">
              <i class="bi bi-arrow-left me-1"></i>Volver al formulario
            </a>
          </div>
        </div>
      </form>

      <hr class="my-4">

      <div class="small text-muted">
        <i class="bi bi-shield-check me-1"></i>
        Tu información se usa únicamente para la atención del reclamo.
      </div>
    </div>

  </div>
</div>