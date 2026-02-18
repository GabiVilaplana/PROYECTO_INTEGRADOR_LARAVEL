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
    // SELECTOR DE FECHAS (CALENDARIO)
    // ================================
    const dateToggle = document.getElementById('dateToggle');
    const zonaFechas = document.getElementById('zonaFechas');
    const fechaDesde = document.getElementById('fechaDesde');
    const fechaHasta = document.getElementById('fechaHasta');
    const dateSuggestions = document.getElementById('dateSuggestions');

    if (!dateToggle || !zonaFechas || !fechaDesde || !fechaHasta || !dateSuggestions) {
        console.warn('Elementos del selector de fechas no encontrados');
    } else {
        // Variables para el calendario
        const today = new Date(2026, 1, 7); // 7 de febrero 2026
        let currentDate = new Date(2026, 1, 1); // Febrero 2026
        let selectedDates = [];

        // Función para formatear fecha como "D MMM"
        function formatQuickDate(date) {
            const day = date.getDate();
            const monthNames = ['ene', 'feb', 'mar', 'abr', 'may', 'jun',
                'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
            const month = monthNames[date.getMonth()];
            return `${day} ${month}`;
        }

        // Función para actualizar las fechas en las opciones rápidas
        function updateQuickDates() {
            const todayDate = new Date(2026, 1, 7); // 7 de febrero 2026
            const tomorrowDate = new Date(2026, 1, 8); // 8 de febrero 2026

            // Este fin de semana (sábado y domingo)
            const weekendStart = new Date(2026, 1, 7); // sábado 7
            const weekendEnd = new Date(2026, 1, 8);   // domingo 8

            document.getElementById('today-date').textContent = formatQuickDate(todayDate);
            document.getElementById('tomorrow-date').textContent = formatQuickDate(tomorrowDate);
            document.getElementById('weekend-date').textContent =
                `${formatQuickDate(weekendStart)}-${weekendEnd.getDate()} ${['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'][weekendEnd.getMonth()]}`;
        }

        // 1. Al hacer clic en el contenedor
        dateToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            
            // Cerrar otros selectores
            closeAllSelectors('dateToggle');
            
            dateToggle.classList.toggle('focused');

            if (dateToggle.classList.contains('focused')) {
                renderCalendar();
            }
        });

        // 2. Cerrar al hacer clic fuera
        document.addEventListener('click', function (e) {
            if (!dateToggle.contains(e.target) && !document.getElementById('zonaToggle')?.contains(e.target)) {
                dateToggle.classList.remove('focused');
            }
        });

        // 3. Función para renderizar el calendario
        function renderCalendar() {
            const calendarGrid = document.getElementById('calendarGrid');
            calendarGrid.innerHTML = '';

            // Añadir encabezado de días
            const daysOfWeek = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
            daysOfWeek.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'day header';
                dayHeader.textContent = day;
                calendarGrid.appendChild(dayHeader);
            });

            // Obtener primer día del mes
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const startingDay = firstDay.getDay() || 7; // 0 = domingo, 1 = lunes, etc.

            // Añadir espacios en blanco para los días anteriores
            for (let i = 1; i < startingDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'day empty';
                calendarGrid.appendChild(emptyDay);
            }

            // Añadir días del mes
            const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
            for (let i = 1; i <= daysInMonth; i++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'day';
                dayElement.textContent = i;
                dayElement.dataset.date = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                // Marcar fines de semana
                const dayOfWeek = new Date(currentDate.getFullYear(), currentDate.getMonth(), i).getDay();
                if (dayOfWeek === 0 || dayOfWeek === 6) {
                    dayElement.classList.add('weekend');
                }

                // Marcar hoy
                if (currentDate.getFullYear() === today.getFullYear() &&
                    currentDate.getMonth() === today.getMonth() &&
                    i === today.getDate()) {
                    dayElement.classList.add('today');
                }

                // Marcar fechas seleccionadas
                if (selectedDates.some(d =>
                    d.getFullYear() === currentDate.getFullYear() &&
                    d.getMonth() === currentDate.getMonth() &&
                    d.getDate() === i)) {
                    dayElement.classList.add('selected');
                }

                // Añadir evento de clic
                dayElement.addEventListener('click', function (e) {
                    e.stopPropagation();
                    handleDateSelection(i);
                });

                calendarGrid.appendChild(dayElement);
            }

            // Actualizar título del mes
            document.getElementById('currentMonth').textContent =
                currentDate.toLocaleString('es-ES', { month: 'long', year: 'numeric' });

            // Actualizar las fechas de las opciones rápidas
            updateQuickDates();
        }

        // 4. Manejar selección de fechas - ACTUALIZADO
        function handleDateSelection(day) {
            const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);

            // SI YA HAY UN RANGO COMPLETO (2 fechas), REINICIAR LA SELECCIÓN
            if (selectedDates.length === 2) {
                // Reiniciar el array y comenzar de nuevo con esta fecha
                selectedDates = [selectedDate];
                fechaDesde.value = formatDate(selectedDate);
                fechaHasta.value = ''; // Limpiar el campo de fecha de fin
                zonaFechas.textContent = 'Selecciona la fecha de fin';
            }
            // SI NO HAY NINGUNA FECHA SELECCIONADA
            else if (selectedDates.length === 0) {
                // Primera fecha seleccionada
                selectedDates = [selectedDate];
                fechaDesde.value = formatDate(selectedDate);
                fechaHasta.value = ''; // Asegurar que esté limpio
                zonaFechas.textContent = 'Selecciona la fecha de fin';
            }
            // SI YA HAY UNA FECHA SELECCIONADA
            else if (selectedDates.length === 1) {
                // Segunda fecha seleccionada
                selectedDates.push(selectedDate);

                // Ordenar fechas
                selectedDates.sort((a, b) => a - b);

                // Actualizar inputs
                fechaDesde.value = formatDate(selectedDates[0]);
                fechaHasta.value = formatDate(selectedDates[1]);

                // Actualizar el texto principal
                zonaFechas.textContent = `${formatDate(selectedDates[0])} - ${formatDate(selectedDates[1])}`;

                // Cerrar el calendario después de seleccionar el rango completo
                setTimeout(() => {
                    dateToggle.classList.remove('focused');
                }, 200);
            }

            renderCalendar();
        }

        // 5. Función para formatear fechas (DD/MM)
        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            return `${day}/${month}`;
        }

        // 6. Manejar opciones rápidas
        document.querySelectorAll('.quick-option').forEach(option => {
            option.addEventListener('click', function (e) {
                e.stopPropagation();
                const range = this.dataset.range;

                // Reiniciar siempre la selección al usar opciones rápidas
                selectedDates = [];

                switch (range) {
                    case 'today':
                        selectedDates = [new Date(2026, 1, 7)];
                        fechaDesde.value = '07/02';
                        fechaHasta.value = '';
                        zonaFechas.textContent = '07/02';
                        break;
                    case 'tomorrow':
                        selectedDates = [new Date(2026, 1, 8)];
                        fechaDesde.value = '08/02';
                        fechaHasta.value = '';
                        zonaFechas.textContent = '08/02';
                        break;
                    case 'weekend':
                        selectedDates = [
                            new Date(2026, 1, 7),
                            new Date(2026, 1, 8)
                        ];
                        fechaDesde.value = '07/02';
                        fechaHasta.value = '08/02';
                        zonaFechas.textContent = '07/02 - 08/02';
                        break;
                }

                renderCalendar();
            });
        });

        // 7. Navegación de meses
        const prevBtn = document.querySelector('.prev-month');
        const nextBtn = document.querySelector('.next-month');

        if (prevBtn) {
            prevBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
                renderCalendar();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
                renderCalendar();
            });
        }
    }
});