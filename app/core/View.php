<?php
declare(strict_types=1);

final class View {
    public static function render(string $view, array $data = []): void {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . trim($view, '/') . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'Vista no encontrada';
            return;
        }
        $layoutDir = __DIR__ . '/../views/layout/';
        require $layoutDir . 'header.php';
        require $layoutDir . 'sidebar.php';
        require $viewFile;
        require $layoutDir . 'footer.php';
        require $layoutDir . 'scripts.php';
    }
}
