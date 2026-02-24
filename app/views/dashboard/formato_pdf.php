<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 24px 26px;
        }

        body {
            font-family: DejaVu Sans, Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        .page {
            border: 1px solid #111827;
            padding: 18px;
        }

        .muted {
            color: #6b7280;
        }

        .strong {
            font-weight: 700;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo {
            width: 120px;
            height: auto;
        }

        .title {
            font-size: 18px;
            letter-spacing: .6px;
            font-weight: 800;
            margin: 0;
        }

        .subtitle {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #374151;
        }

        .top-meta {
            margin-top: 8px;
            font-size: 11px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 14px;
            border: 1px solid #111827;
            font-weight: 700;
            font-size: 10px;
            letter-spacing: .3px;
        }

        .badge-red {
            border-color: #b91c1c;
            color: #b91c1c;
            background: #fff5f5;
        }

        .badge-green {
            border-color: #15803d;
            color: #15803d;
            background: #f0fdf4;
        }

        .badge-amber {
            border-color: #b45309;
            color: #b45309;
            background: #fffbeb;
        }

        .divider {
            border-top: 2px solid #111827;
            margin: 14px 0 16px;
        }

        .section {
            border: 1px solid #111827;
            background: #f3f4f6;
            padding: 8px 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-top: 14px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data td {
            border: 1px solid #d1d5db;
            padding: 8px 9px;
            vertical-align: top;
        }

        td.label {
            width: 26%;
            background: #f9fafb;
            font-weight: 700;
        }

        td.value {
            width: 24%;
        }

        .two-col {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .two-col td {
            vertical-align: top;
        }

        .two-col .box {
            width: 49%;
        }

        .box table.data td.label {
            width: 34%;
        }

        .box table.data td.value {
            width: 66%;
        }

        .paragraph {
            border: 1px solid #d1d5db;
            padding: 10px 10px;
            margin-top: 10px;
            background: #ffffff;
        }

        .footer {
            margin-top: 16px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            font-size: 9.5px;
            color: #6b7280;
            text-align: center;
            line-height: 1.35;
        }
    </style>
</head>

<body>

    <?php
    // Helpers para evitar “vacíos feos”
    $v = function ($x, $alt = '—') {
        $x = is_string($x) ? trim($x) : $x;
        return ($x === null || $x === '') ? $alt : $x;
    };

    // Badge según estado
    $estado = strtoupper(trim($r['estado'] ?? 'PENDIENTE'));
    $badgeClass = 'badge-amber';
    if ($estado === 'ATENDIDO') $badgeClass = 'badge-green';
    if ($estado === 'PENDIENTE') $badgeClass = 'badge-red';

    // Logo: usa ruta física
    // Ajusta si tu logo está en /public/assets/logo-tykesoft.png
    $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/libro_reclamacion/invanelay/public/assets/logo-tykesoft.png';
    $hasLogo = is_file($logoPath);
    ?>

    <div class="page">

        <!-- HEADER -->
        <table class="header-table">
            <tr>
                <td style="width:25%;">
                    <?php if ($hasLogo): ?>
                        <img class="logo" src="<?= $logoPath ?>" alt="Logo">
                    <?php else: ?>
                        <div class="muted">LOGO</div>
                    <?php endif; ?>
                </td>
                <td style="width:75%;" class="right">
                    <p class="title">LIBRO DE RECLAMACIONES</p>
                    <p class="subtitle">Hoja de Reclamación Virtual</p>

                    <div class="top-meta">
                        <span class="strong">N° <?= htmlspecialchars($v($r['correlativo'])) ?></span>
                        &nbsp;&nbsp;·&nbsp;&nbsp;
                        <span class="badge <?= $badgeClass ?>">ESTADO: <?= htmlspecialchars($estado) ?></span>
                        <div class="muted" style="margin-top:6px;">
                            Fecha de registro: <span class="strong"><?= date('d/m/Y', strtotime($r['fecha_registro'])) ?></span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- SECTION 1 -->
        <div class="section">1. Identificación del consumidor</div>

        <table class="two-col">
            <tr>
                <td class="box">
                    <table class="data">
                        <tr>
                            <td class="label">Nombre / Razón social</td>
                            <td class="value"><?= htmlspecialchars($v($r['nombre_completo'])) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Documento</td>
                            <td class="value"><?= htmlspecialchars($v($r['tipo_doc'])) ?> - <?= htmlspecialchars($v($r['num_doc'])) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Correo</td>
                            <td class="value"><?= htmlspecialchars($v($r['email'])) ?></td>
                        </tr>
                    </table>
                </td>

                <td style="width:2%;"></td>

                <td class="box">
                    <table class="data">
                        <tr>
                            <td class="label">Teléfono</td>
                            <td class="value"><?= htmlspecialchars($v($r['telefono'], 'No registrado')) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Dirección</td>
                            <td class="value"><?= htmlspecialchars($v($r['direccion'], 'No especificada')) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Tipo</td>
                            <td class="value"><?= htmlspecialchars($v($r['tipo_incidencia'])) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- SECTION 2 -->
        <div class="section">2. Detalle de la reclamación</div>

        <table class="data">
            <tr>
                <td class="label">Tipo de bien</td>
                <td class="value"><?= htmlspecialchars($v($r['tipo_bien'])) ?></td>
            </tr>
            <tr>
                <td class="label">Monto reclamado</td>
                <td class="value">S/ <?= number_format((float)($r['monto_reclamado'] ?? 0), 2) ?></td>
            </tr>
            <tr>
                <td class="label">Descripción del bien</td>
                <td class="value"><?= htmlspecialchars($v($r['descripcion_bien'], '—')) ?></td>
            </tr>
        </table>

        <div class="paragraph">
            <div class="strong" style="margin-bottom:6px;">Detalle del reclamo/queja</div>
            <?= nl2br(htmlspecialchars($v($r['detalle_cliente'], '—'))) ?>
        </div>

        <div class="paragraph">
            <div class="strong" style="margin-bottom:6px;">Pedido del consumidor</div>
            <?= nl2br(htmlspecialchars($v($r['pedido_cliente'], '—'))) ?>
        </div>

        <!-- SECTION 3 -->
        <div class="section">3. Respuesta del establecimiento</div>

        <table class="data">
            <tr>
                <td class="label">Acciones / Respuesta</td>
                <td class="value">
                    <?= $r['respuesta_negocio']
                        ? nl2br(htmlspecialchars($r['respuesta_negocio']))
                        : '<span class="muted"><i>Pendiente de atención por parte del establecimiento.</i></span>' ?>
                </td>
            </tr>
            <tr>
                <td class="label">Fecha de respuesta</td>
                <td class="value">
                    <?= $r['fecha_respuesta'] ? date('d/m/Y H:i', strtotime($r['fecha_respuesta'])) : '—' ?>
                </td>
            </tr>
        </table>

        <div class="footer">
            Conforme al Código de Protección y Defensa del Consumidor, el proveedor tiene un plazo máximo de
            <span class="strong">15 días hábiles</span> para responder.<br>
            Generado por Sistema Tykesoft · Fecha de impresión: <?= date('d/m/Y H:i:s') ?>
        </div>

    </div>

</body>

</html>