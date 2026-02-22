<?php

/** @var string|null $pageTitle */
$title = $pageTitle ?? 'Galería';
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <title><?= htmlspecialchars($title) ?> | Galería</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5/index.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2/styles/overlayscrollbars.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="<?= htmlspecialchars(asset('adminlte/css/adminlte.min.css')) ?>" />
  <link rel="stylesheet" href="<?= htmlspecialchars(asset('adminlte/css/custom.css')) ?>" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">



<!--   <style>
    /* Fuerza a que los submenús se apilen verticalmente y no horizontalmente */
    .nav-sidebar .nav-treeview {
      display: none;
      /* Se ocultará por defecto */
      list-style: none;
      padding-left: 1rem;
      flex-direction: column !important;
      /* BLOQUEA EL MODO HORIZONTAL */
      width: 100%;
    }

    .nav-sidebar .nav-treeview.show,
    .nav-sidebar .nav-treeview.collapsing {
      display: flex !important;
    }

    .nav-sidebar .nav-treeview .nav-item {
      width: 100%;
      /* Cada link ocupará todo el ancho */
      display: block !important;
    }
  </style> -->
</head>

<body class="layout-fixed fixed-header fixed-footer sidebar-expand-lg sidebar-open">

  <div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body">
      <div class="container-fluid">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="bi bi-list"></i></a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a href="<?= htmlspecialchars(BASE_URL) ?>/index.php" class="nav-link">Inicio</a>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item d-none d-md-flex align-items-center">
            <span class="nav-link user-info">
              <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
              <?php if (!empty($_SESSION['rol'])): ?>
                <span class="badge badge-role"><?= htmlspecialchars($_SESSION['rol']) ?></span>
              <?php endif; ?>
            </span>

          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= htmlspecialchars(BASE_URL) ?>/logout.php" title="Salir">
              <i class="bi bi-box-arrow-right"></i>
            </a>
          </li>
        </ul>
      </div>
    </nav>