<div class="container-fluid pt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-0">Configuración de Galería</h3>
                    <p class="text-muted small">Personaliza la identidad y los datos de facturación de tu sistema</p>
                </div>
                <button type="submit" form="formConfig" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-save me-2"></i> Guardar Cambios
                </button>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> ¡Configuración actualizada correctamente!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form id="formConfig" action="index.php?c=config&a=guardarEmpresa" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="card-title mb-0">Identidad del Negocio</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Nombre Comercial</label>
                                    <input type="text" name="nombre_galeria" class="form-control" value="<?= htmlspecialchars($config['nombre_galeria']) ?>" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">RUC / Identificación Fiscal</label>
                                        <input type="text" name="ruc" class="form-control" value="<?= htmlspecialchars($config['ruc']) ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label small fw-bold">Símbolo de Moneda</label>
                                        <input type="text" name="moneda" class="form-control" value="<?= htmlspecialchars($config['moneda']) ?>" maxlength="5">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Dirección Física</label>
                                    <textarea name="direccion" class="form-control" rows="2"><?= htmlspecialchars($config['direccion']) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 p-3">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="card-title mb-0">Personalización de Tickets</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-0">
                                    <label class="form-label small fw-bold">Mensaje de Pie de Ticket (Cochera/Baños)</label>
                                    <textarea name="mensaje_ticket" class="form-control" rows="4" placeholder="Ej: No nos hacemos responsables por..."><?= htmlspecialchars($config['mensaje_ticket']) ?></textarea>
                                    <div class="form-text mt-2 small text-muted">Este texto aparecerá al final de todos los tickets impresos.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 text-center">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Logo de la Galería</h6>
                                <div class="mb-3">
                                    <div class="mx-auto bg-light rounded-4 d-flex align-items-center justify-content-center border shadow-sm" style="width: 150px; height: 150px; overflow: hidden;">
                                        <?php if ($config['logo_path']): ?>
                                            <img src="<?= htmlspecialchars($config['logo_path']) ?>" class="img-fluid" id="previewLogo">
                                        <?php else: ?>
                                            <i class="bi bi-image text-muted" style="font-size: 3rem;" id="placeholderLogo"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <input type="file" name="logo" id="inputLogo" class="form-control form-control-sm" accept="image/*">
                                <p class="small text-muted mt-2">Recomendado: PNG fondo transparente (512x512px)</p>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 p-3">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="card-title mb-0">Contacto Administrativo</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Teléfono de Soporte</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-telephone"></i></span>
                                        <input type="text" name="telefono" class="form-control border-start-0" value="<?= htmlspecialchars($config['telefono']) ?>">
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small fw-bold">Email de Contacto</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                        <input type="email" name="email_contacto" class="form-control border-start-0" value="<?= htmlspecialchars($config['email_contacto']) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Previsualización del logo antes de subir
    document.getElementById('inputLogo').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            const preview = document.getElementById('previewLogo') || document.createElement('img');
            if (!document.getElementById('previewLogo')) {
                preview.id = 'previewLogo';
                preview.className = 'img-fluid';
                const container = document.getElementById('placeholderLogo').parentElement;
                document.getElementById('placeholderLogo').remove();
                container.appendChild(preview);
            }
            preview.src = URL.createObjectURL(file);
        }
    }
</script>