<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>PillEat</title>
    <style>
        .camera-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .camera-icon img {
            width: 30px; /* 아이콘 크기 */
            height: 30px;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav">
            <div class="camera-icon">
                <a href="upload.php"><img src="camera_icon.png" alt="Upload"></a> <!-- 카메라 아이콘 이미지 경로 설정 -->
            </div>
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
                <h2>
                    <?php 
                    if (isset($_SESSION['fname']) && isset($_SESSION['lname'])) {
                        echo htmlspecialchars($_SESSION['fname'] . " " . $_SESSION['lname']); 
                    } else {
                        echo "사용자";
                    } 
                    ?> 님의 복약내역
                </h2>
                <div class="calendar">
                    <div class="date-buttons" id="date-buttons">
                        <!-- 날짜 버튼은 JS에서 생성됩니다 -->
                    </div>
                </div>
                <div class="medication-schedule">
                    <div class="medication-time">
                        <div class="time">07:30</div>
                        <div class="label button">확인 버튼</div>
                    </div>
                    <div class="medication-time">
                        <div class="time">12:00</div>
                        <div class="label button">확인 버튼</div>
                    </div>
                    <div class="medication-time">
                        <div class="time">19:00</div>
                        <div class="label button">확인 버튼</div>
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
        </div>
    </footer>
    <script src="calendar.js"></script>
</body>
</html>
