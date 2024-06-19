<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'];

$sql = "DELETE FROM posts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();

header("Location: board.php");
exit();
?>
