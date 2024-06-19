<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pilleat_db"; // 변경된 데이터베이스 이름

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id']; // 로그인된 사용자 ID를 사용

// 현재 날짜를 가져옵니다.
$currentDate = new DateTime();
$year = isset($_GET['year']) ? $_GET['year'] : $currentDate->format('Y');
$month = isset($_GET['month']) ? $_GET['month'] : $currentDate->format('m');
$selectedDate = isset($_GET['date']) ? $_GET['date'] : null;

// 월의 시작과 끝 날짜를 가져옵니다.
$startOfMonth = new DateTime("$year-$month-01");
$endOfMonth = new DateTime($startOfMonth->format('Y-m-t'));

// 특정 월의 복용 완료된 약 정보 조회
$sql = "SELECT date, name, dosage FROM medication_history WHERE user_id = ? AND date BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$startDate = $startOfMonth->format('Y-m-d');
$endDate = $endOfMonth->format('Y-m-d');
$stmt->bind_param("sss", $user_id, $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

$medications = [];
while ($row = $result->fetch_assoc()) {
    $date = $row['date'];
    if (!isset($medications[$date])) {
        $medications[$date] = [];
    }
    $medications[$date][] = $row;
}

$stmt->close();
$conn->close();

// 이전 달과 다음 달 계산
$prevMonth = clone $startOfMonth;
$prevMonth->modify('-1 month');
$nextMonth = clone $startOfMonth;
$nextMonth->modify('+1 month');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PillEat - Calendar</title>
    <link rel="stylesheet" href="index.css">
    <style>
        .calendar-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .month-navigation {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 800px;
            margin-bottom: 20px;
        }
        .month-navigation a {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .month-navigation a:hover {
            background-color: #0056b3;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }
        .calendar-grid div {
            background-color: #f0f0f0;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .calendar-grid div:hover {
            background-color: #d0d0d0;
        }
        .calendar-grid .header {
            background-color: #007bff;
            color: white;
        }
        .calendar-grid .day {
            min-height: 100px;
            position: relative;
        }
        .calendar-grid .day.completed {
            background-color: #a0e3a0;
        }
        .medications {
            margin-top: 10px;
            text-align: left;
        }
        .medication-details {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        .main-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .main-btn:hover {
            background-color: #0056b3;
        }
        .camera-icon img {
            width: 30px; /* 아이콘의 너비를 설정합니다. */
            height: auto; /* 비율을 유지하면서 높이를 자동으로 설정합니다. */
        }
    </style>
</head>
<body>
    <header>
        <div class="nav">
            <div class="logo">PillEat</div>
            <div class="menu">
                <a href="search.php">약 검색</a>
                <a href="check.php">복용중인 약 체크</a>
                <a href="expert.php">전문가 소개</a>
                <a href="logout.php" class="login-btn">로그아웃</a>
            </div>
        </div>
    </header>
    <main>
        <div class="content">
            <div class="calendar-container">
                <div class="month-navigation">
                    <a href="?year=<?php echo $prevMonth->format('Y'); ?>&month=<?php echo $prevMonth->format('m'); ?>">이전 달</a>
                    <h2><?php echo $startOfMonth->format('Y년 m월'); ?></h2>
                    <a href="?year=<?php echo $nextMonth->format('Y'); ?>&month=<?php echo $nextMonth->format('m'); ?>">다음 달</a>
                </div>
                <div class="calendar-grid">
                    <div class="header">일</div>
                    <div class="header">월</div>
                    <div class="header">화</div>
                    <div class="header">수</div>
                    <div class="header">목</div>
                    <div class="header">금</div>
                    <div class="header">토</div>
                    <?php
                    // 월의 시작 요일 전까지 빈 칸 추가
                    for ($i = 0; $i < $startOfMonth->format('w'); $i++) {
                        echo '<div></div>';
                    }

                    // 월의 일수만큼 칸 추가
                    for ($day = 1; $day <= $endOfMonth->format('d'); $day++) {
                        $date = $startOfMonth->format("Y-m-") . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $completed = isset($medications[$date]) ? 'completed' : '';
                        echo "<div class='day $completed' data-date='$date'>$day";
                        if (isset($medications[$date])) {
                            echo "<div class='medications'>";
                            foreach ($medications[$date] as $medication) {
                                echo "<div>" . htmlspecialchars($medication['name']) . " - " . htmlspecialchars($medication['dosage']) . "</div>";
                            }
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
            <div class="medication-details">
                <h2>복용 완료된 약</h2>
                <div id="medication-details-container">
                    <?php
                    if ($selectedDate && isset($medications[$selectedDate])) {
                        echo "<h3>$selectedDate</h3>";
                        foreach ($medications[$selectedDate] as $medication) {
                            echo "<div>" . htmlspecialchars($medication['name']) . " - " . htmlspecialchars($medication['dosage']) . "</div>";
                        }
                    } else {
                        echo "<p>해당 날짜에 복용 완료된 약이 없습니다.</p>";
                    }
                    ?>
                </div>
                <a href="index.php" class="main-btn">메인으로</a>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.day.completed').forEach(day => {
                day.addEventListener('click', function() {
                    const date = this.dataset.date;
                    window.location.href = `calendar.php?date=${date}&year=${<?php echo $year; ?>}&month=${<?php echo $month; ?>}`;
                });
            });
        });
    </script>
</body>
</html>
