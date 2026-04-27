<?php include 'config.php'; ?>

<?php
$id = $_GET['id'];
$row = $conn->query("SELECT * FROM books WHERE id=$id")->fetch_assoc();

if(isset($_POST['update'])){
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];

    $conn->query("UPDATE books SET title='$title', author='$author', year='$year' WHERE id=$id");

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">📚 Edit Book</div>

<div class="container">
<div class="card">

<form method="POST">
    <input type="text" name="title" value="<?php echo $row['title']; ?>">
    <input type="text" name="author" value="<?php echo $row['author']; ?>">
    <input type="number" name="year" value="<?php echo $row['year']; ?>">
    <button name="update">Update</button>
</form>

</div>
</div>

</body>
</html>