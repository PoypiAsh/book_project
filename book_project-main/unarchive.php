<?php
include 'config.php';

$id = (int)$_GET['id'];

// Force set to 0 (active)
$conn->query("UPDATE books SET archived = 0 WHERE id = $id");

header("Location: index.php?msg=unarchived");
exit();
?>