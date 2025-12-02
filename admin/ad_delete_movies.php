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
    header('Location: ad_movies.php');
    exit;
}

// fetch movie image
// $stmt = $conn->prepare("SELECT movie_img FROM movies WHERE id = ?");
// $stmt->bind_param('i', $id);
// $stmt->execute();
// $stmt->bind_result($img);
// $stmt->fetch();
// $stmt->close();

// delete DB row
$stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");

$stmt->bind_param('i', $id);

// Execute the deletion
if ($stmt->execute()) {
    // Optional: Add a success message here before redirecting
    $_SESSION['message'] = "Movie successfully deleted.";
} else {
    // Optional: Add an error message here
    $_SESSION['error'] = "Error deleting record: " . $stmt->error;
}

// if ($stmt->execute()) {
//     // delete file if exists
//     if (!empty($img) && file_exists(__DIR__ . '/../uploads/movies/' . $img)) {
//         @unlink(__DIR__ . '/../uploads/movies/' . $img);
//     }
// }
$stmt->close();

header('Location: ad_add_movies.php');
exit;
