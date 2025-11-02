<?php
session_start();
require_once "../config/db.php";

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch all posts
$stmt = $pdo->query("SELECT posts.*, users.username
                     FROM posts
                     JOIN users ON posts.user_id = users.id
                     ORDER BY posts.created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Blog</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include "../templates/header.php"; ?>

    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>

    <h2>All Posts</h2>

    <?php if (count($posts) > 0): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small>By <?= htmlspecialchars($post['username']) ?> on <?= $post['created_at'] ?></small>
                <br>
                <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                    <a href="edit_post.php?id=<?= $post['id'] ?>">Edit</a> |
                    <a href="delete_post.php?id=<?= $post['id'] ?>" onclick="return confirm('Delete this post?');">Delete</a>
                <?php endif; ?>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts yet. <a href="create_post.php">Create one</a>!</p>
    <?php endif; ?>

    <?php include "../templates/footer.php"; ?>
</body>
</html>
