document.addEventListener("DOMContentLoaded", () => {
    const slotsContainer = document.getElementById("time-slots");
    const currentDayHeader = document.getElementById("current-day-header");
    const currentBadge = document.getElementById("current-badge");

    const now = new Date();
    const defaultSlotDurationMinutes = 90;
    const courtId = 1;

    const reservations = [
        { start: "2024-12-14T00:00:00", end: "2024-12-14T01:00:00" },
        { start: "2024-12-14T02:00:00", end: "2024-12-14T03:30:00" }
    ];

    const dayNames = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    const today = `${dayNames[now.getDay()]}, ${now.getDate()} ${monthNames[now.getMonth()]} ${now.getFullYear()}`;

    currentDayHeader.textContent = today;
    currentBadge.textContent = `${String(now.getDate()).padStart(2, "0")}/${String(now.getMonth() + 1).padStart(2, "0")}`;

    let currentStartTime = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0);

    while (currentStartTime.getDate() === now.getDate()) {
        const overlappingReservation = reservations.length > 0
            ? reservations.find(res => {
                const resStart = new Date(res.start);
                const resEnd = new Date(res.end);
                return (
                    currentStartTime >= resStart && currentStartTime < resEnd ||
                    currentStartTime.getTime() + defaultSlotDurationMinutes * 60 * 1000 > resStart.getTime()
                );
            })
            : null;

        let endSlotTime = overlappingReservation
            ? new Date(overlappingReservation.end)
            : new Date(currentStartTime.getTime() + defaultSlotDurationMinutes * 60 * 1000);

        const formatTime = (time) =>
            time.toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit",
                hour12: false
            });

        const startFormatted = formatTime(currentStartTime);
        const endFormatted = formatTime(endSlotTime);

        let slotClass = "free";
        if (currentStartTime < now) {
            slotClass = "no_reservable";
        }

        const slotDiv = document.createElement("div");
        slotDiv.className = `minutes_width reserve_space ${slotClass}`;
        slotDiv.setAttribute("data-placement", "top");

        slotDiv.innerHTML = `
        <div class="time">
            <span class="start_time">${startFormatted}</span>
            <span> - </span>
            <span class="end_time">${endFormatted}</span>
        </div>
        <div class="info-card">
            ${
                slotClass === "free"
                    ? `<a href="/reserve.blade?court=${courtId}&start=${currentStartTime.toISOString()}&end=${endSlotTime.toISOString()}">
                        <div>
                        </div>
                      </a>`
                    : ""
            }
        </div>
    `;

        slotsContainer.appendChild(slotDiv);

        currentStartTime = endSlotTime;
    }

    const millisTillMidnight =
        new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1).getTime() - now.getTime();
    setTimeout(() => {
        location.reload();
    }, millisTillMidnight);
});
