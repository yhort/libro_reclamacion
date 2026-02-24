<?php
// views/layout/app.php
// Este layout recibe $content desde View::render()


require __DIR__ . '/header.php';
require __DIR__ . '/sidebar.php';

// ✅ Mostrar contenido renderizado
echo $content ?? "<div class='alert alert-danger'>Contenido vacío.</div>";

require __DIR__ . '/footer.php';
require __DIR__ . '/scripts.php';