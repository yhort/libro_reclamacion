<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2/browser/overlayscrollbars.browser.es6.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.min.js"></script>
<script src="<?= htmlspecialchars(BASE_URL) ?>/assets/adminlte/js/adminlte.min.js"></script>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const el = document.querySelector('.sidebar-wrapper');
    if (el && window.OverlayScrollbarsGlobal?.OverlayScrollbars) {
      OverlayScrollbarsGlobal.OverlayScrollbars(el, {
        scrollbars: { theme: 'os-theme-light', autoHide: 'leave', clickScroll: true },
      });
    }
  });
</script>
</body>
</html>
