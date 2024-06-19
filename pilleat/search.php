<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>약 검색</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <div class="nav">
            <div class="logo"><a href="index.php">PillEat</div>
            <div class="menu">
                <a href="index.php">메인</a>
                <a href="check.php">복용중인 약 체크</a>
                <a href="expert.php">전문가 소개</a>
                <a href="login.php" class="login-btn">로그인</a>
            </div>
        </div>
    </header>
    <main>
        <div class="content">
            <h1>약 검색</h1>
            <form action="search_results.php" method="get">
                <input type="text" name="query" placeholder="검색어를 입력하세요" required>
                <button type="submit">검색</button>
            </form>
        </div>
    </main>
</body>
</html>
