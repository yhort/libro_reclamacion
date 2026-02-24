<?php
$estado = strtoupper(trim((string)($r['estado'] ?? 'PENDIENTE')));

$badgeClass = 'bg-warning text-dark';
$icon = 'bi-hourglass-split';
$mensaje = 'Su reclamo aún se encuentra en revisión.';

if ($estado === 'ATENDIDO') {
    $badgeClass = 'bg-success';
    $icon = 'bi-check2-circle';
    $mensaje = 'Su reclamo ha sido atendido. Revise la respuesta.';
} elseif ($estado === 'EN REVISION' || $estado === 'EN REVISIÓN') {
    $badgeClass = 'bg-info';
    $icon = 'bi-search';
    $mensaje = 'Su reclamo está siendo revisado por nuestro equipo.';
} elseif ($estado === 'PENDIENTE') {
    $badgeClass = 'bg-warning text-dark';
    $icon = 'bi-clock-history';
    $mensaje = 'Su reclamo fue recibido y está pendiente de revisión.';
}
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8 col-xl-7">

        <div class="public-card p-4 p-md-5">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <h3 class="fw-bold mb-1">Estado de su reclamación</h3>
                    <div class="text-muted">
                        Código:
                        <span class="fw-semibold text-primary"><?= htmlspecialchars($r['correlativo'] ?? '') ?></span>
                    </div>
                </div>

                <span class="badge rounded-pill px-3 py-2 <?= $badgeClass ?>">
                    <i class="bi <?= $icon ?> me-1"></i> <?= htmlspecialchars($estado) ?>
                </span>
            </div>

            <div class="alert alert-light border mt-4 mb-4 d-flex gap-2">
                <i class="bi bi-info-circle-fill mt-1 text-primary"></i>
                <div class="text-muted"><?= htmlspecialchars($mensaje) ?></div>
            </div>

            <?php if ($estado === 'ATENDIDO'): ?>
                <div class="mb-3">
                    <div class="fw-semibold mb-2">Respuesta del negocio</div>
                    <div class="p-3 bg-body-tertiary border rounded-4">
                        <?= nl2br(htmlspecialchars((string)($r['respuesta_negocio'] ?? ''))) ?>
                    </div>
                    <?php if (!empty($r['fecha_respuesta'])): ?>
                        <div class="small text-muted mt-2">
                            Respondido el: <?= date('d/m/Y', strtotime((string)$r['fecha_respuesta'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="small text-muted">Titular</div>
                        <div class="fw-semibold"><?= htmlspecialchars((string)($r['nombre_completo'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Documento</div>
                        <div class="fw-semibold"><?= htmlspecialchars((string)($r['num_doc'] ?? '')) ?></div>
                    </div>

                    <div class="col-md-6">
                        <div class="small text-muted">Tipo</div>
                        <div class="fw-semibold"><?= htmlspecialchars((string)($r['tipo_incidencia'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted">Registrado</div>
                        <div class="fw-semibold">
                            <?= !empty($r['fecha_registro']) ? date('d/m/Y', strtotime((string)$r['fecha_registro'])) : '-' ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <hr class="my-4">

            <div class="d-grid gap-2 d-sm-flex justify-content-between">
                <a href="index.php?c=reclamo&a=consultar_form" class="btn btn-outline-secondary rounded-3 px-4">
                    <i class="bi bi-arrow-left me-2"></i>Otra consulta
                </a>
                <a href="index.php?c=reclamo&a=index" class="btn btn-brand rounded-3 px-4">
                    <i class="bi bi-house me-2"></i>Volver al inicio
                </a>
            </div>
        </div>

    </div>
</div>