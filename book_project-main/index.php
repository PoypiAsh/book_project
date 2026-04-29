<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$show_archived = isset($_GET['archived']) && $_GET['archived'] == 1;

$sql = "SELECT * FROM books WHERE status='approved' ";
if (!$show_archived) {
    $sql .= " AND (archived = 0 OR archived IS NULL) ";
} else {
    $sql .= " AND archived = 1 ";
}
if ($search) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (title LIKE '%$search%' OR author LIKE '%$search%') ";
}
$sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookShelf - Book Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-content">
        <span class="logo">📚 BookShelf</span>
        <div class="nav-right">
            <a href="index.php" class="nav-link <?php echo !$show_archived ? 'active' : ''; ?>">Active Books</a>
            <a href="index.php?archived=1" class="nav-link <?php echo $show_archived ? 'active' : ''; ?>">Archive</a>
            
            <?php if ($is_admin): ?>
                <a href="admin_approval.php" class="nav-link">Admin Approval</a>
            <?php endif; ?>

            <?php if ($is_admin): ?>
                <a href="book_approval.php" class="nav-link">Book Approval</a>
            <?php endif; ?>
            
            <span class="welcome">
                Welcome, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>
                <?php if ($is_admin): ?> (Admin)<?php endif; ?>
            </span>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>
    </div>
</div>

<div class="container">

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success">
            <?php 
                if ($_GET['msg'] == 'archived') echo '✅ Book moved to archive successfully!';
                elseif ($_GET['msg'] == 'unarchived') echo '✅ Book restored successfully!';
                elseif ($_GET['msg'] == 'deleted') echo '✅ Book permanently deleted!';
                elseif ($_GET['msg'] == 'added') echo '✅ New book added successfully!';
                elseif ($_GET['msg'] == 'pending') echo '⏳ Book submitted successfully! Waiting for admin approval.';
            ?>
        </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="card stat-card">
            <h3>Active Books</h3>
            <h1><?php
                $count = $conn->query("SELECT COUNT(*) as total FROM books WHERE archived = 0 OR archived IS NULL");
                echo $count->fetch_assoc()['total'];
                ?></h1>
        </div>
        <div class="card stat-card">
            <h3>Archived Books</h3>
            <h1><?php
                $archived_count = $conn->query("SELECT COUNT(*) as total FROM books WHERE archived = 1");
                echo $archived_count->fetch_assoc()['total'];
                ?></h1>
        </div>
    </div>

    <div class="card">
        <div class="header">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search books..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-edit">🔍 Search</button>
            </form>
            
            <?php if (!$show_archived): ?>
                <a href="add.php" class="btn btn-add">+ Add New Book</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <h3><?php echo $show_archived ? '📦 Archived Books' : '📚 Active Books'; ?></h3>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                    <th>Book Link</th>
                    <th width="220">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                        <td><?php echo $row['year']; ?></td>
                        <td>
                            <?php if (!empty($row['book_link'])): ?>
                                <a href="<?php echo htmlspecialchars($row['book_link']); ?>" 
                                   class="btn btn-sm btn-book-link" 
                                   target="_blank">
                                    📖 Read Book
                                </a>
                            <?php else: ?>
                                <span class="no-link">No link</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <?php if ($is_admin): ?>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                                
                                <?php if (!$show_archived): ?>
                                    <a href="archive.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-archive"
                                       onclick="return confirm('Archive this book?')">Archive</a>
                                <?php else: ?>
                                    <a href="unarchive.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-unarchive"
                                       onclick="return confirm('Restore this book?')">Unarchive</a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                       class="btn btn-delete"
                                       onclick="return confirm('Permanently delete this book?')">Delete</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="view-only">View Only</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="no-data">
                            <?php echo $search ? 'No matching books found.' : ($show_archived ? 'Archive is empty.' : 'No books yet.'); ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
