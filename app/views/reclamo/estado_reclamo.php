<?php if (empty($r)): ?>

    <div class="container my-5 text-center">
        <div class="alert alert-danger">
            No se encontró el reclamo. Verifique los datos ingresados.
        </div>
        <a href="index.php?c=reclamo&a=consultar" class="btn btn-outline-primary">
            Intentar nuevamente
        </a>
    </div>

<?php else: ?>

    <div class="container my-5 text-center" style="max-width: 700px;">
        <h2 class="fw-bold mb-4">Estado de su Reclamación</h2>

        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h4 class="text-primary fw-bold"><?= htmlspecialchars($r['correlativo']) ?></h4>

                <div class="my-3">
                    <span class="badge rounded-pill p-3 px-4 <?= $r['estado'] == 'Atendido' ? 'bg-success' : 'bg-warning text-dark' ?>">
                        ESTADO: <?= strtoupper(htmlspecialchars($r['estado'])) ?>
                    </span>
                </div>

                <hr class="my-4">

                <?php if ($r['estado'] == 'Atendido'): ?>
                    <div class="text-start">
                        <h5 class="fw-bold">Respuesta de Tykesoft:</h5>
                        <p class="bg-light p-3 rounded border">
                            <?= nl2br(htmlspecialchars($r['respuesta_negocio'])) ?>
                        </p>
                        <small class="text-muted">
                            Respondido el: <?= date('d/m/Y', strtotime($r['fecha_respuesta'])) ?>
                        </small>
                    </div>
                <?php else: ?>
                    <p class="text-muted">
                        Su reclamo aún se encuentra en revisión.
                    </p>
                <?php endif; ?>

                <div class="mt-5">
                    <a href="index.php?c=reclamo&a=index" class="btn btn-outline-secondary">
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>