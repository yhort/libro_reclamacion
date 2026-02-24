<?php
$rol = $_SESSION['rol'] ?? '';

//aside class data-bs-theme="dark"


?>

<aside class="app-sidebar sidebar-saas shadow-sm" data-bs-theme="light" style="background: #ffffff; border-right: 1px solid #f0f0f0;">
  <div class="sidebar-brand border-bottom mb-2">
    <a href="<?= htmlspecialchars(BASE_URL) ?>/index.php" class="brand-link py-3">
      <div class="bg-primary shadow-sm rounded-3 d-inline-flex align-items-center justify-content-center me-2" style="width:34px; height:34px;">
        <i class="bi bi-buildings-fill text-white small"></i>
      </div>
      <span class="brand-text fw-bold text-dark" style="letter-spacing: -0.5px;">Lib<span class="text-primary">Reclamacion</span></span>
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2 px-2">
      <ul class="nav nav-pills nav-sidebar flex-column gap-1" data-lte-toggle="treeview" role="menu" data-accordion="false">
        
        <li class="nav-item">
          <a href="<?= htmlspecialchars(BASE_URL) ?>/index.php" class="nav-link py-2 rounded-3">
            <i class="nav-icon bi bi-grid-1x2 text-indigo"></i>
            <p class="fw-medium">Dashboard</p>
          </a>
        </li>

        <?php if ($rol === 'COCHERA' || $rol === 'ADMIN'): ?>
          <li class="nav-item">
            <a href="#" class="nav-link py-2 rounded-3" data-bs-toggle="collapse" data-bs-target="#submenuCochera">
              <i class="nav-icon bi bi-car-front text-info"></i>
              <p class="fw-medium">Cochera <i class="nav-arrow bi bi-chevron-right ms-auto"></i></p>
            </a>
            <ul class="nav nav-treeview collapse list-unstyled ms-4 border-start ps-2" id="submenuCochera">
              <li class="nav-item">
                <a href="<?= htmlspecialchars(BASE_URL) ?>/index.php?c=cochera&a=entrada" class="nav-link py-1 small">Entrada</a>
              </li>
              <li class="nav-item">
                <a href="<?= htmlspecialchars(BASE_URL) ?>/index.php?c=cochera&a=salida" class="nav-link py-1 small">Salida</a>
              </li>
              <?php if ($rol === 'ADMIN'): ?>
                <li class="nav-item mt-1">
                  <a href="<?= htmlspecialchars(BASE_URL) ?>/index.php?c=cochera&a=configuracion" class="nav-link py-1 small text-warning"><i class="bi bi-gear me-2"></i>Tarifas</a>
                </li>
              <?php endif; ?>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($rol === 'BANOS' || $rol === 'ADMIN'): ?>
          <li class="nav-item">
            <a href="<?= htmlspecialchars(BASE_URL) ?>/index.php?c=bano&a=index" class="nav-link py-2 rounded-3">
              <i class="nav-icon bi bi-droplet-fill text-primary"></i>
              <p class="fw-medium">Servicios Higiénicos</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($rol === 'ALQUILERES' || $rol === 'ADMIN'): ?>
          <li class="nav-item">
            <a href="#" class="nav-link py-2 rounded-3" data-bs-toggle="collapse" data-bs-target="#submenuAlquileres">
              <i class="nav-icon bi bi-shop text-success"></i>
              <p class="fw-medium">Alquileres <i class="nav-arrow bi bi-chevron-right ms-auto"></i></p>
            </a>
            <ul class="nav nav-treeview collapse list-unstyled ms-4 border-start ps-2" id="submenuAlquileres">
              <li class="nav-item"><a href="index.php?c=stand&a=index" class="nav-link py-1 small">Gestión de Stands</a></li>
              <li class="nav-item"><a href="index.php?c=arrendatario&a=index" class="nav-link py-1 small">Arrendatarios</a></li>
              <li class="nav-item"><a href="index.php?c=contrato&a=index" class="nav-link py-1 small">Contratos Activos</a></li>
            </ul>
          </li>
        <?php endif; ?>

        <?php if ($rol === 'TESORERIA' || $rol === 'ADMIN'): ?>
          <li class="nav-item mt-2">
            <a href="<?= htmlspecialchars(BASE_URL) ?>/index.php?c=tesoreria&a=index" class="nav-link py-2 rounded-3">
              <i class="nav-icon bi bi-cash-stack text-success"></i>
              <p class="fw-medium">Tesorería</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= htmlspecialchars(BASE_URL) ?>/index.php?c=reportes&a=index" class="nav-link py-2 rounded-3">
              <i class="nav-icon bi bi-pie-chart text-purple"></i>
              <p class="fw-medium">Reportes</p>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($rol === 'ADMIN'): ?>
          <li class="nav-header text-uppercase mt-4 mb-1">
            <small class="fw-bold text-muted opacity-50 ms-2" style="font-size: 0.65rem; letter-spacing: 1px;">SISTEMA</small>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link py-2 rounded-3" data-bs-toggle="collapse" data-bs-target="#submenuConfig">
              <i class="nav-icon bi bi-shield-lock text-danger"></i>
              <p class="fw-medium">Seguridad <i class="nav-arrow bi bi-chevron-right ms-auto"></i></p>
            </a>
            <ul class="nav nav-treeview collapse list-unstyled ms-4 border-start ps-2" id="submenuConfig">
              <li class="nav-item">
                <a href="index.php?c=usuario&a=index" class="nav-link py-1 small">Usuarios</a>
              </li>
              <li class="nav-item">
                <a href="index.php?c=config&a=empresa" class="nav-link py-1 small">Datos Galería</a>
              </li>
            </ul>
          </li>

        <?php endif; ?>
        <li class="nav-item mt-auto pt-5 pb-3">
          <a href="<?= htmlspecialchars(BASE_URL) ?>/logout.php" class="nav-link text-danger border border-danger border-opacity-10 rounded-3 py-2 mx-1">
            <i class="nav-icon bi bi-power"></i>
            <p class="fw-bold">Salir del Sistema</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>

<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <h3 class="mb-0"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h3>
    </div>
  </div>
  <div class="app-content">
    <div class="container-fluid">