<?php
include 'config.php';

$id = (int)$_GET['id'];

$conn->query("UPDATE books SET archived = 1 WHERE id = $id");

header("Location: index.php?msg=archived");
exit();
?>