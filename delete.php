<?php
include 'config.php';

$id = (int)$_GET['id'];

$conn->query("DELETE FROM books WHERE id = $id");

header("Location: index.php?msg=deleted");
exit();
?>
