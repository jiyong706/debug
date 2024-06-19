<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$id = $_SESSION['id'];
$query = $conn->prepare("SELECT fname, lname FROM users WHERE id = ?");
$query->bind_param("s", $id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>PillEat</title>
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
            <div class="left-panel">
                <div class="board">
                    <h3>즐겨찾는 게시판</h3>
                    <a href="#">더보기 ></a>
                </div>
            </div>
            <div class="right-panel">
                <h2><?php echo htmlspecialchars($user['fname']) . " " . htmlspecialchars($user['lname']); ?> 님의 복약내역</h2>
                <div class="medication-schedule">
                    <div class="medication-time">
                        <div class="time">07:30</div>
                        <div class="label button" onclick="markCompleted(this)">확인 버튼</div>
                    </div>
                    <div class="medication-time">
                        <div class="time">12:00</div>
                        <div class="label button" onclick="markCompleted(this)">확인 버튼</div>
                    </div>
                    <div class="medication-time">
                        <div class="time">19:00</div>
                        <div class="label button" onclick="markCompleted(this)">확인 버튼</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <p>Site name</p>
                <div class="social-icons">
                    <a href="#"><img src="path/to/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="path/to/linkedin.png" alt="LinkedIn"></a>
                    <a href="#"><img src="path/to/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="path/to/youtube.png" alt="YouTube"></a>
                </div>
            </div>
            <div class="footer-section">
                <p>Topic</p>
                <a href="#">Page</a>
                <a href="#">Page</a>
                <a href="#">Page</a>
            </div>
            <div class="footer-section">
                <p>Topic</p>
                <a href="#">Page</a>
                <a href="#">Page</a>
                <a href="#">Page</a>
            </div>
            <div class="footer-section">
                <p>Topic</p>
                <a href="#">Page</a>
                <a href="#">Page</a>
                <a href="#">Page</a>
            </div>
        </div>
    </footer>
    <script src="calendar.js"></script>
    <script>
        function markCompleted(element) {
            element.classList.add('completed');
            element.innerText = '복용 완료';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinutes = now.getMinutes();

            const times = document.querySelectorAll('.medication-time .time');

            times.forEach(function(timeElement, index) {
                const [hour, minutes] = timeElement.textContent.split(':').map(Number);

                if (index > 0) {
                    const prevTimeElement = times[index - 1];
                    const [prevHour, prevMinutes] = prevTimeElement.textContent.split(':').map(Number);

                    if ((currentHour > prevHour || (currentHour === prevHour && currentMinutes >= prevMinutes)) &&
                        (currentHour < hour || (currentHour === hour && currentMinutes < minutes))) {
                        timeElement.parentElement.classList.add('current');
                    }
                }
            });
        });
    </script>
</body>
</html>
