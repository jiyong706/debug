<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'];
$sql = "SELECT * FROM posts WHERE post_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $post_id, $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "수정할 권한이 없습니다.";
    exit();
}

$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE posts SET title = ?, content = ? WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $post_id);
    $stmt->execute();

    header("Location: board.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="post.css">
    <title>게시물 수정 - PillEat</title>
</head>
<body>
    <header>
        <div class="nav">
            <div class="logo">PillEat</div>
            <div class="menu">
                <a href="search.php">약 검색</a>
                <a href="check.php">복용중인 약 체크</a>
                <a href="expert.php">전문가 소개</a>
                <a href="logout.php" class="login-btn">로그아웃</a>
            </div>
        </div>
    </header>
    <main>
        <div class="post-container">
            <h1>게시물 수정</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $post_id; ?>" method="POST">
                <div class="field">
                    <input type="text" name="title" placeholder="제목" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </div>
                <div class="field">
                    <textarea name="content" placeholder="내용" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                <div class="field">
                    <input type="submit" value="수정 완료">
                </div>
            </form>
        </div>
    </main>
</body>
</html>
