<?php
include_once "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['birthdate'])) {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $_SESSION['error'] = '비밀번호가 일치하지 않습니다.';
            header("Location: signup_step2.php");
            exit();
        }

        $id = $_SESSION['id'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $birthdate = $_POST['birthdate'];

        $sql = "INSERT INTO users (id, fname, lname, password, birthdate) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $id, $fname, $lname, $password, $birthdate);

        if ($stmt->execute()) {
            header("Location: signup_success.php");
            exit();
        } else {
            $_SESSION['error'] = '회원가입에 실패했습니다.';
            header("Location: signup_step2.php");
            exit();
        }
    } else {
        $_SESSION['error'] = '모든 필드를 입력해주세요.';
        header("Location: signup_step2.php");
        exit();
    }
} else {
    $_SESSION['error'] = '잘못된 요청입니다.';
    header("Location: signup_step2.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입 - Step 3</title>
    <link rel="stylesheet" href="signup.css">
    <script>
        window.onload = function() {
            <?php
            if (isset($_SESSION['error'])) {
                echo 'alert("' . $_SESSION['error'] . '");';
                unset($_SESSION['error']);
            }
            ?>
        };
    </script>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>회원 정보 입력</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
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
                    <input type="submit" value="가입 완료">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
