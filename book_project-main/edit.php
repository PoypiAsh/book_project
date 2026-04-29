<?php 
session_start();
include 'config.php'; 

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if (!$is_admin) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$row = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();

if (!$row) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['update'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $year = (int)$_POST['year'];
    $book_link = $conn->real_escape_string($_POST['book_link']);

    $conn->query("UPDATE books SET 
                  title='$title', 
                  author='$author', 
                  year=$year,
                  book_link='$book_link'
                  WHERE id=$id");

    header("Location: index.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - BookShelf</title>
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
        <h3>Edit Book</h3>
        <form method="POST">
            <input type="text" name="title" placeholder="Book Title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
            <input type="text" name="author" placeholder="Author Name" value="<?php echo htmlspecialchars($row['author']); ?>" required>
            <input type="number" name="year" placeholder="Publication Year" value="<?php echo $row['year']; ?>" required>
            <input type="url" name="book_link" placeholder="Book Link (URL)" value="<?php echo htmlspecialchars($row['book_link']); ?>">
            <button type="submit" name="update" class="btn btn-edit btn-full">Update Book</button>
        </form>
    </div>
</div>

</body>
</html>
