<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];
    $user_id = $_SESSION['id'];

    // Debugging - user_id 확인
    echo "User ID: " . $user_id . "<br>";

    // Check if the user_id exists in the users table
    $user_check_sql = "SELECT * FROM users WHERE id = ?";
    $user_check_stmt = $conn->prepare($user_check_sql);
    $user_check_stmt->bind_param("s", $user_id);
    $user_check_stmt->execute();
    $user_check_result = $user_check_stmt->get_result();

    if ($user_check_result->num_rows > 0) {
        echo "User ID exists in the users table.<br>";

        // Check if the post_id exists in the posts table
        $post_check_sql = "SELECT * FROM posts WHERE post_id = ?";
        $post_check_stmt = $conn->prepare($post_check_sql);
        $post_check_stmt->bind_param("i", $post_id);
        $post_check_stmt->execute();
        $post_check_result = $post_check_stmt->get_result();

        if ($post_check_result->num_rows > 0) {
            echo "Post ID exists in the posts table.<br>";

            // Insert reply
            $sql = "INSERT INTO replies (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $post_id, $user_id, $content);

            if ($stmt->execute()) {
                echo "Reply successfully inserted.";
                // Redirect after inserting
                header("Location: board.php");
                exit();
            } else {
                echo "Error inserting reply: " . $stmt->error;
            }
        } else {
            echo "Error: Post ID not found in the posts table.";
        }
    } else {
        echo "Error: User ID not found in the users table.";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="post.css">
    <title>답글 작성 - PillEat</title>
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
            <h1>답글 작성</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $post_id; ?>" method="POST">
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
