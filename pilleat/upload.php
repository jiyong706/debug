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
            text-align: left;
            max-width: 800px;
            margin: 0 auto;
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
        .main-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .main-btn:hover {
            background-color: #0056b3;
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
            <label for="imageUpload" class="upload-icon">&#128247;</label>
            <input type="file" id="imageUpload" accept="image/*">
            <canvas id="canvas" width="500" height="500"></canvas>
            <br>
            <button id="processImage">텍스트 추출</button>
            <br><br>
            <a href="index.php" class="main-btn">메인으로</a> <!-- 메인 버튼 추가 -->
        </div>
        <div class="result" id="result"></div>
    </div>

    <script>
        let canvas = document.getElementById('canvas');
        let ctx = canvas.getContext('2d');
        let rect = {startX: 0, startY: 0, endX: 0, endY: 0};
        let clickCount = 0;

        let image = new Image();
        let imageUploaded = false;
        let originalWidth, originalHeight;

        document.getElementById('imageUpload').addEventListener('change', function (e) {
            let reader = new FileReader();
            reader.onload = function (event) {
                image.onload = function () {
                    originalWidth = image.width;
                    originalHeight = image.height;
                    canvas.width = originalWidth;
                    canvas.height = originalHeight;
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
                    imageUploaded = true;
                    clickCount = 0; // 이미지 업로드 시 클릭 카운트 초기화
                }
                image.src = event.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        });

        canvas.addEventListener('mousedown', function (e) {
            if (imageUploaded) {
                if (clickCount === 0) {
                    rect.startX = e.offsetX;
                    rect.startY = e.offsetY;
                    clickCount = 1;
                } else {
                    rect.endX = e.offsetX;
                    rect.endY = e.offsetY;
                    clickCount = 0;
                    drawRect();
                }
            }
        });

        function drawRect() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
            ctx.strokeStyle = 'green';
            ctx.lineWidth = 2;
            ctx.strokeRect(rect.startX, rect.startY, rect.endX - rect.startX, rect.endY - rect.startY);
        }

        document.getElementById('processImage').addEventListener('click', function () {
            if (imageUploaded && (rect.startX !== rect.endX && rect.startY !== rect.endY)) {
                const x = Math.min(rect.startX, rect.endX);
                const y = Math.min(rect.startY, rect.endY);
                const width = Math.abs(rect.endX - rect.startX);
                const height = Math.abs(rect.endY - rect.startY);

                canvas.toBlob(function (blob) {
                    const data = new FormData();
                    data.append('image', blob);
                    data.append('x', x);
                    data.append('y', y);
                    data.append('width', width);
                    data.append('height', height);

                    fetch('upload_process.php', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => response.text())
                    .then(text => {
                        document.getElementById('result').innerHTML = text;
                        window.location.href = 'result.php'; // 결과 페이지로 리디렉션
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }, 'image/png', 1.0); // 이미지 압축률 1.0 (최대 품질)
            } else {
                alert('이미지를 업로드하고 영역을 선택해주세요.');
            }
        });
    </script>
</body>
</html>
