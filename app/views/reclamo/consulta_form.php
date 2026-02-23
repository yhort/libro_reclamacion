<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consultar Reclamo - Tykesoft</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #004a99;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: #003366;
        }
    </style>
</head>

<body>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    <div class="container my-5">
        <div class="text-center mb-4">
            <img src="assets/logo-tykesoft.png" alt="Tykesoft" style="max-width: 180px;">
            <h2 class="mt-3">Consulta de Estado</h2>
            <p class="text-muted">Ingrese los datos proporcionados al registrar su reclamo</p>
        </div>

        <div class="card shadow border-0 mx-auto" style="max-width: 600px;">
            <div class="card-header text-center py-3">
                CONSULTAR RECLAMACIÓN
            </div>

            <div class="card-body p-4">

                <form method="POST" action="index.php?c=reclamo&a=consultar">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Número de Documento</label>
                        <input type="text" name="num_doc" class="form-control form-control-lg"
                            placeholder="Ej: 12345678" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Código de Reclamo</label>
                        <input type="text" name="correlativo" class="form-control form-control-lg"
                            placeholder="Ej: R-2026-0001" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Consultar Estado
                        </button>

                        <a href="index.php?c=reclamo&a=index"
                            class="btn btn-outline-secondary">
                            Volver al formulario
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <footer class="text-center pb-5 text-muted small">
        &copy; 2026 Tykesoft. Todos los derechos reservados.
    </footer>

</body>

</html>