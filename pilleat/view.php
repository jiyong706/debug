<?php
include_once "config.php";
session_start();

$post_id = $_GET['post_id'];

$sql = "SELECT * FROM posts WHERE post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?></title>
    <link rel="stylesheet" href="view.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $post['title']; ?></h1>
        <p><?php echo $post['content']; ?></p>
        <p>작성일: <?php echo $post['created_at']; ?></p>
        <a href="edit.php?post_id=<?php echo $post_id; ?>">수정</a>
        <a href="delete.php?post_id=<?php echo $post_id; ?>">삭제</a>
        <a href="board.php">목록으로</a>
    </div>
</body>
</html>