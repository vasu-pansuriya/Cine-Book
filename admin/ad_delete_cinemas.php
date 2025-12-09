<?php
// admin/delete_movie.php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}
include '../db_connect.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: ad_cinemas.php');
    exit;
}


// delete DB row
$stmt = $conn->prepare("DELETE FROM cinemas WHERE id = ?");

$stmt->bind_param('i', $id);

// Execute the deletion
if ($stmt->execute()) {
    // Optional: Add a success message here before redirecting
    $_SESSION['message'] = "Cinema successfully deleted.";
} else {
    // Optional: Add an error message here
    $_SESSION['error'] = "Error deleting record: " . $stmt->error;
}

$stmt->close();

header('Location: ad_cinemas.php');
exit;
