<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'];

// 게시글 정보 가져오기
$post_sql = "SELECT posts.title, posts.content, users.fname, users.lname, posts.created_at 
             FROM posts 
             JOIN users ON posts.user_id = users.id 
             WHERE posts.post_id = ?";
$post_stmt = $conn->prepare($post_sql);
$post_stmt->bind_param("i", $post_id);
$post_stmt->execute();
$post_result = $post_stmt->get_result();
$post = $post_result->fetch_assoc();

// 답글 정보 가져오기
$reply_sql = "SELECT replies.content, users.fname, users.lname, replies.created_at 
              FROM replies 
              JOIN users ON replies.user_id = users.id 
              WHERE replies.post_id = ?
              ORDER BY replies.created_at DESC";
$reply_stmt = $conn->prepare($reply_sql);
$reply_stmt->bind_param("i", $post_id);
$reply_stmt->execute();
$reply_result = $reply_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="post.css">
    <title><?php echo htmlspecialchars($post['title']); ?> - PillEat</title>
    <style>
        .reply {
            margin-bottom: 1rem;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .reply-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: #555;
        }
        .reply-content {
            margin-bottom: 0.5rem;
        }
        .reply-author {
            text-align: right;
        }
        .back-button {
            display: inline-block;
            margin-bottom: 1rem;
            padding: 0.5rem 1rem;
            background: #007bff; /* 작성 완료 버튼과 동일한 배경색 */
            color: #fff; /* 텍스트 색상을 흰색으로 변경 */
            text-decoration: none;
            border-radius: 5px;
            border: none; /* 테두리 제거 */
        }
        .back-button:hover {
            background: #0056b3; /* 호버 상태의 색상 */
        }
    </style>
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
            <a href="javascript:history.back()" class="back-button">뒤로 가기</a>
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <div class="post-meta">
                <span><?php echo htmlspecialchars($post['fname'] . " " . $post['lname']); ?></span>
                <span><?php echo htmlspecialchars($post['created_at']); ?></span>
            </div>
            <hr>
            <h2>답글</h2>
            <div class="replies">
                <?php while ($reply = $reply_result->fetch_assoc()) { ?>
                    <div class="reply">
                        <div class="reply-content"><?php echo htmlspecialchars($reply['content']); ?></div>
                        <div class="reply-meta">
                            <span class="reply-author"><?php echo htmlspecialchars($reply['fname'] . " " . $reply['lname']); ?></span>
                            <span><?php echo htmlspecialchars($reply['created_at']); ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <hr>
            <h2>답글 작성</h2>
            <form action="reply_post.php?id=<?php echo $post_id; ?>" method="POST">
                <div class="field">
                    <textarea name="content" placeholder="내용" required></textarea>
                </div>
                <div class="field">
                    <input type="submit" value="작성 완료">
                </div>
            </form>
        </div>
    </main>
</body>
</html>
