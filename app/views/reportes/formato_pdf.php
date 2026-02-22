<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Tipografía más moderna y limpia */
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 10px; color: #1a202c; line-height: 1.5; }
        
        /* Encabezado minimalista estilo Factura SaaS */
        .header { margin-bottom: 30px; }
        .header-table { width: 100%; border: none; }
        .report-title { font-size: 18px; font-weight: bold; color: #111827; margin-bottom: 5px; }
        .report-subtitle { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
        
        /* Tablas con estilo de "Líneas Finas" */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f9fafb; color: #374151; font-weight: bold; text-align: left; padding: 10px 8px; border-bottom: 2px solid #e5e7eb; text-transform: uppercase; font-size: 9px; }
        td { padding: 8px; border-bottom: 1px solid #f3f4f6; }
        
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        /* Fila de Saldo Anterior con estilo sutil */
        .bg-start { background-color: #f0f7ff; color: #004e9a; font-weight: bold; }
        
        /* Contenedores de Resumen */
        .summary-box { border: 1px solid #e5e7eb; border-radius: 8px; padding: 0; overflow: hidden; }
        .summary-header { background: #f9fafb; font-weight: bold; padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; color: #374151; }
        .summary-body { padding: 10px; }
        
        /* El "Total Final" debe destacar pero con sobriedad */
        .total-highlight { background: #111827; color: white; font-weight: bold; font-size: 12px; }
        
        /* Firmas */
        .signature-space { margin-top: 80px; text-align: center; }
        .signature-line { border-top: 1px solid #9ca3af; width: 200px; margin: 0 auto; padding-top: 5px; color: #4b5563; }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="border: none; padding: 0;">
                    <div class="report-title">ARQUEO DE CAJA GENERAL</div>
                    <div class="report-subtitle">Galería Central - Control de Tesorería</div>
                </td>
                <td style="border: none; padding: 0;" class="text-right">
                    <div style="font-size: 12px; font-weight: bold;">FECHA: <?= date('d/m/Y', strtotime($fecha)) ?></div>
                    <div style="color: #6b7280;">Generado: <?= date('d/m/Y H:i') ?></div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">Hora</th>
                <th width="15%">Módulo</th>
                <th>Concepto / Descripción</th>
                <th width="15%" class="text-right">Ingreso</th>
                <th width="15%" class="text-right">Egreso</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-start">
                <td>00:00</td>
                <td>SISTEMA</td>
                <td>SALDO ANTERIOR (Arrastre de caja)</td>
                <td class="text-right">S/ <?= number_format($saldoAnterior, 2) ?></td>
                <td class="text-right">-</td>
            </tr>
            <?php foreach ($movimientos as $m): ?>
            <tr>
                <td style="color: #6b7280;"><?= date('H:i', strtotime($m['fecha_hora'])) ?></td>
                <td><span style="color: #374151;"><?= $m['modulo'] ?></span></td>
                <td><?= htmlspecialchars($m['concepto']) ?></td>
                <td class="text-right fw-bold" style="color: <?= $m['tipo'] == 'INGRESO' ? '#059669' : '#111827' ?>;">
                    <?= $m['tipo'] == 'INGRESO' ? 'S/ '.number_format($m['monto'], 2) : '-' ?>
                </td>
                <td class="text-right" style="color: <?= $m['tipo'] != 'INGRESO' ? '#dc2626' : '#111827' ?>;">
                    <?= $m['tipo'] != 'INGRESO' ? 'S/ '.number_format($m['monto'], 2) : '-' ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table style="border: none; margin-top: 20px;">
        <tr>
            <td style="border: none; width: 48%; vertical-align: top; padding: 0;">
                <div class="summary-box">
                    <div class="summary-header">RESUMEN OPERATIVO DEL DÍA</div>
                    <table style="margin-bottom: 0; border: none;">
                        <tr><td style="border: none;">(+) Ingresos Totales</td><td class="text-right text-bold" style="border: none;">S/ <?= number_format($t_ing, 2) ?></td></tr>
                        <tr><td style="border: none;">(-) Gastos Operación</td><td class="text-right" style="border: none; color: #dc2626;">S/ <?= number_format($gastos_op, 2) ?></td></tr>
                        <tr style="background-color: #f9fafb;"><td style="font-weight: bold;">FLUJO NETO</td><td class="text-right text-bold">S/ <?= number_format($t_ing - $gastos_op, 2) ?></td></tr>
                    </table>
                </div>
            </td>
            <td style="border: none; width: 4%; border: none;"></td>
            <td style="border: none; width: 48%; vertical-align: top; padding: 0;">
                <div class="summary-box">
                    <div class="summary-header">CONCILIACIÓN FINAL DE EFECTIVO</div>
                    <table style="margin-bottom: 0; border: none;">
                        <tr><td style="border: none;">Saldo Anterior</td><td class="text-right" style="border: none;">S/ <?= number_format($saldoAnterior, 2) ?></td></tr>
                        <tr><td style="border: none;">(+) Movimientos Día</td><td class="text-right" style="border: none;">S/ <?= number_format($t_ing - $t_egr, 2) ?></td></tr>
                        <tr class="total-highlight">
                            <td style="border: none;">EFECTIVO EN CAJA</td>
                            <td class="text-right" style="border: none;">S/ <?= number_format(($saldoAnterior + $t_ing) - $t_egr, 2) ?></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="signature-space">
        <table style="border: none;">
            <tr>
                <td style="border: none;">
                    <div class="signature-line">Firma Cajero</div>
                    <div style="font-size: 8px; color: #9ca3af; margin-top: 4px;">DNI: _________________</div>
                </td>
                <td style="border: none;">
                    <div class="signature-line">Administración</div>
                    <div style="font-size: 8px; color: #9ca3af; margin-top: 4px;">Fecha Recibido: ___/___/___</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>