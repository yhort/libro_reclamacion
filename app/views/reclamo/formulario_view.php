<?php
// formulario_view.php (solo contenido)
?>

<div class="text-center mb-4">
  <h2 class="fw-bold mb-1">Hoja de Reclamación</h2>
  <div class="muted">Completa los datos para registrar tu reclamo o queja.</div>
</div>

<!-- Progreso -->
<div class="d-flex align-items-center justify-content-center gap-2 mb-4">
  <span id="stepBadge1" class="badge rounded-pill text-bg-primary px-3 py-2">1. Datos</span>
  <span class="text-muted">—</span>
  <span id="stepBadge2" class="badge rounded-pill text-bg-light border px-3 py-2">2. Detalle</span>
</div>

<form id="reclamoForm" method="POST" action="index.php?c=reclamo&a=guardar" novalidate>

  <!-- PASO 1 -->
  <section id="step1">
    <div class="mb-3">
      <div class="fw-bold">1) Identificación del Consumidor</div>
      <div class="muted small">Campos con (*) son obligatorios.</div>
    </div>

    <div class="row g-3">
      <div class="col-md-8">
        <label class="form-label fw-semibold">Nombre Completo o Razón Social *</label>
        <input type="text" name="nombre_completo" class="form-control" placeholder="Ej: Juan Pérez / Empresa S.A.C." required>
        <div class="invalid-feedback">Ingresa tu nombre o razón social.</div>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">DNI / CE / RUC *</label>
        <input type="text" name="num_doc" class="form-control" placeholder="Número de documento" required>
        <input type="hidden" name="tipo_doc" value="DNI">
        <div class="invalid-feedback">Ingresa tu documento.</div>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Correo Electrónico *</label>
        <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
        <div class="invalid-feedback">Ingresa un correo válido.</div>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Teléfono / Celular</label>
        <input type="text" name="telefono" class="form-control" placeholder="Ej: 987654321">
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Dirección / Domicilio *</label>
        <input type="text" name="direccion" class="form-control" placeholder="Av. Ejemplo 123, Ciudad" required>
        <div class="invalid-feedback">Ingresa tu dirección.</div>
      </div>

      <div class="col-12 d-flex justify-content-end mt-2">
        <button type="button" id="btnNext" class="btn btn-primary px-4">
          Continuar
        </button>
      </div>
    </div>
  </section>

  <!-- PASO 2 -->
  <section id="step2" style="display:none;">
    <div class="mb-3">
      <div class="fw-bold">2) Detalle de la Reclamación</div>
      <div class="muted small">Describe lo sucedido y qué solución esperas.</div>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Tipo de Bien</label>
        <select name="tipo_bien" class="form-select">
          <option value="Producto">Producto</option>
          <option value="Servicio">Servicio</option>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Monto Reclamado (S/)</label>
        <input type="number" step="0.01" name="monto_reclamado" class="form-control" placeholder="0.00">
      </div>

      <div class="col-md-4">
        <label class="form-label fw-semibold">Descripción del Bien</label>
        <input type="text" name="descripcion_bien" class="form-control" placeholder="Ej: Software de ventas">
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Tipo de Incidencia *</label>
        <select name="tipo_incidencia" class="form-select" required>
          <option value="Reclamo">Reclamo (Disconformidad con el producto o servicio)</option>
          <option value="Queja">Queja (Malestar respecto a la atención al público)</option>
        </select>
        <div class="invalid-feedback">Selecciona el tipo.</div>
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Detalle del Reclamo / Queja *</label>
        <textarea name="detalle_cliente" class="form-control" rows="4"
          placeholder="Describa lo sucedido de forma clara..." required></textarea>
        <div class="invalid-feedback">Describe el detalle.</div>
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Pedido / Solicitud del Consumidor *</label>
        <textarea name="pedido_cliente" class="form-control" rows="3"
          placeholder="¿Qué solución o respuesta espera de nuestra parte?" required></textarea>
        <div class="invalid-feedback">Indica tu solicitud.</div>
      </div>

      <div class="col-12">
        <div class="alert alert-secondary border-0 small mb-0">
          <strong>Nota:</strong> El proveedor responderá en un plazo no mayor a quince (15) días hábiles.
        </div>
      </div>

      <div class="col-12 d-flex justify-content-between align-items-center mt-2">
        <button type="button" id="btnBack" class="btn btn-outline-secondary px-4">
          Volver
        </button>

        <div class="d-flex gap-2">
          <button type="reset" class="btn btn-light border px-4">Limpiar</button>
          <button type="submit" id="btnSubmit" class="btn btn-primary px-5">
            Enviar reclamo
          </button>
        </div>
      </div>
    </div>
  </section>

  <hr class="my-4">

  <div class="text-center">
    <p class="muted small mb-2">¿Ya registraste un reclamo?</p>
    <a href="index.php?c=reclamo&a=consultar_form" class="btn btn-outline-primary px-4" style="border-radius:999px;">
      Consultar estado de mi reclamo
    </a>
  </div>
</form>

<script>
(function(){
  const step1 = document.getElementById('step1');
  const step2 = document.getElementById('step2');
  const btnNext = document.getElementById('btnNext');
  const btnBack = document.getElementById('btnBack');
  const badge1 = document.getElementById('stepBadge1');
  const badge2 = document.getElementById('stepBadge2');
  const form = document.getElementById('reclamoForm');

  function setStep(n){
    if(n === 1){
      step1.style.display = '';
      step2.style.display = 'none';
      badge1.className = 'badge rounded-pill text-bg-primary px-3 py-2';
      badge2.className = 'badge rounded-pill text-bg-light border px-3 py-2';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }else{
      step1.style.display = 'none';
      step2.style.display = '';
      badge1.className = 'badge rounded-pill text-bg-light border px-3 py-2';
      badge2.className = 'badge rounded-pill text-bg-primary px-3 py-2';
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }

  function validateStep1(){
    const inputs = step1.querySelectorAll('input[required], select[required], textarea[required]');
    let ok = true;
    inputs.forEach(el => {
      if(!el.checkValidity()){
        ok = false;
        el.classList.add('is-invalid');
      }else{
        el.classList.remove('is-invalid');
      }
    });
    return ok;
  }

  btnNext.addEventListener('click', () => {
    if(validateStep1()) setStep(2);
  });

  btnBack.addEventListener('click', () => setStep(1));

  form.addEventListener('submit', (e) => {
    // validación final (paso 2)
    const required = step2.querySelectorAll('input[required], select[required], textarea[required]');
    let ok = true;
    required.forEach(el => {
      if(!el.checkValidity()){
        ok = false;
        el.classList.add('is-invalid');
      }else{
        el.classList.remove('is-invalid');
      }
    });

    if(!ok){
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }

    // UX: deshabilitar para evitar doble envío
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerText = 'Enviando...';
  });

  // limpiar invalid al tipear
  form.addEventListener('input', (e) => {
    if(e.target.classList && e.target.classList.contains('is-invalid')){
      if(e.target.checkValidity()) e.target.classList.remove('is-invalid');
    }
  });
})();
</script>