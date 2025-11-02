<header>
    <h1>📰 My Blog</h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php">Home</a> |
            <a href="create_post.php">Create Post</a> |
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a> |
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    <hr>
</header>
