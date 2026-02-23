<?php
declare(strict_types=1);

final class View {
    public static function render(string $view, array $data = [], bool $withLayout = true): void {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . trim($view, '/') . '.php';

        if (!file_exists($viewFile)) {
            die("Vista no encontrada en: $viewFile");
        }

        if ($withLayout) {
            $layoutDir = __DIR__ . '/../views/layout/';
            if(file_exists($layoutDir . 'header.php')) require $layoutDir . 'header.php';
            if(file_exists($layoutDir . 'sidebar.php')) require $layoutDir . 'sidebar.php';
            require $viewFile;
            if(file_exists($layoutDir . 'footer.php')) require $layoutDir . 'footer.php';
            if(file_exists($layoutDir . 'scripts.php')) require $layoutDir . 'scripts.php';
        } else {
            // Para el formulario público que no lleva menú lateral
            require $viewFile;
        }
    }
}