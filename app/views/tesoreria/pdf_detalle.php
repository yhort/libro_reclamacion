<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #001f3f; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #001f3f; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-size: 14px; font-weight: bold; }
        .text-success { color: #157347; }
        .text-danger { color: #bb2d3b; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">AUDITORÍA DE MOVIMIENTOS</h2>
        <p>Caja: <strong><?= $caja['modulo'] ?></strong> | Fecha: <strong><?= date('d/m/Y', strtotime($fecha)) ?></strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Concepto</th>
                <th>Tipo</th>
                <th class="text-right">Monto</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $saldo_dia = 0;
            foreach ($movimientos as $m): 
                $saldo_dia += ($m['tipo'] == 'INGRESO' ? $m['monto'] : -$m['monto']);
            ?>
                <tr>
                    <td><?= date('H:i', strtotime($m['fecha_hora'])) ?></td>
                    <td><?= htmlspecialchars($m['concepto']) ?></td>
                    <td><?= $m['tipo'] ?></td>
                    <td class="text-right <?= $m['tipo'] == 'INGRESO' ? 'text-success' : 'text-danger' ?>">
                        S/ <?= number_format($m['monto'], 2) ?>
                    </td>
                    <td><?= $m['usuario'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        SALDO GENERADO EN EL PERIODO: S/ <?= number_format($saldo_dia, 2) ?>
    </div>
</body>
</html>