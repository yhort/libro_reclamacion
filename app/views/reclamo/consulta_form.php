<?php
// consultar_form.php
$errorMsg = $error ?? null;
?>

<div class="text-center mb-4">
  <h2 class="fw-bold mb-1">Consultar estado</h2>
  <div class="muted">Ingresa tu documento y tu código de reclamo.</div>
</div>

<?php if (!empty($errorMsg)): ?>
  <div class="alert alert-danger border-0">
    <?= htmlspecialchars($errorMsg) ?>
  </div>
<?php endif; ?>

<form method="POST" action="index.php?c=reclamo&a=consultar" class="row g-3">
  <div class="col-md-6">
    <label class="form-label fw-semibold">Número de Documento *</label>
    <input type="text" name="num_doc" class="form-control" required>
  </div>

  <div class="col-md-6">
    <label class="form-label fw-semibold">Código de Reclamo *</label>
    <input type="text" name="correlativo" class="form-control" placeholder="Ej: R-2026-0001" required>
  </div>

  <div class="col-12 d-flex gap-2 justify-content-end mt-2">
    <a href="index.php?c=reclamo&a=index" class="btn btn-outline-secondary px-4" style="border-radius:12px;">
      Volver
    </a>
    <button type="submit" class="btn btn-primary px-5">Consultar</button>
  </div>
</form>