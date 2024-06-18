<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    $target_file = $target_dir . "uploaded_image.png";
    
    // Check if file was uploaded without errors
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        echo "파일 " . basename($_FILES["image"]["name"]) . " 이(가) 업로드되었습니다.<br>";
    } else {
        echo "파일 업로드에 오류가 발생했습니다.<br>";
        exit;
    }

    $x = $_POST['x'];
    $y = $_POST['y'];
    $width = $_POST['width'];
    $height = $_POST['height'];

    $output_image = $target_dir . "output_image.png";
    $overlay_image = $target_dir . "overlay_image.png";
    $command = "C:/Users/315/AppData/Local/Programs/Python/Python312/python.exe C:/xampp/htdocs/pilleat/image_processing.py \"$target_file\" \"$output_image\" \"$overlay_image\" \"$x\" \"$y\" \"$width\" \"$height\"";
    echo "Running command: $command<br>";

    $output = shell_exec($command);
    echo "Python Output:<br>$output<br>";

    $extracted_text_file = "extracted_text.txt";
    if (file_exists($extracted_text_file)) {
        $extracted_text = file_get_contents($extracted_text_file);
        echo "추출된 텍스트:<br>" . nl2br($extracted_text) . "<br>";
        
        // 데이터베이스 연결 및 쿼리 실행
        $conn = new mysqli('localhost', 'root', '', 'pilleat_db');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM drugs WHERE name LIKE ?";
        $stmt = $conn->prepare($sql);
        $search_param = "%" . $extracted_text . "%";
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "약 이름: " . $row["name"] . " - 복용량: " . $row["dosage"] . "<br>";
            }
        } else {
            echo "약 정보를 찾을 수 없습니다.<br>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "텍스트 추출에 실패했습니다. 추출된 텍스트 파일을 찾을 수 없습니다.<br>";
    }
}
?>
