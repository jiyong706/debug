document.addEventListener('DOMContentLoaded', function() {
    const dateButtonsContainer = document.getElementById('date-buttons');
    if (!dateButtonsContainer) {
        console.error('date-buttons element not found');
        return;
    }

    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();

    function createCalendar(year, month) {
        dateButtonsContainer.innerHTML = '';

        const firstDay = new Date(year, month).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        const daysOfWeek = ['일', '월', '화', '수', '목', '금', '토'];

        // Create empty cells for days before the first day of the month
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.classList.add('date-btn', 'empty');
            dateButtonsContainer.appendChild(emptyCell);
        }

        // Create cells for each day of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dayOfWeekLabel = daysOfWeek[date.getDay()];

            const button = document.createElement('button');
            button.classList.add('date-btn');
            button.innerHTML = `${day}<br>${dayOfWeekLabel}`;
            button.dataset.date = date.toISOString().split('T')[0]; // 날짜 데이터를 저장

            if (date.toDateString() === today.toDateString()) {
                button.classList.add('active');
            }

            button.addEventListener('click', function() {
                window.location.href = `calendar.php?date=${this.dataset.date}`;
            });

            dateButtonsContainer.appendChild(button);
        }
    }

    createCalendar(currentYear, currentMonth);
});
