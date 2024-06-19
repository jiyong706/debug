<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

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
echo "User ID (Check): " . htmlspecialchars($user_id); // 디버깅용으로 추가

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['completed'])) {
    $medication_ids = $_POST['completed'];
    $date = date('Y-m-d');

    foreach ($medication_ids as $medication_id) {
        $stmt = $conn->prepare("SELECT name, dosage FROM medication WHERE id = ?");
        $stmt->bind_param("i", $medication_id);
        $stmt->execute();
        $stmt->bind_result($name, $dosage);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO medication_history (user_id, medication_id, name, dosage, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $user_id, $medication_id, $name, $dosage, $date);
        if ($stmt->execute()) {
            echo "Inserted into medication_history: $name - $dosage";
        } else {
            echo "Error inserting into medication_history: " . $stmt->error;
        }
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM medication WHERE id = ?");
        $stmt->bind_param("i", $medication_id);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT id, name, dosage FROM medication WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$medications = [];
while ($row = $result->fetch_assoc()) {
    $medications[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PillEat - Check Medications</title>
    <link rel="stylesheet" href="check.css">
</head>
<body>
    <header>
        <div class="nav">
            <div class=" logo"><a href="index.php">PillEat</div>
            <div class="menu">
                <a href="search.php">약 검색</a>
                <a href="index.php">메인</a>
                <a href="expert.php">전문가 소개</a>
                <a href="logout.php" class="login-btn">로그아웃</a>
            </div>
        </div>
    </header>
    <main>
        <div class="content">
            <h2>복용중인 약 체크</h2>
            <form method="post" action="check.php">
                <?php if (!empty($medications)): ?>
                    <div class="cards">
                        <?php foreach ($medications as $medication): ?>
                            <div class="card">
                                <h3><?php echo htmlspecialchars($medication['name']); ?></h3>
                                <p><?php echo htmlspecialchars($medication['dosage']); ?></p>
                                <input type="checkbox" name="completed[]" value="<?php echo $medication['id']; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit">복용완료</button>
                <?php else: ?>
                    <p>복용중인 약이 없습니다.</p>
                <?php endif; ?>
            </form>
        </div>
    </main>
</body>
</html>
