<?php if (empty($r)): ?>
    <div class="text-center">
        <div class="alert alert-danger border-0">
            No se encontró el reclamo. Verifique los datos ingresados.
        </div>
        <a href="index.php?c=reclamo&a=consultar_form" class="btn btn-primary px-4">
            Intentar nuevamente
        </a>
    </div>
<?php else: ?>

    <?php
    $estado = (string)($r['estado'] ?? 'Pendiente');
    $estadoUpper = strtoupper($estado);

    $badge = 'text-bg-warning';
    if (strcasecmp($estado, 'Atendido') === 0) $badge = 'text-bg-success';
    if (stripos($estado, 'Revision') !== false) $badge = 'text-bg-primary';
    ?>

    <div class="text-center mb-4">
        <h2 class="fw-bold mb-1">Estado de tu reclamación</h2>
        <div class="muted">Guarda tu código para futuras consultas.</div>
    </div>

    <div class="p-3 p-md-4" style="border:1px solid #e5e7eb;border-radius:18px;background:#fff;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <div class="muted small mb-1">Código</div>
                <div class="fw-bold" style="font-size:1.35rem;">
                    <?= htmlspecialchars($r['correlativo']) ?>
                </div>
            </div>

            <div class="text-md-end">
                <div class="muted small mb-1">Estado</div>
                <span class="badge rounded-pill <?= $badge ?> px-3 py-2" style="font-size:.95rem;">
                    <?= htmlspecialchars($estadoUpper) ?>
                </span>
            </div>
        </div>

        <hr class="my-4">

        <?php if (strcasecmp($estado, 'Atendido') === 0): ?>
            <div class="text-start">
                <div class="fw-bold mb-2">Respuesta de Tykesoft</div>
                <div class="p-3" style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:14px;">
                    <?= nl2br(htmlspecialchars((string)($r['respuesta_negocio'] ?? ''))) ?>
                </div>
                <?php if (!empty($r['fecha_respuesta'])): ?>
                    <div class="muted small mt-2">
                        Respondido el: <?= date('d/m/Y', strtotime($r['fecha_respuesta'])) ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="muted">
                Tu reclamo aún se encuentra en revisión. Te notificaremos cuando haya una respuesta.
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-end mt-4">
            <a href="index.php?c=reclamo&a=index" class="btn btn-outline-secondary px-4" style="border-radius:12px;">
                Volver
            </a>
        </div>
    </div>

<?php endif; ?>