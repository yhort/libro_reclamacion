v</div>
</div>
</main>

<footer class="app-footer">
    <div class="float-end d-none d-sm-inline">Libro de Reclamacion</div>
    <strong>&copy; <?= date('Y') ?>.</strong> Todos los derechos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    (function() {
        // --- CONFIGURACIÓN ---
        const MINUTOS_TOTALES = 20;
        const MINUTOS_AVISO = 18;

        const tiempoMaximo = MINUTOS_TOTALES * 60;
        const tiempoAviso = MINUTOS_AVISO * 60;

        let segundosTranscurridos = 0;
        let avisoAbierto = false;
        let timerInterval;

        const contadorSesion = setInterval(() => {
            segundosTranscurridos++;

            // 1. Mostrar el aviso (Minuto 18)
            if (segundosTranscurridos === tiempoAviso) {
                avisoAbierto = true;

                Swal.fire({
                    title: '¿Sigues trabajando?',
                    html: 'Tu sesión expirará por inactividad en <b></b> segundos.',
                    icon: 'warning',
                    showConfirmButton: true,
                    confirmButtonText: 'Mantener sesión',
                    allowOutsideClick: false,
                    timer: (tiempoMaximo - tiempoAviso) * 1000,
                    timerProgressBar: true,
                    didOpen: () => {
                        const b = Swal.getHtmlContainer().querySelector('b');
                        timerInterval = setInterval(() => {
                            b.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        resetearContador();
                        avisoAbierto = false;
                    }
                });
            }

            // 2. Ejecutar el Logout Real (Minuto 20)
            if (segundosTranscurridos >= tiempoMaximo) {
                clearInterval(contadorSesion);
                Swal.close();
                window.location.href = "<?= BASE_URL ?>/logout.php?error=timeout";
            }
        }, 1000);

        function resetearContador() {
            if (!avisoAbierto) {
                segundosTranscurridos = 0;
            }
        }

        ['mousemove', 'mousedown', 'keypress', 'touchstart'].forEach(evt => {
            window.addEventListener(evt, resetearContador, false);
        });
    })();
</script>