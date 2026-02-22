<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Gestión de Personal</h3>
            <p class="text-muted small">Administra los accesos y roles del sistema</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="nuevoUsuario()">
            <i class="bi bi-person-plus-fill me-2"></i> Nuevo Usuario
        </button>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'email_duplicado'): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Error: El correo electrónico ya está registrado por otro usuario.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small fw-bold text-uppercase">
                    <tr>
                        <th class="ps-4">Usuario / Email</th>
                        <th>Rol</th>
                        <th>Auditoría</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3"><?= strtoupper($u['username'][0]) ?></div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($u['username']) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($u['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3">
                                    <?= $u['rol'] ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <div><i class="bi bi-plus-circle me-1"></i> <?= $u['creador_nombre'] ?? 'Sistema' ?></div>
                                <?php if ($u['editado_por']): ?>
                                    <div><i class="bi bi-pencil-square me-1"></i> <?= $u['editor_nombre'] ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill <?= $u['activo'] ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $u['activo'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                    onclick="editarUsuario(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)">
                                    <i class="bi bi-pencil me-1"></i> Editar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitulo">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="index.php?c=usuario&a=guardar" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="user_id">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre de Usuario</label>
                        <input type="text" name="username" id="user_username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Correo Electrónico</label>
                        <input type="email" name="email" id="user_email" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Rol de Acceso</label>
                            <select name="rol" id="user_rol" class="form-select" required>
                                <option value="ADMIN">ADMIN</option>
                                <option value="TESORERIA">TESORERIA</option>
                                <option value="COCHERA">COCHERA</option>
                                <option value="BANOS">BANOS</option>
                                <option value="ALQUILERES">ALQUILERES</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Estado</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="activo" id="user_activo" checked>
                                <label class="form-check-label" for="user_activo">Usuario Activo</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold" id="labelPassword">Contraseña</label>
                        <input type="password" name="password" id="user_password" class="form-control" placeholder="Mínimo 6 caracteres">
                        <div id="passHelp" class="form-text small" style="display:none;">
                            Deja en blanco para mantener la contraseña actual.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Usamos un evento para asegurar que el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializamos el modal
        const elModal = document.getElementById('modalUsuario');
        const modalWrap = new bootstrap.Modal(elModal);

        // Hacer las funciones globales para que los botones las vean
        window.nuevoUsuario = function() {
            document.getElementById('modalTitulo').innerText = "Nuevo Usuario";
            document.getElementById('user_id').value = "";
            document.getElementById('user_username').value = "";
            document.getElementById('user_email').value = "";
            document.getElementById('user_rol').value = "COCHERA";
            document.getElementById('user_activo').checked = true;
            document.getElementById('user_password').required = true;
            document.getElementById('passHelp').style.display = "none";
            document.getElementById('labelPassword').innerText = "Contraseña";
            modalWrap.show();
        };

        window.editarUsuario = function(data) {
            document.getElementById('modalTitulo').innerText = "Editar Usuario: " + data.username;
            document.getElementById('user_id').value = data.usuario_id;
            document.getElementById('user_username').value = data.username;
            document.getElementById('user_email').value = data.email;
            document.getElementById('user_rol').value = data.rol;
            document.getElementById('user_activo').checked = (data.activo == 1);

            document.getElementById('user_password').required = false;
            document.getElementById('user_password').value = "";
            document.getElementById('passHelp').style.display = "block";
            document.getElementById('labelPassword').innerText = "Cambiar Contraseña (Opcional)";

            modalWrap.show();
        };
    });
</script>