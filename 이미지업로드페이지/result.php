<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pill_db";

function get_drug_info($text, $conn) {
    $sql = "SELECT * FROM drugs WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $like_text = "%$text%";
    $stmt->bind_param("s", $like_text);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function display_results($lines, $conn) {
    $found_drugs = [];
    foreach ($lines as $line) {
        $drug_info = get_drug_info(trim($line), $conn);
        if (!empty($drug_info)) {
            foreach ($drug_info as $drug) {
                $found_drugs[] = $drug;
            }
        }
    }

    if (!empty($found_drugs)) {
        echo "<div class='result'><h3>일치하는 약정보:</h3><ul class='drug-list'>";
        foreach ($found_drugs as $drug) {
            echo "<li class='drug-item'>" . $drug['name'] . " - " . $drug['dosage'] . "</li>";
        }
        echo "</ul></div>";
    } else {
        echo "<div class='result'>No matching drugs found in the database.</div>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['extracted_text'])) {
    $extracted_text = $_POST['extracted_text'];
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

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PillEat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .header {
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px;
        }
        .result-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: inline-block;
            position: relative;
            text-align: left;
            max-width: 800px;
            margin: 0 auto;
        }
        .result-container textarea {
            width: 100%;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .result-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .result-container button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
        }
        .drug-list {
            display: flex;
            flex-wrap: wrap;
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .drug-item {
            background-color: #e9ecef;
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
            flex: 1 1 calc(50% - 20px);
            box-sizing: border-box;
            text-align: center;
        }
        .drug-item:nth-child(odd) {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PillEat</h1>
    </div>
    <div class="content">
        <div class="result-container">
            <?php
            echo "<h2>추출된 약들</h2>";
            echo "<pre style='background-color: #f9f9f9; padding: 10px; border-radius: 5px;'>$extracted_text</pre>";
            display_results($lines, $conn);

            echo "<form method='post' id='searchForm'>";
            echo "<textarea name='extracted_text' rows='10'>$extracted_text</textarea><br>";
            echo "<button type='submit'>수정 후 다시 검색</button>";
            echo "</form>";

            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
