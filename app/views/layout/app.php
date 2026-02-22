<?php
// Wrapper principal: Header + Sidebar + Vista + Footer + Scripts
// El controlador debe definir $view con la ruta absoluta de la vista.

require __DIR__ . '/header.php';
require __DIR__ . '/sidebar.php';

if (!isset($view) || !is_string($view) || !file_exists($view)) {
    echo "<div class='content-wrapper'><section class='content p-3'><div class='alert alert-danger'>Vista no encontrada.</div></section></div>";
} else {
    require $view;
}

require __DIR__ . '/footer.php';
require __DIR__ . '/scripts.php';
