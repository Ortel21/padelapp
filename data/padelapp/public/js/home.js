document.addEventListener("DOMContentLoaded", () => {
    const weekSelector = document.getElementById("week-selector");
    const currentWeekDisplay = document.getElementById("current-week");
    let currentStartDate = new Date();
    let currentDayOffset = 0;

    const dayButtons = document.querySelectorAll('.day-btn');

    let slotDurationMinutes = 90; 

    function updateCurrentWeekDisplay(startDate) {
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 6);
        currentWeekDisplay.textContent = `${startDate.toLocaleDateString('es-ES')} - ${endDate.toLocaleDateString('es-ES')}`;

        dayButtons.forEach((button, index) => {
            const dayDate = new Date(startDate);
            dayDate.setDate(dayDate.getDate() + index);
            button.textContent = `${button.getAttribute('data-original-day')} ${dayDate.getDate()}`;
        });
    }

    function fetchWeeklyReservations(startDate) {
        generateTimeSlots(startDate, '1');

        generateTimeSlots(startDate, '2');
    }

    function generateTimeSlots(startDate, courtId) {
        const slotsContainer = document.getElementById(`time-slots-${courtId}`);
        slotsContainer.innerHTML = ''; 

        const hoursInDay = 24;
        const defaultSlotDuration = 90;
        const slotsPerDay = Math.ceil((hoursInDay * 60) / defaultSlotDuration);

        const selectedDate = new Date(startDate);
        selectedDate.setDate(selectedDate.getDate() + currentDayOffset);

        const dayHeader = document.getElementById('current-day-header');
        dayHeader.textContent = `${selectedDate.toLocaleDateString('es-ES', { weekday: 'long' })}, ${selectedDate.toLocaleDateString('es-ES')}`;

        fetch(`/api/reservations?court_number=${courtId}&date=${selectedDate.toISOString().split('T')[0]}`)
            .then(response => response.json())
            .then(reservations => {
                const reservedSlots = reservations.map(reservation => {
                    const start = new Date(reservation.start_time);
                    const duration = reservation.duration_minutes || defaultSlotDuration;
                    const end = new Date(start.getTime() + duration * 60000);
                    return { start, end, duration };
                });

                const now = new Date();

                for (let i = 0; i < slotsPerDay; i++) {
                    const startMinutes = i * defaultSlotDuration;
                    const endMinutes = startMinutes + defaultSlotDuration;

                    const startTime = new Date(selectedDate);
                    startTime.setHours(0, startMinutes, 0, 0);
                    const endTime = new Date(selectedDate);
                    endTime.setHours(0, endMinutes, 0, 0);

                    const formatTime = time => time.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit", hour12: false });

                    let slotClass = "free";
                    let slotDuration = defaultSlotDuration; 

                    for (const reservation of reservedSlots) {
                        if (
                            (startTime >= reservation.start && startTime < reservation.end) ||
                            (endTime > reservation.start && endTime <= reservation.end)
                        ) {
                            slotClass = "filled";
                            slotDuration = reservation.duration;
                            break;
                        }
                    }

                    const blockTime = new Date(endTime.getTime() - 20 * 60000);
                    if (blockTime <= now) {
                        slotClass = "no_avalible";
                    }

                    const slotDiv = document.createElement("div");
                    slotDiv.className = `minutes_width reserve_space ${slotClass}`;
                    slotDiv.setAttribute("data-placement", "top");

                    if (slotClass === "free") {
                        slotDiv.addEventListener("click", () => {
                            window.location.href = `/reserve?court=${courtId}&start=${startTime.toLocaleString('sv-SE')}Z&end=${endTime.toLocaleString('sv-SE')}Z`;
                        });
                    }

                    let endTimeAdjusted = new Date(startTime.getTime() + slotDuration * 60000);
                    let endTimeFormatted = formatTime(endTimeAdjusted);

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

    function changeWeek(offset) {
        currentStartDate.setDate(currentStartDate.getDate() + offset * 7);
        updateCurrentWeekDisplay(currentStartDate);
        fetchWeeklyReservations(currentStartDate);
    }

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

    fetchWeeklyReservations(currentStartDate);
    updateCurrentWeekDisplay(currentStartDate);
});
