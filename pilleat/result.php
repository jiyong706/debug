<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pilleat_db"; // 변경된 데이터베이스 이름

function get_drug_info($text, $conn) {
    $sql = "SELECT DISTINCT name, dosage FROM drugs WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $like_text = "%$text%";
    $stmt->bind_param("s", $like_text);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function display_results($lines, $conn) {
    $found_drugs = [];
    $unique_lines = array_unique(array_map('trim', $lines)); // 중복된 텍스트 제거

    foreach ($unique_lines as $line) {
        $drug_info = get_drug_info($line, $conn);
        if (!empty($drug_info)) {
            foreach ($drug_info as $drug) {
                $found_drugs[] = $drug;
            }
        }
    }

    if (!empty($found_drugs)) {
        $unique_drugs = array_unique($found_drugs, SORT_REGULAR); // 중복된 약 정보 제거
        return $unique_drugs;
    } else {
        return [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['extracted_text'])) {
    $extracted_text = $_POST['extracted_text'];
    $_SESSION['extracted_text'] = $extracted_text; // 세션에 저장
} else if (isset($_SESSION['extracted_text'])) {
    $extracted_text = $_SESSION['extracted_text'];
} else {
    echo "추출된 텍스트가 없습니다.";
    exit;
}

$lines = explode("\n", $extracted_text);

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$found_drugs = display_results($lines, $conn);

$message = "";
$message_class = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_drugs'])) {
    $user_id = $_SESSION['id'];
    foreach ($found_drugs as $drug) {
        $stmt = $conn->prepare("INSERT INTO medication (name, dosage, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $drug['name'], $drug['dosage'], $user_id); // user_id는 VARCHAR이므로 sss로 변경
        if ($stmt->execute()) {
            $message = "약 정보가 성공적으로 저장되었습니다.";
            $message_class = "success";
        } else {
            $message = "약 정보 저장에 실패했습니다: " . $stmt->error;
            $message_class = "error";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PillEat</title>
    <link rel="stylesheet" href="result.css">
</head>
<body>
    <div class="header">
        <h1>PillEat</h1>
    </div>
    <div class="content">
        <div class="result-container">
            <?php
            echo "<h2>Extracted Text</h2>";
            echo "<pre style='background-color: #f9f9f9; padding: 10px; border-radius: 5px;'>$extracted_text</pre>";
            ?>
            <form method="post">
                <textarea name="extracted_text" rows="10"><?php echo $extracted_text; ?></textarea><br>
                <button type="submit">수정 후 다시 검색</button>
                <button type="submit" name="apply_drugs">적용</button>
            </form>
            <a href="upload.php" class="main-btn">메인으로</a>
        </div>
        <div class="found-drugs">
        <?php
            if (!empty($found_drugs)) {
                echo "<div class='result'><h3>Found Drugs:</h3><ul class='drug-list'>";
                foreach ($found_drugs as $drug) {
                    echo "<li class='drug-item'>" . htmlspecialchars($drug['name']) . " - " . htmlspecialchars($drug['dosage']) . "</li>";
                }
                echo "</ul></div>";
                if ($message) {
                    echo "<div class='result $message_class'>$message <a href='check.php' class='main-btn'>복용중인 약 확인</a></div>";
                }
            } else {
                echo "<div class='result'>No matching drugs found in the database.</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
