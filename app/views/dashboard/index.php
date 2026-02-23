<div class="app-content-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="fw-bold text-dark mb-0">Gestión de Reclamaciones</h3>
                <p class="text-muted small">Resumen del estado de quejas y reclamos de Tykesoft.</p>
            </div>
            <div class="col-sm-6 text-end">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> <?= date('d M, Y') ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid pt-2">
    <div class="row">
        <div class="col-lg-3 col-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="bg-primary-subtle text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase">Total Recibidos</h6>
                    <h2 class="fw-bold mb-0"><?= $stats['total'] ?></h2>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="bg-info-subtle text-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase">Solo Reclamos</h6>
                    <h2 class="fw-bold mb-0"><?= $stats['solo_reclamos'] ?></h2>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6 mb-4">
            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-person-badge fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase">Solo Quejas</h6>
                    <h2 class="fw-bold mb-0"><?= $stats['solo_quejas'] ?></h2>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6 mb-4">
            <div class="card border-0 shadow-sm h-100 border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="bg-danger-subtle text-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted small fw-bold text-uppercase text-danger">Por Responder</h6>
                    <h2 class="fw-bold mb-0 text-danger"><?= $stats['pendientes'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Incidencias registradas por Mes</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:350px;">
                        <canvas id="complaints-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Últimos Reclamos Recibidos</h5>
                    <a href="index.php?c=reclamo&a=admin" class="btn btn-sm btn-primary rounded-pill px-3">Ver todos</a>
                </div>
                <form method="GET" action="index.php" class="card card-body mb-4 shadow-sm">
                    <input type="hidden" name="c" value="dashboard">

                    <div class="row g-2">
                        <div class="col-md-2">
                            <input type="text" name="num_doc" class="form-control"
                                value="<?= htmlspecialchars($_GET['num_doc'] ?? '') ?>"
                                placeholder="DNI">
                        </div>

                        <div class="col-md-2">
                            <input type="text" name="correlativo" class="form-control" placeholder="Correlativo">
                        </div>

                        <div class="col-md-2">
                            <select name="estado" class="form-select">
                                <option value="">Estado</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="En Revision">En Revisión</option>
                                <option value="Atendido">Atendido</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="tipo" class="form-select">
                                <option value="">Tipo</option>
                                <option value="Reclamo">Reclamo</option>
                                <option value="Queja">Queja</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <input type="date" name="desde" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <input type="date" name="hasta" class="form-control">
                        </div>

                        <div class="col-md-12 text-end mt-2">
                            <button type="submit" class="btn btn-primary">
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Código</th>
                                    <th>Cliente</th>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th class="text-end pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reclamos as $r): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary"><?= $r['correlativo'] ?></td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($r['nombre_completo']) ?></div>
                                            <small class="text-muted"><?= $r['email'] ?></small>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill <?= $r['tipo_incidencia'] == 'Reclamo' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' ?> border">
                                                <?= $r['tipo_incidencia'] ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($r['fecha_registro'])) ?></td>
                                        <td class="text-end pe-4">
                                            <a href="index.php?c=dashboard&a=ver&id=<?= $r['id'] ?>" class="btn btn-sm btn-light border shadow-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($reclamos)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No se encontraron registros.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if ($totalPaginas > 1): ?>
                            <nav class="mt-4">
                                <ul class="pagination justify-content-center">

                                    <?php
                                    // Mantener filtros en la URL
                                    $queryParams = $_GET;
                                    ?>

                                    <!-- Botón Anterior -->
                                    <?php if ($pagina > 1): ?>
                                        <?php $queryParams['page'] = $pagina - 1; ?>
                                        <li class="page-item">
                                            <a class="page-link" href="index.php?<?= http_build_query($queryParams) ?>">Anterior</a>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Números -->
                                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                        <?php $queryParams['page'] = $i; ?>
                                        <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                                            <a class="page-link" href="index.php?<?= http_build_query($queryParams) ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <!-- Botón Siguiente -->
                                    <?php if ($pagina < $totalPaginas): ?>
                                        <?php $queryParams['page'] = $pagina + 1; ?>
                                        <li class="page-item">
                                            <a class="page-link" href="index.php?<?= http_build_query($queryParams) ?>">Siguiente</a>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-primary mt-5">
        <div class="card-body p-4 text-center">
            <h5 class="fw-bold"><i class="bi bi-search me-2"></i>¿Ya registraste un reclamo?</h5>
            <p class="text-muted small">Ingresa tu número de documento y el código de reclamo para ver el estado.</p>
            <form action="index.php?c=reclamo&a=consultar" method="POST" class="row g-2 justify-content-center">
                <div class="col-md-4">
                    <input type="text" name="num_doc" class="form-control" placeholder="DNI / RUC" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="correlativo" class="form-control" placeholder="Ej: R-2026-0001" required>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary px-4">Consultar Estado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('complaints-chart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $labels ?>, // Quita el json_encode aquí, ya viene listo del controlador
                datasets: [{
                    label: 'Cantidad de Reclamaciones',
                    data: <?= $valores ?>, // Quita el json_encode aquí también
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>