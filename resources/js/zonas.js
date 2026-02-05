document.addEventListener('DOMContentLoaded', function () {
    const zonaToggle = document.getElementById('zonaToggle');
    const zonaTexto = document.getElementById('zonaTexto');
    const zonaInput = document.getElementById('zonaInput');
    const zonaSuggestions = document.getElementById('zonaSuggestions');

    let zonasCache = null; // Cache para no llamar a la API cada vez

    // 1. Al hacer clic en el contenedor
    zonaToggle.addEventListener('click', async function () {
        // Mostrar input y cargar sugerencias si es la primera vez
        zonaInput.style.display = 'block';
        zonaInput.focus();

        if (!zonasCache) {
            try {
                const res = await fetch('/api/zonas');
                if (res.ok) {
                    zonasCache = await res.json();
                } else {
                    console.error('Error al cargar zonas');
                    return;
                }
            } catch (err) {
                console.error('No se pudieron cargar las zonas:', err);
                return;
            }
        }

        // Mostrar todas las zonas (o puedes filtrar al escribir)
        renderSuggestions(zonasCache.slice(0, 10)); // Limitamos a 10 para mejor UX
        zonaSuggestions.style.display = 'block';
    });

    // 2. Si el usuario escribe, filtrar (opcional, mejora UX)
    zonaInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        if (!query) {
            renderSuggestions(zonasCache.slice(0, 10));
        } else {
            const filtered = zonasCache.filter(z => 
                z.nombre.toLowerCase().includes(query)
            ).slice(0, 10);
            renderSuggestions(filtered);
        }
    });

    // 3. Función para renderizar sugerencias
    function renderSuggestions(list) {
        if (list.length === 0) {
            zonaSuggestions.innerHTML = '<div style="padding: 8px; color: #999;">No se encontraron ciudades</div>';
            return;
        }

        zonaSuggestions.innerHTML = list.map(zona => `
            <div 
                data-nombre="${zona.nombre}" 
                data-lat="${zona.lat}" 
                data-lng="${zona.lng}"
                style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee;"
                onmouseenter="this.style.backgroundColor='#f0f0f0'"
                onmouseleave="this.style.backgroundColor=''"
                onclick="selectZona(this)"
            >
                ${zona.nombre}
            </div>
        `).join('');
    }

    // 4. Función global para manejar selección
    window.selectZona = function (element) {
        const nombre = element.getAttribute('data-nombre');
        const lat = element.getAttribute('data-lat');
        const lng = element.getAttribute('data-lng');

        // Guardar en el DOM (puedes usarlo después para buscar servicios)
        zonaTexto.textContent = nombre;
        zonaInput.value = nombre;

        // Ocultar sugerencias
        zonaSuggestions.style.display = 'none';

        // Opcional: guardar en variables globales o localStorage
        window.selectedZona = { nombre, lat, lng };

        console.log('Zona seleccionada:', window.selectedZona);
    };

    // 5. Cerrar al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (!zonaToggle.contains(e.target)) {
            zonaInput.style.display = 'none';
            zonaSuggestions.style.display = 'none';
        }
    });
});