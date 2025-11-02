<?php
session_start();
require_once "../config/db.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get post ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$post_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch post to ensure it belongs to the logged-in user
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$post_id, $user_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Unauthorized access or post not found!");
}

$message = '';

// Update post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $update_stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, updated_at = NOW() WHERE id = ?");
        $update_stmt->execute([$title, $content, $post_id]);
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
    <title>Edit Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include "../templates/header.php"; ?>

    <h2>Edit Post</h2>
    <form method="POST">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br><br>

        <label>Content:</label><br>
        <textarea name="content" rows="6" cols="50" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>

        <button type="submit">Update</button>
    </form>
    <p><?= $message ?></p>
    <a href="index.php">⬅ Back to Home</a>

    <?php include "../templates/footer.php"; ?>
</body>
</html>
