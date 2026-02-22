<?php
$t_ingresos = 0;
$t_egresos = 0;
?>
<div class="text-center mb-4">
    <h2>REPORTE DE FLUJO DE CAJA - GALERÍa</h2>
    <p>Fecha del reporte: <strong><?= date('d/m/Y', strtotime($fecha)) ?></strong></p>
</div>

<table border="1" style="width:100%; border-collapse: collapse; font-family: sans-serif;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Hora</th>
            <th>Módulo</th>
            <th>Concepto</th>
            <th>Ingreso</th>
            <th>Egreso</th>
            <th>Usuario</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($movimientos as $m): 
            if ($m['tipo'] == 'INGRESO') $t_ingresos += $m['monto'];
            else $t_egresos += $m['monto'];
        ?>
            <tr>
                <td><?= date('H:i', strtotime($m['fecha_hora'])) ?></td>
                <td><?= $m['modulo'] ?></td>
                <td><?= htmlspecialchars($m['concepto']) ?></td>
                <td style="color: green; text-align: right;">
                    <?= $m['tipo'] == 'INGRESO' ? 'S/ ' . number_format($m['monto'], 2) : '-' ?>
                </td>
                <td style="color: red; text-align: right;">
                    <?= ($m['tipo'] != 'INGRESO') ? 'S/ ' . number_format($m['monto'], 2) : '-' ?>
                </td>
                <td><?= $m['usuario'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #eee;">
            <td colspan="3" style="text-align: right;">TOTALES:</td>
            <td style="color: green; text-align: right;">S/ <?= number_format($t_ingresos, 2) ?></td>
            <td style="color: red; text-align: right;">S/ <?= number_format($t_egresos, 2) ?></td>
            <td style="background: #001f3f; color: white; text-align: center;">
                SALDO: S/ <?= number_format($t_ingresos - $t_egresos, 2) ?>
            </td>
        </tr>
    </tfoot>
</table>

<script>
    // Esto hace que apenas cargue la página, se abra el diálogo de impresión
    window.onload = function() {
        window.print();
        // Opcional: window.close(); // Cierra la pestaña después de imprimir
    };
</script>