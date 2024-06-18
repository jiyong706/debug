<?php
include_once "config.php";
session_start();

$post_id = $_GET['post_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE posts SET title = ?, content = ? WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $post_id);

    if ($stmt->execute()) {
        header("Location: view.php?post_id=$post_id");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $sql = "SELECT * FROM posts WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 수정</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <div class="container">
        <h1>글 수정</h1>
        <form action="edit.php?post_id=<?php echo $post_id; ?>" method="POST">
            <div class="field">
                <label for="title">제목</label>
                <input type="text" name="title" id="title" value="<?php echo $post['title']; ?>" required>
            </div>
            <div class="field">
                <label for="content">내용</label>
                <textarea name="content" id="content" required><?php echo $post['content']; ?></textarea>
            </div>
            <div class="field">
                <input type="submit" value="수정">
            </div>
        </form>
    </div>
</body>
</html>
