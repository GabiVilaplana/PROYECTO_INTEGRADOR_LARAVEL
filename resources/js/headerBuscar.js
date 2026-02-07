document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('searchForm');

    if (!searchForm) {
        return;
    }

    // Función para verificar si hay filtros aplicados
    function hayFiltrosAplicados() {
        const zona = window.selectedZona?.nombre || '';
        const fechaDesde = document.getElementById('fechaDesde')?.value || '';
        const fechaHasta = document.getElementById('fechaHasta')?.value || '';
        const servicio = window.selectedServicio?.nombre || '';

        return zona.trim() !== '' ||
            fechaDesde.trim() !== '' ||
            fechaHasta.trim() !== '' ||
            servicio.trim() !== '';
    }

    // Manejar el submit del formulario
    searchForm.addEventListener('submit', function (e) {
        e.preventDefault();

        if (hayFiltrosAplicados()) {
            // Hay filtros, enviar el formulario a Laravel
            document.getElementById('filtroZona').value = window.selectedZona?.nombre || '';
            document.getElementById('filtroFechaDesde').value = document.getElementById('fechaDesde')?.value || '';
            document.getElementById('filtroFechaHasta').value = document.getElementById('fechaHasta')?.value || '';
            document.getElementById('filtroServicio').value = window.selectedServicio?.nombre || '';

            this.submit();
        } else {
            // No hay filtros, redirigir directamente a home
            window.location.href = '{{ route("home") }}';
        }
    });
});