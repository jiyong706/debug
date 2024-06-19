<?php
include_once "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['id'])) {
        $id = $_POST['id'];

        // 중복된 ID(이메일) 확인
        $check_sql = "SELECT id FROM users WHERE id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $_SESSION['error'] = '아이디가 중복되었습니다.';
            header("Location: signup_step1.php");
            exit();
        }

        $_SESSION['id'] = $id;
    } else {
        $_SESSION['error'] = '아이디를 입력해주세요.';
        header("Location: signup_step1.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입 - Step 2</title>
    <link rel="stylesheet" href="signup.css">
    <script>
        window.onload = function() {
            <?php
            if (isset($_SESSION['error'])) {
                echo 'alert("' . $_SESSION['error'] . '");';
                unset($_SESSION['error']);
            }
            if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
                $_SESSION['error'] = '아이디를 입력해주세요.';
                header("Location: signup_step1.php");
                exit();
            }
            ?>
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>비밀번호를 입력해주세요</h1>
            <form action="signup_step3.php" method="POST">
                <div class="field">
                    <input type="text" name="fname" placeholder="성" required>
                </div>
                <div class="field">
                    <input type="text" name="lname" placeholder="이름" required>
                </div>
                <div class="field">
                    <input type="text" name="birthdate" placeholder="생년월일" required>
                </div>
                <div class="field">
                    <input type="password" name="password" placeholder="비밀번호" required>
                </div>
                <div class="field">
                    <input type="password" name="confirm_password" placeholder="비밀번호 확인" required>
                </div>
                <div class="field">
                    <input type="submit" value="계속하기">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
