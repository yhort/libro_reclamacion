<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title ?? 'Libro de Reclamaciones') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Fonts (igual que el admin) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5/index.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1/font/bootstrap-icons.min.css" />

  <style>
    :root{
      --brand:#0b5ed7;          /* azul moderno */
      --brand-2:#0a58ca;
      --bg:#f6f7fb;
      --text:#0f172a;
      --muted:#64748b;
      --card:#ffffff;
      --radius:18px;
    }

    body{
      background: radial-gradient(1200px 600px at 20% 0%, rgba(11,94,215,.10), transparent 60%),
                  radial-gradient(1200px 600px at 80% 0%, rgba(99,102,241,.10), transparent 60%),
                  var(--bg);
      font-family: "Source Sans 3","Inter",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      color: var(--text);
    }

    .public-nav{
      background: rgba(255,255,255,.85);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(15,23,42,.06);
    }

    .brand-badge{
      width:40px;height:40px;border-radius:12px;
      background: linear-gradient(135deg,var(--brand),#2563eb);
      display:flex;align-items:center;justify-content:center;
      color:#fff;
      box-shadow: 0 10px 25px rgba(11,94,215,.25);
      flex: 0 0 auto;
    }

    .brand-title{ font-weight:800; letter-spacing:-.5px; margin:0; line-height:1.1; }
    .brand-sub{ color:var(--muted); font-size:.9rem; margin:0; }

    .public-wrap{
      padding: 28px 0 40px;
    }

    .public-card{
      background: var(--card);
      border: 1px solid rgba(15,23,42,.06);
      border-radius: var(--radius);
      box-shadow: 0 25px 50px -12px rgba(15,23,42,.12);
    }

    .btn-brand{
      background: var(--brand);
      border: none;
      border-radius: 12px;
      padding: .65rem 1rem;
      font-weight: 700;
    }
    .btn-brand:hover{ background: var(--brand-2); }

    .footer{
      color: var(--muted);
      font-size: .9rem;
      padding: 24px 0 32px;
    }
  </style>
</head>

<body>

  <nav class="navbar public-nav py-3">
    <div class="container d-flex align-items-center gap-3">
      <div class="brand-badge"><i class="bi bi-journal-check fs-5"></i></div>

      <div class="me-auto">
        <p class="brand-title">Libro de Reclamaciones Virtual</p>
        <p class="brand-sub">Tykesoft · Atención al consumidor</p>
      </div>

      <a class="btn btn-outline-primary rounded-3 px-3"
         href="index.php?c=reclamo&a=consultar_form">
        <i class="bi bi-search me-2"></i>Consultar estado
      </a>
    </div>
  </nav>

  <main class="public-wrap">
    <div class="container">
      <?= $content ?>
    </div>
  </main>

  <div class="footer text-center">
    &copy; <?= date('Y') ?> Tykesoft. Todos los derechos reservados.
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>