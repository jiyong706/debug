<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pilleat_db";

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id']; // 로그인된 사용자 ID를 사용

if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $sql = "SELECT name, dosage FROM medication_history WHERE user_id = ? AND date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_id, $date); // 수정된 부분: "ss"로 두 개의 문자열 매개변수 바인딩
    $stmt->execute();
    $result = $stmt->get_result();

    $medications = [];
    while ($row = $result->fetch_assoc()) {
        $medications[] = $row;
    }

    $stmt->close();
    $conn->close();

    echo json_encode($medications);
} else {
    echo json_encode([]);
}
?>
