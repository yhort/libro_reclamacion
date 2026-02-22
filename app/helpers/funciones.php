<?php
declare(strict_types=1);

function asset(string $path): string {
  $path = ltrim($path, '/');
  return BASE_PATH . '/assets/' . $path;
}

function view(string $viewPath, array $data = []): void {
  extract($data, EXTR_SKIP);
  $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
  if (!file_exists($viewFile)) {
    http_response_code(500);
    echo 'View not found: ' . htmlspecialchars($viewPath);
    exit;
  }
  require __DIR__ . '/../views/layout/header.php';
  require __DIR__ . '/../views/layout/sidebar.php';
  require $viewFile;
  require __DIR__ . '/../views/layout/footer.php';
  require __DIR__ . '/../views/layout/scripts.php';
}
