<?php
// Database connection settings
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "CINE BOOK";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to avoid encoding issues
$conn->set_charset("utf8");

?>
