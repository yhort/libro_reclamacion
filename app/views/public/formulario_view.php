<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libro de Reclamaciones Virtual - Tykesoft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-color: #004a99; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-header { background-color: var(--primary-color); color: white; font-weight: bold; font-size: 1.3rem; }
        .section-title { color: var(--primary-color); border-bottom: 2px solid var(--primary-color); margin-bottom: 20px; padding-bottom: 5px; font-size: 1.2rem; font-weight: 600; }
        .btn-primary { background-color: var(--primary-color); border: none; }
        .btn-primary:hover { background-color: #003366; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="text-center mb-4">
        <img src="assets/logo-tykesoft.png" alt="Tykesoft" style="max-width: 200px;">
        <h2 class="mt-3">Libro de Reclamaciones Virtual</h2>
        <p class="text-muted">Conforme al Código de Protección y Defensa del Consumidor</p>
    </div>

    <div class="card shadow border-0">
        <div class="card-header text-center py-3">HOJA DE RECLAMACIÓN</div>
        <div class="card-body p-4 p-md-5">
            
            <form action="index.php?c=reclamo&a=guardar" method="POST">

                <div class="section-title">1. Identificación del Consumidor</div>
                <div class="row g-3 mb-5">
                    <div class="col-md-8">
                        <label class="form-label">Nombre Completo o Razón Social *</label>
                        <input type="text" name="nombre_completo" class="form-control" placeholder="Ej: Juan Pérez / Empresa S.A.C." required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">DNI / CE / RUC *</label>
                        <input type="text" name="num_doc" class="form-control" placeholder="Número de documento" required>
                        <input type="hidden" name="tipo_doc" value="DNI"> 
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico *</label>
                        <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono / Celular</label>
                        <input type="text" name="telefono" class="form-control" placeholder="Ej: 987654321">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dirección / Domicilio *</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Av. Ejemplo 123, Ciudad" required>
                    </div>
                </div>

                <div class="section-title">2. Detalle de la Reclamación</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Tipo de Bien</label>
                        <select name="tipo_bien" class="form-select">
                            <option value="Producto">Producto</option>
                            <option value="Servicio">Servicio</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Monto Reclamado (S/)</label>
                        <input type="number" step="0.01" name="monto_reclamado" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Descripción del Bien</label>
                        <input type="text" name="descripcion_bien" class="form-control" placeholder="Ej: Software de ventas">
                    </div>
                    
                    <div class="col-12"> 
                        <label class="form-label">Tipo de Incidencia *</label>
                        <select name="tipo_incidencia" class="form-select" required>
                            <option value="Reclamo">Reclamo (Disconformidad con el producto o servicio)</option>
                            <option value="Queja">Queja (Malestar respecto a la atención al público)</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Detalle del Reclamo / Queja *</label>
                        <textarea name="detalle_cliente" class="form-control" rows="4" placeholder="Describa lo sucedido de forma clara..." required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Pedido / Solicitud del Consumidor *</label>
                        <textarea name="pedido_cliente" class="form-control" rows="3" required placeholder="¿Qué solución o respuesta espera de nuestra parte?"></textarea>
                    </div>
                </div>

                <div class="alert alert-secondary small border-0">
                    <strong>Nota:</strong> De acuerdo a la normativa vigente, el proveedor deberá dar respuesta al reclamo o queja en un plazo no mayor a quince (15) días hábiles.
                </div>

                <div class="text-end mt-4">
                    <button type="reset" class="btn btn-light border px-4">Limpiar Formulario</button>
                    <button type="submit" class="btn btn-primary px-5 py-2">Enviar Hoja de Reclamación</button>
                </div>

            </form>
        </div>
    </div>
</div>

<footer class="text-center pb-5 text-muted small">
    &copy; 2026 Tykesoft. Todos los derechos reservados.
</footer>

</body>
</html>