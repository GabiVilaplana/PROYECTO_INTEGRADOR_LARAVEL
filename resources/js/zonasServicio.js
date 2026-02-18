document.addEventListener('DOMContentLoaded', function () {
    // Función para cerrar todos los selectores excepto el especificado
    function closeAllSelectors(exceptId = null) {
        const selectors = ['zonaToggle', 'dateToggle', 'servicioToggle'];
        selectors.forEach(selectorId => {
            if (selectorId !== exceptId) {
                const element = document.getElementById(selectorId);
                if (element) {
                    element.classList.remove('focused');
                }
            }
        });
    }

    // ================================
    // SELECTOR DE SERVICIOS
    // ================================
    const servicioToggle = document.getElementById('servicioToggle');
    const zonaServicio = document.getElementById('zonaServicio');
    const servicioInput = document.getElementById('servicioInput');
    const servicioResults = document.getElementById('servicioResults');

    if (!servicioToggle || !zonaServicio || !servicioInput || !servicioResults) {
        console.warn('Elementos del selector de servicios no encontrados');
        return;
    }

    // Añadir eventos a las opciones existentes
    function addEventListenersToOptions() {
        document.querySelectorAll('.servicio-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                selectServicio(this);
            });
            
            option.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#eef4ff';
            });
            
            option.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    }

    // Función global para manejar selección
    function selectServicio(element) {
        const nombre = element.getAttribute('data-nombre');
        const id = element.getAttribute('data-id');

        // Guardar en el DOM
        zonaServicio.textContent = nombre;
        servicioInput.value = nombre;

        // Ocultar sugerencias
        servicioToggle.classList.remove('focused');

        // Guardar en variables globales
        window.selectedServicio = { nombre, id };

        console.log('Servicio seleccionado:', window.selectedServicio);
    }

    // 1. Al hacer clic en el contenedor
    servicioToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        
        // Cerrar otros selectores
        closeAllSelectors('servicioToggle');
        
        servicioToggle.classList.toggle('focused');
        
        if (servicioToggle.classList.contains('focused')) {
            servicioInput.focus();
        }
    });

    // 2. Si el usuario escribe, filtrar
    servicioInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        const allOptions = document.querySelectorAll('.servicio-option');
        
        allOptions.forEach(option => {
            const optionText = option.textContent.toLowerCase();
            if (query === '' || optionText.includes(query)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });

    // 3. Cerrar al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (!servicioToggle.contains(e.target) && 
            !document.getElementById('zonaToggle')?.contains(e.target) && 
            !document.getElementById('dateToggle')?.contains(e.target)) {
            servicioToggle.classList.remove('focused');
        }
    });

    // Inicializar eventos
    addEventListenersToOptions();
});