document.addEventListener('DOMContentLoaded', function () {
    // Función para cerrar todos los selectores excepto el especificado
    function closeAllSelectors(exceptId = null) {
        const selectors = ['zonaToggle', 'dateToggle'];
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
    // SELECTOR DE ZONAS
    // ================================
    const zonaToggle = document.getElementById('zonaToggle');
    const zonaTexto = document.getElementById('zonaTexto');
    const zonaInput = document.getElementById('zonaInput');
    const zonaSuggestions = document.getElementById('zonaSuggestions');

    if (!zonaToggle || !zonaTexto || !zonaInput || !zonaSuggestions) {
        console.warn('Elementos del selector de zonas no encontrados');
    } else {
        let zonasCache = null; // Cache para no llamar a la API cada vez

        // Función para renderizar sugerencias
        function renderZonaSuggestions(list) {
            const zonaResults = document.getElementById('zonaResults');
            
            if (list.length === 0) {
                zonaResults.innerHTML = '<div style="padding: 12px 16px; color: #999; text-align: center;">No se encontraron ciudades</div>';
                return;
            }

            zonaResults.innerHTML = list.map(zona => `
                <div 
                    data-nombre="${zona.nombre}" 
                    data-lat="${zona.lat}" 
                    data-lng="${zona.lng}"
                    class="zona-option"
                >
                    ${zona.nombre}
                </div>
            `).join('');
            
            // Añadir eventos a las opciones
            document.querySelectorAll('.zona-option').forEach(option => {
                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    selectZona(this);
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
        function selectZona(element) {
            const nombre = element.getAttribute('data-nombre');
            const lat = element.getAttribute('data-lat');
            const lng = element.getAttribute('data-lng');

            // Guardar en el DOM
            zonaTexto.textContent = nombre;
            zonaInput.value = nombre;

            // Ocultar sugerencias
            zonaToggle.classList.remove('focused');

            // Guardar en variables globales
            window.selectedZona = { nombre, lat, lng };

            console.log('Zona seleccionada:', window.selectedZona);
        }

        // 1. Al hacer clic en el contenedor
        zonaToggle.addEventListener('click', async function (e) {
            e.stopPropagation();
            
            // Cerrar otros selectores
            closeAllSelectors('zonaToggle');
            
            zonaToggle.classList.toggle('focused');
            
            if (zonaToggle.classList.contains('focused')) {
                zonaInput.focus();
                
                if (!zonasCache) {
                    try {
                        const res = await fetch('/api/zonas');
                        if (res.ok) {
                            zonasCache = await res.json();
                            renderZonaSuggestions(zonasCache.slice(0, 10));
                        } else {
                            console.error('Error al cargar zonas');
                            return;
                        }
                    } catch (err) {
                        console.error('No se pudieron cargar las zonas:', err);
                        return;
                    }
                } else {
                    renderZonaSuggestions(zonasCache.slice(0, 10));
                }
            }
        });

        // 2. Si el usuario escribe, filtrar
        zonaInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            if (!query) {
                renderZonaSuggestions(zonasCache.slice(0, 10));
            } else {
                const filtered = zonasCache.filter(z =>
                    z.nombre.toLowerCase().includes(query)
                ).slice(0, 10);
                renderZonaSuggestions(filtered);
            }
        });

        // 3. Cerrar al hacer clic fuera
        document.addEventListener('click', function (e) {
            if (!zonaToggle.contains(e.target) && !document.getElementById('dateToggle')?.contains(e.target)) {
                zonaToggle.classList.remove('focused');
            }
        });
    }
});