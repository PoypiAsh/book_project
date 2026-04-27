<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success = '';

// Approve Admin Request
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $conn->query("UPDATE users SET role = 'admin' WHERE id = $id AND role = 'pending_admin'");
    $success = "✅ User promoted to Admin successfully!";
}

// Reject Request
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $conn->query("UPDATE users SET role = 'user' WHERE id = $id AND role = 'pending_admin'");
    $success = "✅ Admin request rejected.";
}

$result = $conn->query("SELECT * FROM users WHERE role = 'pending_admin' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval - BookShelf</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-content">
        <span class="logo">📚 BookShelf</span>
        <div class="nav-right">
            <a href="index.php" class="nav-link">Books</a>
            <span class="welcome">Welcome, Admin</span>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <h3>Pending Admin Requests</h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Requested At</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                    <td><?= $row['created_at'] ?? 'N/A' ?></td>
                    <td>
                        <a href="admin_approval.php?approve=<?= $row['id'] ?>" 
                           class="btn btn-edit" 
                           onclick="return confirm('Approve this user as Admin?')">Approve</a>
                        <a href="admin_approval.php?reject=<?= $row['id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('Reject this admin request?')">Reject</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No pending admin requests at the moment.</p>
        <?php endif; ?>
    </div>

    <a href="index.php" class="btn btn-secondary">← Back to Book Management</a>
</div>

</body>
</html>