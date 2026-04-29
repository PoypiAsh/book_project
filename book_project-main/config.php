<?php
$conn = new mysqli("localhost", "root", "bombabeat", "book_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set charset
$conn->set_charset("utf8mb4");
?>
