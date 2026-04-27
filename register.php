<?php
include 'config.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);
    $request_admin = isset($_POST['request_admin']) ? true : false;

    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    
    if ($check && $check->num_rows > 0) {
        $error = "Username already exists! Please choose another.";
    } else {
        $role = $request_admin ? 'pending_admin' : 'user';

        $sql = "INSERT INTO users (username, password, role) 
                VALUES ('$username', '$password', '$role')";

        if ($conn->query($sql)) {
            if ($request_admin) {
                $success = "✅ Account created successfully!<br><br>Your <strong>Admin Rights</strong> request has been submitted for review.";
            } else {
                $success = "✅ Account created successfully! You can now login.";
            }
        } else {
            $error = "Registration failed: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BookShelf</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="display:flex; align-items:center; justify-content:center; min-height:100vh; background:#f8f7f4;">

<div class="card" style="max-width: 480px; width:100%; padding: 2.8rem;">
    <div style="text-align:center; margin-bottom: 2rem;">
        <h1 style="font-size: 2.8rem;">📚</h1>
        <h2>Create New Account</h2>
    </div>

    <?php if ($error): ?>
        <div class="alert" style="background:#fee2e2; color:#dc2626; border-left:5px solid #dc2626;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert" style="background:#ecfdf5; color:#10b981; border-left:5px solid #10b981;">
            <?= $success ?>
            <br><br>
            <a href="login.php" class="btn btn-primary btn-full">Go to Login</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Choose Username" required>
            <input type="password" name="password" placeholder="Create Password" required>

            <label style="display:block; margin:25px 0 15px 0; font-size:1.05rem;">
                <input type="checkbox" name="request_admin"> 
                Request Admin Rights <small style="color:#666;">(Admin will review)</small>
            </label>

            <button type="submit" name="register" class="btn btn-primary btn-full">Create Account</button>
        </form>

        <p style="text-align:center; margin-top:25px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    <?php endif; ?>
</div>

</body>
</html>