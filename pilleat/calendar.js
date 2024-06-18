document.addEventListener('DOMContentLoaded', function() {
    const dateButtonsContainer = document.getElementById('date-buttons');
    if (!dateButtonsContainer) {
        console.error('date-buttons element not found');
        return;
    }

    const dateButtons = [];
    const today = new Date();
    const dayOfWeek = today.getDay(); // 0 (일요일) ~ 6 (토요일)
    const daysOfWeek = ['일', '월', '화', '수', '목', '금', '토'];

    // 현재 날짜에서 해당 주의 시작일(일요일)을 계산
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - dayOfWeek);

    for (let i = 0; i < 7; i++) {
        const date = new Date(startOfWeek);
        date.setDate(startOfWeek.getDate() + i);

        const day = date.getDate();
        const dayOfWeekLabel = daysOfWeek[date.getDay()];

        const button = document.createElement('button');
        button.classList.add('date-btn');
        button.innerHTML = `${day}<br>${dayOfWeekLabel}`;
        
        if (date.toDateString() === today.toDateString()) {
            button.classList.add('active');
        }

        button.dataset.date = date.toISOString().split('T')[0]; // 날짜 데이터를 저장
        dateButtons.push(button);
        dateButtonsContainer.appendChild(button);
    }

    dateButtons.forEach(button => {
        button.addEventListener('click', function() {
            dateButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            loadMedicationStatus(this.dataset.date); // 날짜 변경 시 복약 상태를 로드
        });
    });

    // 복용완료 버튼 상태 로드 함수
    function loadMedicationStatus(date) {
        const medicationButtons = document.querySelectorAll('.medication-time .label.button');
        const currentTime = new Date();
        const currentHour = currentTime.getHours();
        const currentMinutes = currentTime.getMinutes();

        medicationButtons.forEach(button => {
            const time = button.previousElementSibling.textContent;
            const [hour, minutes] = time.split(':').map(Number);
            const key = `medication-${date}-${time}`;
            const isCompleted = localStorage.getItem(key) === 'true';

            if (isCompleted) {
                button.classList.add('completed');
                button.textContent = '복용완료';
            } else {
                button.classList.remove('completed');
                button.textContent = '확인 버튼';
            }

            // 현재 시간 강조
            if (date === currentTime.toISOString().split('T')[0]) {
                const medicationTime = new Date(currentTime);
                medicationTime.setHours(hour, minutes, 0, 0);

                const nextTimeElement = button.closest('.medication-time').nextElementSibling;
                let nextTime = new Date(medicationTime);
                if (nextTimeElement) {
                    const nextTimeText = nextTimeElement.querySelector('.time').textContent;
                    const [nextHour, nextMinutes] = nextTimeText.split(':').map(Number);
                    nextTime.setHours(nextHour, nextMinutes, 0, 0);
                } else {
                    nextTime.setHours(23, 59, 59, 999); // 마지막 시간은 하루의 끝으로 설정
                }

                if (currentTime >= medicationTime && currentTime < nextTime) {
                    button.closest('.medication-time').classList.add('current');
                } else {
                    button.closest('.medication-time').classList.remove('current');
                }
            } else {
                button.closest('.medication-time').classList.remove('current');
            }

            button.onclick = function() {
                const currentState = button.classList.toggle('completed');
                button.textContent = currentState ? '복용완료' : '확인 버튼';
                localStorage.setItem(key, currentState);
            };
        });
    }

    // 초기 로드 시 오늘 날짜의 복약 상태를 로드
    const activeButton = document.querySelector('.date-btn.active');
    if (activeButton) {
        loadMedicationStatus(activeButton.dataset.date);
    }
});
