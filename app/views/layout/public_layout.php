<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Libro de Reclamaciones') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --brand: #004a99;
            --brand-2: #0b63c7;
            --bg: #f4f7f6;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --border: #e5e7eb;
            --radius: 18px;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
        }

        .public-header {
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            color: #fff;
        }

        .brand-badge {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .25);
            backdrop-filter: blur(8px);
        }

        .public-shell {
            max-width: 980px;
            margin: 0 auto;
            padding: 22px 14px;
        }

        .public-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 18px 40px rgba(15, 23, 42, .08);
        }

        .btn-brand {
            background: #fff;
            color: var(--brand);
            border: 0;
            font-weight: 700;
            border-radius: 999px;
            padding: .6rem 1.0rem;
        }

        .btn-brand:hover {
            opacity: .95;
        }

        .btn-primary {
            background: var(--brand);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            padding: .75rem 1rem;
        }

        .btn-primary:hover {
            background: #003b7a;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border-color: var(--border);
            padding: .75rem .9rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(0, 74, 153, .35);
            box-shadow: 0 0 0 .25rem rgba(0, 74, 153, .12);
        }

        .muted {
            color: var(--muted);
        }

        footer {
            color: var(--muted);
        }
    </style>
</head>

<body>

    <header class="public-header">
        <div class="public-shell">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <div class="brand-badge">
                        <!-- icon -->
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M4 7.5L12 3l8 4.5V21a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7.5Z" stroke="white" stroke-width="1.8" />
                            <path d="M9 22V12h6v10" stroke="white" stroke-width="1.8" />
                        </svg>
                    </div>

                    <div>
                        <div class="fw-bold" style="font-size:1.05rem; letter-spacing:-.2px;">Libro de Reclamaciones Virtual</div>
                        <div class="opacity-75" style="font-size:.9rem;">Tykesoft • Atención al consumidor</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a class="btn btn-brand" href="index.php?c=reclamo&a=consultar_form">Consultar estado</a>
                </div>
            </div>
        </div>
    </header>

    <main class="public-shell">
        <div class="public-card p-3 p-md-4">
            <?= $content ?>
        </div>

        <div class="text-center mt-4">
            <div class="small muted">
                Conforme al Código de Protección y Defensa del Consumidor • Plazo de respuesta máximo: 15 días hábiles
            </div>
        </div>
    </main>

    <footer class="text-center py-4 small">
        &copy; <?= date('Y') ?> Tykesoft. Todos los derechos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>