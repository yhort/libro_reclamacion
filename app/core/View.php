<?php

class View
{
    public static function render(string $view, array $data = [], string $layout = 'app')
    {
        extract($data);

        $viewFile = __DIR__ . '/../../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("Vista no encontrada: " . $viewFile);
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Selección de layout
        switch ($layout) {
            case 'public':
                require __DIR__ . '/../../views/layout/public_layout.php';
                break;

            case 'app':
                require __DIR__ . '/../../views/layout/app.php';
                break;

            case 'none':
                echo $content;
                break;

            default:
                require __DIR__ . '/../../views/layout/app.php';
        }
    }
}