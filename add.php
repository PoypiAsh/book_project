<?php include 'config.php'; ?>

<?php
if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];

    $conn->query("INSERT INTO books (title, author, year)
    VALUES ('$title','$author','$year')");

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">📚 Add Book</div>

<div class="container">
<div class="card">

<form method="POST">
    <input type="text" name="title" placeholder="Title" required>
    <input type="text" name="author" placeholder="Author" required>
    <input type="number" name="year" placeholder="Year" required>
    <button name="submit">Save</button>
</form>

</div>
</div>

</body>
</html>