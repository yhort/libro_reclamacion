<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Libro de Reclamaciones' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #004a99;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: #003366;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand">Libro de Reclamaciones</span>
    </div>
</nav>

<div class="py-4">
    <?= $content ?>
</div>

<footer class="text-center py-4 text-muted small">
    &copy; <?= date('Y') ?> Tykesoft
</footer>

</body>
</html>