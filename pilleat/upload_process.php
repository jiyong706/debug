<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pilleat_db"; // 변경된 데이터베이스 이름

function get_drug_info($text, $conn) {
    $sql = "SELECT * FROM drugs WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $like_text = "%$text%";
    $stmt->bind_param("s", $like_text);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

if (isset($_FILES['image']) && isset($_POST['x']) && isset($_POST['y']) && isset($_POST['width']) && isset($_POST['height'])) {
    $x = intval($_POST['x']);
    $y = intval($_POST['y']);
    $width = intval($_POST['width']);
    $height = intval($_POST['height']);

    // 파일 저장 경로
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . "selected_image.png";
    $processed_image = $target_dir . "processed_selected_image.png";
    $overlay_image = $target_dir . "overlay_selected_image.png";

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // 절대 경로로 변경
        $absolute_target_file = realpath($target_file);
        $absolute_processed_image = realpath($processed_image);
        $absolute_overlay_image = realpath($overlay_image);

        // Python 스크립트를 호출하여 특정 영역 추출 및 네모 박스 표시
        $python = "C:/Python312/python.exe"; // Python 실행 파일 경로
        $script = "C:/xampp/htdocs/pilleat/image_processing.py"; // Python 스크립트 경로

        $command = escapeshellcmd("$python \"$script\" \"$absolute_target_file\" \"$absolute_processed_image\" \"$absolute_overlay_image\" \"$x\" \"$y\" \"$width\" \"$height\"");
        $output = shell_exec($command);
        echo "<pre>Running command: $command</pre>";
        echo "<pre>Python Output: $output</pre>";

        // 추출된 텍스트 읽기
        $extracted_text_file = "extracted_text.txt";
        if (file_exists($extracted_text_file)) {
            $extracted_text = file_get_contents($extracted_text_file);
            echo "<pre>Extracted Text: $extracted_text</pre>";

            // 추출된 텍스트를 세션에 저장하고 결과 페이지로 리디렉션
            session_start();
            $_SESSION['extracted_text'] = $extracted_text;
            header('Location: result.php');
            exit;
        } else {
            echo "텍스트 추출에 실패했습니다.";
        }
    } else {
        echo "<div class='result'>파일 업로드에 실패했습니다.</div>";
    }
} else {
    echo "필요한 데이터가 없습니다.";
}
?>
