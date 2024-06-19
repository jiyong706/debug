<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $sql = "SELECT posts.post_id, posts.title, posts.content, users.fname, users.lname, posts.created_at, posts.user_id
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            WHERE posts.title LIKE ? OR posts.content LIKE ?
            ORDER BY posts.created_at DESC";
    $stmt = $conn->prepare($sql);
    $search_param = '%' . $search_query . '%';
    $stmt->bind_param("ss", $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT posts.post_id, posts.title, posts.content, users.fname, users.lname, posts.created_at, posts.user_id
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC";
    $result = $conn->query($sql);
}
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
                    <form action="board.php" method="GET">
                        <input type="text" name="search" placeholder="검색" value="<?php echo htmlspecialchars($search_query); ?>">
                        <button type="submit" class="search-btn"><img src="path/to/search-icon.png" alt="검색"></button>
                    </form>
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
                            <?php if ($row['user_id'] === $_SESSION['id']) { ?>
                                <div class="post-actions">
                                    <a href="edit_post.php?id=<?php echo $row['post_id']; ?>">수정</a>
                                    <a href="delete_post.php?id=<?php echo $row['post_id']; ?>">삭제</a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="post-interactions">
                            <a href="reply_post.php?id=<?php echo $row['post_id']; ?>">답글</a>
                            <a href="like_post.php?id=<?php echo $row['post_id']; ?>">추천</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
</body>
</html>
