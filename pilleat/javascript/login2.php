<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PillEat 로그인</title>
    <link rel="stylesheet" href="login2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <header class="pilleat">PillEat</header>
            <div class="form">
                <p class="login_text">로그인 하여 PillEat!</p>
                <form action="login_process.php" method="POST">
                    <div class="field">
                        <input type="text" name="id" placeholder="id" required>
                    </div>
                    <div class="field">
                        <input type="password" name="pw" placeholder="pw" required>
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="link">
                        <a href="find_info.php" class="find_info">회원정보 찾기</a>
                        <a href="signup.php" class="signup">회원가입</a>
                    </div>
                    <div class="field">
                        <input type="submit" value="로그인" class="button_new">
                    </div>
                </form>
            </div>
            <footer class="footer">
                <p class="team_debug">Team_debug</p>
                <div class="social-icons">
                    <div class="buttons_icon">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                    </div>
                    <div class="buttons_icon">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <div class="buttons_icon">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                    <div class="buttons_icon">
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <a href="admin_login.php" class="admin_login">관리자 로그인</a>
            </footer>
        </div>
    </div>
    <script src="pass-show-hide.js"></script>
</body>
</html>
