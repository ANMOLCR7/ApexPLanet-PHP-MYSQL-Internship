<?php
session_start();
require_once "../config/db.php";

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $message = "Passwords do not match!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            try {
                $stmt->execute([$username, $hashed_password]);
                $message = "Registration successful! You can now <a href='login.php'>login</a>.";
            } catch (PDOException $e) {
                $message = "Username already exists!";
            }
        }
    } else {
        $message = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST">
        <h2>Register</h2>
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>
        <button type="submit">Register</button>
    </form>
    <p class="message"><?= $message ?></p>
</body>
</html>
