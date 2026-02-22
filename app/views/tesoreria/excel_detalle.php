<?php
// Evitar problemas de caracteres especiales en Excel
echo "\xEF\xBB\xBF"; 
?>
<table border="1">
    <thead>
        <tr>
            <th colspan="5" style="background-color: #001f3f; color: white; font-size: 14px;">
                DETALLE DE MOVIMIENTOS - CAJA <?= strtoupper($caja['modulo']) ?> (<?= $fecha ?>)
            </th>
        </tr>
        <tr style="background-color: #f2f2f2;">
            <th>Hora</th>
            <th>Concepto / Motivo</th>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Usuario</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($movimientos as $m): ?>
            <tr>
                <td><?= date('H:i', strtotime($m['fecha_hora'])) ?></td>
                <td><?= htmlspecialchars($m['concepto']) ?></td>
                <td><?= $m['tipo'] ?></td>
                <td style="text-align: right;">S/ <?= number_format($m['monto'], 2) ?></td>
                <td><?= htmlspecialchars($m['usuario']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>