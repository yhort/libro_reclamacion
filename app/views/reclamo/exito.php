<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reclamo Enviado - Tykesoft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-color: #004a99; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; height: 100vh; display: flex; align-items: center; }
        .success-card { max-width: 600px; margin: auto; border: none; border-radius: 15px; }
        .icon-box { color: #28a745; font-size: 4rem; margin-bottom: 20px; }
        .correlativo-box { background-color: #e9ecef; padding: 15px; border-radius: 10px; font-weight: bold; font-size: 1.5rem; color: var(--primary-color); border: 2px dashed var(--primary-color); }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

<div class="container text-center">
    <div class="card shadow success-card p-5">
        <div class="icon-box">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check2-circle" viewBox="0 0 16 16">
                <path d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0z"/>
                <path d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l7-7z"/>
            </svg>
        </div>
        
        <h2 class="mb-3">¡Reclamo Registrado!</h2>
        <p class="text-muted mb-4">Su hoja de reclamación ha sido enviada con éxito. Guarde su número de seguimiento para futuras consultas.</p>

        <div class="correlativo-box mb-4">
            N° <?= htmlspecialchars($correlativo) ?>
        </div>

        <p class="small text-muted mb-4">
            Hemos enviado una copia detallada a su correo electrónico. 
            El plazo de respuesta es de 15 días hábiles.
        </p>

        <div class="d-grid gap-2 d-md-block no-print">
            <button onclick="window.print()" class="btn btn-outline-secondary px-4 me-md-2">Imprimir Cargo</button>
            <a href="index.php" class="btn btn-primary px-4">Volver al inicio</a>
        </div>
    </div>
</div>

</body>
</html>