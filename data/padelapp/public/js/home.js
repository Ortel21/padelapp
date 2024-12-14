document.addEventListener("DOMContentLoaded", () => {
    const weekSelector = document.getElementById("week-selector");
    const currentWeekDisplay = document.getElementById("current-week");
    let currentStartDate = new Date('2024-12-14'); // Fecha inicial: Sábado, 14 de Diciembre
    let currentDayOffset = 0;

    const dayButtons = document.querySelectorAll('.day-btn');

    // Configuración inicial
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

    function fetchWeeklyReservations(startDate) {
        // Para pista 1
        generateTimeSlots(startDate, '1');
        
        // Para pista 2
        generateTimeSlots(startDate, '2');
    }

    function generateTimeSlots(startDate, courtId) {
        const slotsContainer = document.getElementById(`time-slots-${courtId}`);
        slotsContainer.innerHTML = ''; // Limpiar las franjas horarias previas
        const now = new Date();
        const hoursInDay = 24;
        const slotDurationMinutes = 90; // Duración de cada franja horaria (en minutos)
        const slotsPerDay = Math.ceil((hoursInDay * 60) / slotDurationMinutes);

        const selectedDate = new Date(startDate);
        selectedDate.setDate(selectedDate.getDate() + currentDayOffset);
        const dayHeader = document.getElementById('current-day-header');
        dayHeader.textContent = `${selectedDate.toLocaleDateString('es-ES', { weekday: 'long' })}, ${selectedDate.toLocaleDateString('es-ES')}`;

        for (let i = 0; i < slotsPerDay; i++) {
            const startMinutes = i * slotDurationMinutes;
            const endMinutes = startMinutes + slotDurationMinutes;
            const startTime = new Date(selectedDate);
            startTime.setHours(0, startMinutes, 0, 0);
            const endTime = new Date(selectedDate);
            endTime.setHours(0, endMinutes, 0, 0);

            const formatTime = (time) => time.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit", hour12: false });

            const startFormatted = formatTime(startTime);
            const endFormatted = formatTime(endTime);

            let slotClass = "free";
            const blockTime = new Date(endTime.getTime() - 20 * 60000); // 20 minutos antes de la hora de finalización
            if (blockTime <= now) {
                slotClass = "no_reservable";
            }

            // Crear el div de la franja
            const slotDiv = document.createElement("div");
            slotDiv.className = `minutes_width reserve_space ${slotClass}`;
            slotDiv.setAttribute("data-placement", "top");

            if (slotClass === "free") {
                slotDiv.addEventListener("click", () => {
                    window.location.href = `/reserve?court=${courtId}&start=${startTime.toISOString()}&end=${endTime.toISOString()}`;
                });
            }

            slotDiv.innerHTML = `
                <div class="time">
                    <span class="start_time">${startFormatted}</span>
                    <span> - </span>
                    <span class="end_time">${endFormatted}</span>
                </div>
                <div class="info-card"></div>
            `;

            slotsContainer.appendChild(slotDiv);
        }
    }

    function changeWeek(offset) {
        currentStartDate.setDate(currentStartDate.getDate() + offset * 7);
        updateCurrentWeekDisplay(currentStartDate);  // Asegurarnos de actualizar los botones de días también
        fetchWeeklyReservations(currentStartDate);
    }

    // Configurar atributos originales para los días (esto es necesario para recordar los nombres de los días cuando se actualiza el texto de los botones)
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
