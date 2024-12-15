document.addEventListener("DOMContentLoaded", () => {
    const weekSelector = document.getElementById("week-selector");
    const currentWeekDisplay = document.getElementById("current-week");
    let currentStartDate = new Date();
    let currentDayOffset = 0;

    const dayButtons = document.querySelectorAll('.day-btn');

    let slotDurationMinutes = 90; 

    // Función para actualizar la visualización de la semana
    function updateCurrentWeekDisplay(startDate) {
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 6);
        currentWeekDisplay.textContent = `${startDate.toLocaleDateString('es-ES')} - ${endDate.toLocaleDateString('es-ES')}`;

        // Actualizar botones de días con las fechas correspondientes
        dayButtons.forEach((button, index) => {
            const dayDate = new Date(startDate);
            dayDate.setDate(dayDate.getDate() + index);
            button.textContent = `${button.getAttribute('data-original-day')} ${dayDate.getDate()}`;
        });
    }

    // Función para obtener las reservas de la semana
    function fetchWeeklyReservations(startDate) {
        // Para pista 1
        generateTimeSlots(startDate, '1');

        // Para pista 2
        generateTimeSlots(startDate, '2');
    }

    // Función para generar franjas horarias
    function generateTimeSlots(startDate, courtId) {
        const slotsContainer = document.getElementById(`time-slots-${courtId}`);
        slotsContainer.innerHTML = ''; // Limpiar slots antes de crear nuevos

        const hoursInDay = 24;
        const defaultSlotDuration = 90;
        const slotsPerDay = Math.ceil((hoursInDay * 60) / defaultSlotDuration);

        const selectedDate = new Date(startDate);
        selectedDate.setDate(selectedDate.getDate() + currentDayOffset);

        const dayHeader = document.getElementById('current-day-header');
        dayHeader.textContent = `${selectedDate.toLocaleDateString('es-ES', { weekday: 'long' })}, ${selectedDate.toLocaleDateString('es-ES')}`;

        // Obtener reservas desde el backend
        fetch(`/api/reservations?court_number=${courtId}&date=${selectedDate.toISOString().split('T')[0]}`)
            .then(response => response.json())
            .then(reservations => {
                // Mapear las reservas para que tengan start, end (con la duración)
                const reservedSlots = reservations.map(reservation => {
                    const start = new Date(reservation.start_time);
                    const duration = reservation.duration_minutes || defaultSlotDuration; // Tomar la duración de la base de datos, sino usar 90 minutos
                    const end = new Date(start.getTime() + duration * 60000); // Calcula el final en función de la duración
                    return { start, end, duration };
                });

                const now = new Date(); // Fecha y hora actual

                // Iterar sobre cada slot de tiempo
                for (let i = 0; i < slotsPerDay; i++) {
                    const startMinutes = i * defaultSlotDuration;
                    const endMinutes = startMinutes + defaultSlotDuration;

                    const startTime = new Date(selectedDate);
                    startTime.setHours(0, startMinutes, 0, 0);
                    const endTime = new Date(selectedDate);
                    endTime.setHours(0, endMinutes, 0, 0);

                    const formatTime = time => time.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit", hour12: false });

                    let slotClass = "free";
                    let slotDuration = defaultSlotDuration; // Duración del slot, que por defecto es 90

                    // Verificar si el slot está reservado, teniendo en cuenta la duración
                    for (const reservation of reservedSlots) {
                        if (
                            (startTime >= reservation.start && startTime < reservation.end) ||
                            (endTime > reservation.start && endTime <= reservation.end)
                        ) {
                            slotClass = "filled";
                            slotDuration = reservation.duration; // Asignar la duración de la reserva a este slot
                            break;
                        }
                    }

                    // Verificar si el slot es no disponible (menos de 20 minutos antes de que termine)
                    const blockTime = new Date(endTime.getTime() - 20 * 60000); // 20 minutos antes de la hora de finalización
                    if (blockTime <= now) {
                        slotClass = "no_avalible";
                    }

                    // Crear el div para el slot de tiempo
                    const slotDiv = document.createElement("div");
                    slotDiv.className = `minutes_width reserve_space ${slotClass}`;
                    slotDiv.setAttribute("data-placement", "top");

                    if (slotClass === "free") {
                        slotDiv.addEventListener("click", () => {
                            window.location.href = `/reserve?court=${courtId}&start=${startTime.toLocaleString('sv-SE')}Z&end=${endTime.toLocaleString('sv-SE')}Z`;
                        });
                    }

                    // Actualizar el texto del horario según la duración
                    let endTimeAdjusted = new Date(startTime.getTime() + slotDuration * 60000); // Ajustamos el endTime según la duración
                    let endTimeFormatted = formatTime(endTimeAdjusted); // Formatear el nuevo tiempo de finalización

                    slotDiv.innerHTML = `
                        <div class="time">
                            <span class="start_time">${formatTime(startTime)}</span>
                            <span> - </span>
                            <span class="end_time">${endTimeFormatted}</span>
                        </div>
                        <div class="info-card">${slotClass === "filled" ? "<p>Reservado</p>" : ""}</div>
                    `;

                    slotsContainer.appendChild(slotDiv);
                }
            })
            .catch(error => console.error('Error al cargar las reservas:', error));
    }

    // Cambiar de semana
    function changeWeek(offset) {
        currentStartDate.setDate(currentStartDate.getDate() + offset * 7);
        updateCurrentWeekDisplay(currentStartDate);
        fetchWeeklyReservations(currentStartDate);
    }

    // Configurar atributos originales para los días
    dayButtons.forEach(button => {
        button.setAttribute('data-original-day', button.textContent.split(' ')[0]);
    });

    dayButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const dayOffset = parseInt(e.target.getAttribute('data-day'));
            currentDayOffset = dayOffset;
            fetchWeeklyReservations(currentStartDate);
        });
    });

    document.getElementById("prev-week-btn").addEventListener("click", () => changeWeek(-1));
    document.getElementById("next-week-btn").addEventListener("click", () => changeWeek(1));

    fetchWeeklyReservations(currentStartDate); // Inicializar la vista con la semana actual
    updateCurrentWeekDisplay(currentStartDate);
});
