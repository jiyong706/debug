<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT posts.post_id, posts.title, posts.content, users.fname, users.lname, posts.created_at 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="board.css">
    <title>게시판 - PillEat</title>
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
        <div class="board-container">
            <div class="board-actions">
                <a href="post.php" class="create-post">게시물 작성</a>
                <div class="search-bar">
                    <input type="text" placeholder="검색">
                    <button class="search-btn"><img src="path/to/search-icon.png" alt="검색"></button>
                </div>
            </div>
            <div class="posts">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="post">
                        <h3><a href="post_detail.php?id=<?php echo $row['post_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
                        <p><?php echo htmlspecialchars($row['content']); ?></p>
                        <div class="post-meta">
                            <span><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></span>
                            <span><?php echo htmlspecialchars($row['created_at']); ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
</body>
</html>
