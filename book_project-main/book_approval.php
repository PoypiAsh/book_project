<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success = '';

if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $conn->query("UPDATE books SET status='approved' WHERE id=$id");
    $success = "✅ Book approved successfully!";
}

if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $conn->query("UPDATE books SET status='rejected' WHERE id=$id");
    $success = "❌ Book rejected.";
}

$result = $conn->query("SELECT * FROM books WHERE status='pending' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Approval</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <div class="card">
        <h3>Pending Book Requests</h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Action</th>
            </tr>

            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= $row['year'] ?></td>
                    <td>
                        <a href="?approve=<?= $row['id'] ?>" class="btn btn-edit">Approve</a>
                        <a href="?reject=<?= $row['id'] ?>" class="btn btn-delete">Reject</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No pending books.</td>
                </tr>
            <?php endif; ?>
        </table>

        <br>
        <a href="index.php" class="btn btn-secondary">← Back</a>
    </div>
</div>

</body>
</html>