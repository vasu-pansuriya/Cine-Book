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
$stmt = $conn->prepare("SELECT movie_img FROM movies WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($img);
$stmt->fetch();
$stmt->close();

// delete DB row
$stmt = $conn->prepare("DELETE FROM movies WHERE id = ?");
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    // delete file if exists
    if (!empty($img) && file_exists(__DIR__ . '/../uploads/movies/' . $img)) {
        @unlink(__DIR__ . '/../uploads/movies/' . $img);
    }
}
$stmt->close();

header('Location: movies.php');
exit;
