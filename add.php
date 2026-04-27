<?php 
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (isset($_POST['submit'])) {
    $title  = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $year   = (int)$_POST['year'];
    $book_link = $conn->real_escape_string($_POST['book_link']);

    $status = $is_admin ? 'approved' : 'pending';

    $sql = "INSERT INTO books (title, author, year, archived, status, book_link) 
            VALUES ('$title', '$author', $year, 0, '$status', '$book_link')";
    
    if ($conn->query($sql)) {
        if ($is_admin) {
            header("Location: index.php?msg=added");
        } else {
            header("Location: index.php?msg=pending");
        }
        exit();
    } else {
        die("Error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book - BookShelf</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-content">
        <span class="logo">📚 BookShelf</span>

        <div class="nav-right">
            <span class="welcome">
                Welcome, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>
                <?php if ($is_admin): ?> (Admin)<?php endif; ?>
            </span>

            <a href="index.php" class="btn btn-secondary">← Back to Books</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="card" style="max-width: 650px; margin: 40px auto;">
        <h3><?php echo $is_admin ? 'Add New Book' : 'Submit Book for Approval'; ?></h3>

        <?php if (!$is_admin): ?>
            <p style="color:#666; margin-bottom:15px;">
                Your submitted book will be reviewed by an admin before it appears in the library.
            </p>
        <?php endif; ?>

        <form method="POST" class="book-form">
            <input 
                class="book-input"
                type="text" 
                name="title" 
                placeholder="Book Title" 
                required
            >

            <input 
                class="book-input"
                type="text" 
                name="author" 
                placeholder="Author Name" 
                required
            >

            <input 
                class="book-input"
                type="number" 
                name="year" 
                placeholder="Publication Year" 
                required
            >

            <input 
                class="book-input"
                type="url" 
                name="book_link" 
                placeholder="Book Link (URL to read the book)"
            >

            <button type="submit" name="submit" class="btn btn-add btn-full">
                <?php echo $is_admin ? 'Save Book' : 'Submit for Approval'; ?>
            </button>
        </form>
    </div>
</div>

</body>
</html>
