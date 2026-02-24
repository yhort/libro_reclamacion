<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8 col-xl-6">

        <div class="public-card p-4 p-md-5 text-center">
            <div class="mb-3" style="font-size:3.5rem; color:#16a34a;">
                <i class="bi bi-check2-circle"></i>
            </div>

            <h2 class="fw-bold mb-2">¡Reclamo registrado!</h2>
            <p class="text-muted mb-4">
                Su hoja de reclamación se envió correctamente. Guarde su código para futuras consultas.
            </p>

            <div class="border rounded-4 p-3 mb-4"
                style="border-style:dashed !important; border-width:2px !important;">
                <div class="text-muted small">Código de seguimiento</div>
                <div class="fs-3 fw-bold text-primary">
                    <?= htmlspecialchars($correlativo) ?>
                </div>
            </div>

            <p class="small text-muted mb-4">
                Enviamos una copia a su correo. El plazo máximo de respuesta es de 15 días hábiles.
            </p>

            <div class="d-grid gap-2 d-sm-flex justify-content-center">
                <button type="button" onclick="window.print()" class="btn btn-outline-secondary rounded-3 px-4">
                    <i class="bi bi-printer me-2"></i>Imprimir cargo
                </button>

                <a href="index.php?c=reclamo&a=index" class="btn btn-brand rounded-3 px-4">
                    <i class="bi bi-house me-2"></i>Volver al inicio
                </a>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {

        .public-nav,
        .footer,
        .btn,
        a {
            display: none !important;
        }
    }
</style>