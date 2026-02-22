<?php
// 1. Evitamos que cualquier error previo ensucie la imagen
ob_start();

// 2. Incluimos la librería (ahora está en la misma carpeta)
require_once "qrlib.php";

// 3. Limpiamos el buffer por si acaso
ob_end_clean();

// 4. Recibimos el texto
$texto = $_GET['text'] ?? 'SIN-DATOS';

// 5. Enviamos la cabecera correcta
header("Content-Type: image/png");

// 6. Generamos el QR directamente al navegador
QRcode::png($texto, false, QR_ECLEVEL_L, 6, 2);
exit;