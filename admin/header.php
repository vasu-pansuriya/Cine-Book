<?php
session_start();

// If admin not logged in, redirect to login
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineBook Admin</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #0b2d89;
            color: white;
            position: fixed;
            padding-top: 30px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 40px;
            letter-spacing: 2px;
        }

        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .sidebar a:hover {
            background: #051b52;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0px 3px 8px rgba(0,0,0,0.2);
            text-align: center;
            width: 250px;
        }

        .card-container {
            display: flex;
            gap: 30px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h2>Admin Panel</h2>

    <a href="dashboard.php">Dashboard</a>
    <a href="ad_movies.php">Movies</a>
    <a href="cinemas.php">Cinemas</a>
    <a href="users.php">Users</a>
    <a href="logout.php" style="color: red;">Logout</a>
</div>

<div class="content">
