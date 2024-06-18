<?php
include_once "config.php";

// 데이터베이스 연결이 설정되었는지 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 오류: " . $conn->connect_error);
}

// 폼이 제출되었는지 확인
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 각 입력 필드가 존재하는지 확인하고 값을 할당
    $fname = isset($_POST['fname']) ? mysqli_real_escape_string($conn, $_POST['fname']) : '';
    $lname = isset($_POST['lname']) ? mysqli_real_escape_string($conn, $_POST['lname']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, $_POST['password']) : '';

    // 입력 필드가 모두 존재하는지 확인
    if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
        // 데이터베이스에 사용자 추가
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // 비밀번호 해시화
        $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fname, $lname, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "회원가입이 성공적으로 완료되었습니다!";
        } else {
            echo "오류: " . $stmt->error;
        }
    } else {
        echo "모든 입력 필드를 채워주세요!";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>효율적으로 약관리를 시작해봐요</h1>
            <p>먼저 사용하시는 아이디를 입력해주세요</p>
            <form action="signup.php" method="POST">
                <div class="field">
                    <input type="text" name="fname" placeholder="First Name" required>
                </div>
                <div class="field">
                    <input type="text" name="lname" placeholder="Last Name" required>
                </div>
                <div class="field">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="field">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="field">
                    <input type="submit" value="계속하기">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
