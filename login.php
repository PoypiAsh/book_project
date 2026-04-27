<?php
session_start();
include 'config.php';

$error = '';

if (isset($_POST['login'])) {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['user'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['user_id'] = $row['id'];
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BookShelf</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="display:flex; align-items:center; justify-content:center; min-height:100vh; background:#f8f7f4;">

<div class="card" style="max-width: 420px; width:100%; padding: 2.5rem;">
    <div style="text-align:center; margin-bottom: 2rem;">
        <h1 style="font-size: 3rem;">📚</h1>
        <h2>BookShelf</h2>
        <p style="color:#666;">Sign in to your account</p>
    </div>

    <?php if ($error): ?>
        <div class="alert" style="background:#fee2e2; color:#dc2626; border-left:5px solid #dc2626;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login" class="btn btn-primary btn-full">Login</button>
    </form>

    <div style="text-align:center; margin-top: 20px;">
        <a href="register.php" class="btn btn-secondary" style="display:inline-block; padding:10px 20px;">
            Create New Account
        </a>
    </div>
</div>

</body>
</html>
