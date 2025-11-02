<?php
session_start();
require_once "../config/db.php";

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $title, $content]);
        header("Location: index.php");
        exit;
    } else {
        $message = "Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include "../templates/header.php"; ?>

    <h2>Create New Post</h2>
    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="6" cols="50" required></textarea><br><br>

        <button type="submit">Publish</button>
    </form>
    <p><?= $message ?></p>
    <a href="index.php">⬅ Back to Home</a>

    <?php include "../templates/footer.php"; ?>
</body>
</html>
