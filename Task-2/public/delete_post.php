<?php
session_start();
require_once "../config/db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure a post ID is provided
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$post_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Check if the post belongs to the current user
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$post_id, $user_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Unauthorized action or post not found!");
}

// Delete the post
$delete_stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$delete_stmt->execute([$post_id, $user_id]);

header("Location: index.php");
exit;
?>
