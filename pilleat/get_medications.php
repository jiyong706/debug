<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pilleat_db";

if (!isset($_GET['date'])) {
    echo json_encode([]);
    exit;
}

$date = $_GET['date'];
$user_id = $_SESSION['id']; // 로그인된 사용자 ID를 사용

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, dosage FROM medication_history WHERE user_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$result = $stmt->get_result();

$medications = [];
while ($row = $result->fetch_assoc()) {
    $medications[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($medications);
?>
