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
        .upload-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: inline-block;
            position: relative;
        }
        .upload-container img {
            width: 100%;
            max-width: 500px;
            margin-bottom: 20px;
        }
        .upload-container input[type="file"] {
            display: none;
        }
        .upload-icon {
            font-size: 50px;
            cursor: pointer;
        }
        .result {
            margin-top: 20px;
            text-align: left;
            display: inline-block;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            color: red;
            font-size: 20px;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PillEat</h1>
    </div>
    <div class="content">
        <div class="upload-container">
            <h2>약 봉투를 등록 해주세요!</h2>
            <img src="uploads/logo.png" alt="PillEat Logo">
            <form action="index.php" method="post" enctype="multipart/form-data">
                <label for="imageUpload" class="upload-icon">&#128247;</label>
                <input type="file" name="image" id="imageUpload" accept="image/*" required>
                <br>
                <input type="submit" name="upload" value="업로드 및 텍스트 추출">
            </form>
        </div>
        <?php
        if (isset($_POST['upload'])) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $processed_image = $target_dir . "processed_" . basename($_FILES["image"]["name"]);
            $overlay_image = $target_dir . "overlay_" . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // 이미지 파일 확인
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                echo "<div class='result'>파일이 이미지가 아닙니다.</div>";
                exit;
            }

            // 파일 업로드
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // 절대 경로로 변경
                $absolute_target_file = realpath($target_file);
                $absolute_processed_image = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $processed_image;
                $absolute_overlay_image = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $overlay_image;

                // 경로의 슬래시를 올바르게 변경
                $absolute_target_file = str_replace("\\", "/", $absolute_target_file);
                $absolute_processed_image = str_replace("\\", "/", $absolute_processed_image);
                $absolute_overlay_image = str_replace("\\", "/", $absolute_overlay_image);

                // Python 스크립트를 호출하여 특정 영역 추출 및 네모 박스 표시
                $python = "C:/Python312/python.exe"; // Python 실행 파일 경로
                $script = "C:/xampp/htdocs/xampp/image_processing.py"; // Python 스크립트 경로

                $command = escapeshellcmd("$python \"$script\" \"$absolute_target_file\" \"$absolute_processed_image\" \"$absolute_overlay_image\"");
                echo "<pre>Running command: $command</pre>";
                $output = shell_exec($command);
                echo "<pre>Python Output: $output</pre>";

                // Python 스크립트가 정상적으로 실행되었는지 확인
                if (file_exists($absolute_processed_image) && file_exists($absolute_overlay_image)) {
                    echo "<div class='upload-container'>";
                    echo "<img src='$overlay_image' alt='Overlay Image'>";
                    echo "<img src='$processed_image' alt='Processed Image'>";

                    // Tesseract OCR 실행 (한국어 설정)
                    $tesseract_path = "\"C:/Program Files/Tesseract-OCR/tesseract.exe\"";
                    $command = "$tesseract_path \"$absolute_processed_image\" stdout -l kor 2>&1";
                    echo "<pre>Running Tesseract command: $command</pre>";
                    $output = shell_exec($command);
                    echo "<pre>Tesseract Output: $output</pre>";

                    if (empty($output)) {
                        echo "OCR 실행에 실패했습니다.";
                    } else {
                        echo "<div class='overlay'><pre>$output</pre></div>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='result'>이미지 처리가 실패했습니다.</div>";
                }
            } else {
                echo "<div class='result'>파일 업로드에 실패했습니다.</div>";
            }
        }
        ?>
    </div>
</body>
</html>
