<?php
session_start();
include 'config.php';

// LOGIN PROTECTION
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// SEARCH FUNCTION
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM books 
        WHERE title LIKE '%$search%' 
        OR author LIKE '%$search%'
        ORDER BY id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">📚 Book Management System</div>

<div class="container">

<!-- WELCOME + LOGOUT -->
<div class="card">
    <h3>Welcome, <?php echo $_SESSION['user']; ?> 👋</h3>
    <a href="logout.php" class="btn btn-delete">Logout</a>
</div>

<!-- DASHBOARD -->
<div class="card">
    <h3>Total Books</h3>
    <h1>
        <?php
        $count = $conn->query("SELECT COUNT(*) as total FROM books");
        echo $count->fetch_assoc()['total'];
        ?>
    </h1>
</div>

<!-- SEARCH + ADD -->
<div class="card">

    <form method="GET">
        <input type="text" name="search" placeholder="Search book..." value="<?php echo $search; ?>">
        <button type="submit">Search</button>
    </form>

    <br>

    <a class="btn btn-add" href="add.php">+ Add Book</a>

</div>

<!-- TABLE -->
<div class="card">

<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Author</th>
    <th>Year</th>
    <th>Action</th>
</tr>

<?php if($result && $result->num_rows > 0){ ?>
    <?php while($row = $result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['author']; ?></td>
        <td><?php echo $row['year']; ?></td>
        <td>
            <a class="btn btn-edit" href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
            <a class="btn btn-delete" href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
        </td>
    </tr>
    <?php } ?>
<?php } else { ?>
<tr>
    <td colspan="5" style="text-align:center;">No results found</td>
</tr>
<?php } ?>

</table>

</div>

</div>

</body>
</html>
