<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .hoja { width: 100%; border: 1px solid #000; padding: 15px; }
        
        /* Layout usando tablas porque Dompdf no soporta Flexbox */
        .tabla-full { width: 100%; border-collapse: collapse; }
        .header-table { margin-bottom: 20px; border-bottom: 2px solid #000; }
        
        .titulo-principal { text-align: center; text-transform: uppercase; }
        .seccion { background: #f0f0f0; font-weight: bold; padding: 8px; margin-top: 15px; border: 1px solid #000; text-transform: uppercase; }
        
        .fila-datos { border-bottom: 1px solid #ddd; }
        .etiqueta { width: 30%; font-weight: bold; padding: 8px 5px; vertical-align: top; background: #fafafa; }
        .contenido { width: 70%; padding: 8px 5px; vertical-align: top; }
        
        .footer-pdf { margin-top: 30px; font-size: 9px; text-align: center; color: #666; border-top: 1px solid #ccc; padding-top: 10px; }
        .logo { width: 100px; }
        .status-badge { font-weight: bold; color: #004a99; }
    </style>
</head>
<body>

<div class="hoja">
    <table class="tabla-full header-table">
        <tr>
            <td style="width: 25%;">
                <img src="<?= $_SERVER['DOCUMENT_ROOT'] . '/assets/logo-tykesoft.png' ?>" class="logo">
            </td>
            <td class="titulo-principal" style="width: 75%;">
                <h2 style="margin:0; font-size: 18px;">LIBRO DE RECLAMACIONES</h2>
                <p style="margin:5px 0 0 0; font-size: 14px;">Hoja de Reclamación N° <strong><?= $r['correlativo'] ?></strong></p>
            </td>
        </tr>
    </table>

    <p style="text-align: right;"><strong>Fecha de Registro:</strong> <?= date('d/m/Y', strtotime($r['fecha_registro'])) ?></p>

    <div class="seccion">1. IDENTIFICACIÓN DEL CONSUMIDOR</div>
    <table class="tabla-full">
        <tr class="fila-datos">
            <td class="etiqueta">Nombre completo:</td>
            <td class="contenido"><?= htmlspecialchars($r['nombre_completo']) ?></td>
        </tr>
        <tr class="fila-datos">
            <td class="etiqueta">Documento de Identidad:</td>
            <td class="contenido"><?= $r['tipo_doc'] ?> - <?= $r['num_doc'] ?></td>
        </tr>
        <tr class="fila-datos">
            <td class="etiqueta">Correo Electrónico:</td>
            <td class="contenido"><?= $r['email'] ?></td>
        </tr>
        <tr class="fila-datos">
            <td class="etiqueta">Dirección:</td>
            <td class="contenido"><?= htmlspecialchars($r['direccion'] ?? 'No especificada') ?></td>
        </tr>
    </table>

    <div class="seccion">2. DETALLE DE LA RECLAMACIÓN</div>
    <table class="tabla-full">
        <tr class="fila-datos">
            <td class="etiqueta">Tipo de Bien:</td>
            <td class="contenido"><?= $r['tipo_bien'] ?> (Monto: S/ <?= number_format((float)$r['monto_reclamado'], 2) ?>)</td>
        </tr>
        <tr class="fila-datos">
            <td class="etiqueta">Incidencia:</td>
            <td class="contenido"><span class="status-badge"><?= strtoupper($r['tipo_incidencia']) ?></span></td>
        </tr>
        <tr class="fila-datos">
            <td class="etiqueta">Descripción del Bien:</td>
            <td class="contenido"><?= nl2br(htmlspecialchars($r['descripcion_bien'] ?? '--')) ?></td>
        </tr>
        <tr class="fila-datos">
            <td class="etiqueta">Detalle del Reclamo:</td>
            <td class="contenido"><?= nl2br(htmlspecialchars($r['detalle_cliente'])) ?></td>
        </tr>
        <tr class="fila-datos">
            <td class="etiqueta">Pedido del Consumidor:</td>
            <td class="contenido"><?= nl2br(htmlspecialchars($r['pedido_cliente'])) ?></td>
        </tr>
    </table>

    <div class="seccion">3. RESPUESTA DEL ESTABLECIMIENTO</div>
    <table class="tabla-full">
        <tr>
            <td class="etiqueta">Respuesta / Acciones:</td>
            <td class="contenido" style="min-height: 100px;">
                <?= $r['respuesta_negocio'] ? nl2br(htmlspecialchars($r['respuesta_negocio'])) : '<i>Pendiente de atención por parte del establecimiento.</i>' ?>
            </td>
        </tr>
        <?php if($r['fecha_respuesta']): ?>
        <tr>
            <td class="etiqueta">Fecha de Respuesta:</td>
            <td class="contenido"><?= date('d/m/Y H:i', strtotime($r['fecha_respuesta'])) ?></td>
        </tr>
        <?php endif; ?>
    </table>

    <div class="footer-pdf">
        <p>Conforme a lo establecido en el Código de Protección y Defensa del Consumidor, el proveedor cuenta con un plazo de 15 días hábiles para responder.</p>
        <strong>Generado por Sistema Tykesoft para: <?= MAIL_FROM_NAME ?></strong><br>
        Fecha de impresión: <?= date('d/m/Y H:i:s') ?>
    </div>
</div>

</body>
</html>