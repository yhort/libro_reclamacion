<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Detalle del Reclamo: <?= $r['correlativo'] ?></h2>
        <span class="badge <?= $r['estado'] == 'Pendiente' ? 'bg-danger' : 'bg-success' ?> p-2 px-3">
            Estado: <?= $r['estado'] ?>
        </span>
    </div>
    <a href="index.php?c=dashboard&a=imprimir&id=<?= $r['id'] ?>" target="_blank" class="btn btn-outline-danger shadow-sm">
        <i class="bi bi-file-earmark-pdf me-2"></i>Generar PDF / Imprimir
    </a>
</div>
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-bold text-primary">Datos del Consumidor</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars($r['nombre_completo']) ?></p>
                    <p class="mb-1"><strong><?= $r['tipo_doc'] ?>:</strong> <?= $r['num_doc'] ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?= $r['email'] ?></p>
                    <p class="mb-1"><strong>Teléfono:</strong> <?= $r['telefono'] ?: 'No registrado' ?></p>
                    <p class="mb-0"><strong>Dirección:</strong> <?= htmlspecialchars($r['direccion']) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-bold text-primary">Detalle de la Incidencia</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1"><strong>Tipo:</strong> <?= $r['tipo_incidencia'] ?></p>
                            <p class="mb-1"><strong>Bien:</strong> <?= $r['tipo_bien'] ?></p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Monto:</strong> S/ <?= number_format((float)$r['monto_reclamado'], 2) ?></p>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Descripción del bien:</strong><br> <?= htmlspecialchars($r['descripcion_bien']) ?></p>
                    <p><strong>Queja/Reclamo:</strong><br><?= nl2br(htmlspecialchars($r['detalle_cliente'])) ?></p>
                    <p class="mb-0 text-primary"><strong>Pedido del Cliente:</strong><br><?= nl2br(htmlspecialchars($r['pedido_cliente'])) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white fw-bold">Gestión de Respuesta del Negocio</div>
        <div class="card-body">
            <?php if ($r['estado'] !== 'Atendido'): ?>
                <form action="index.php?c=dashboard&a=responder" method="POST">
                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Acciones adoptadas por el negocio:</label>
                        <textarea name="respuesta_negocio" class="form-control" rows="5" required placeholder="Escriba la solución o respuesta oficial aquí..."></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            <i class="bi bi-send me-2"></i>Enviar Respuesta Oficial
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-success border-0 shadow-sm mb-0">
                    <div class="d-flex justify-content-between mb-2">
                        <strong><i class="bi bi-check-circle-fill me-2"></i>Respuesta enviada el:</strong>
                        <span><?= date('d/m/Y H:i', strtotime($r['fecha_respuesta'])) ?></span>
                    </div>
                    <hr>
                    <p class="mb-0 italic"><?= nl2br(htmlspecialchars($r['respuesta_negocio'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="index.php?c=dashboard" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver al Panel Principal
        </a>
    </div>


</div>