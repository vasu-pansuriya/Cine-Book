<?php
session_start();

// if admin not logged in then redirect to login page
if (!isset($_SESSION['admin'])) {
    header("Location: ad_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #002b80;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .sidebar a {
            display: block;
            margin: 18px 0;
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a:hover {
            color: yellow;
        }

        .logout {
            color: red;
        }

        .main {
            margin-left: 260px;
            padding: 30px;
        }

        .title {
            text-align: center;
            font-size: 32px;
            color: #cc0000;
            margin-bottom: 10px;
        }

        .boxes {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 40px;
        }

        .box {
            width: 220px;
            height: 120px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.2);
            text-align: center;
            padding-top: 25px;
            font-size: 22px;
        }

        .box span {
            font-size: 35px;
            font-weight: bold;
            display: block;
            margin-top: 10px;
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
    <a href="logout.php" class="logout">Logout</a>
</div>

<div class="main">
    <h1 class="title">Admin Dashboard</h1>
    <p style="text-align:center;">Welcome to the admin dashboard.</p>

    <?php 
        include "../db_connect.php";
        $users = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
        $movies = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM movies"))[0];
        $cinemas = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM cinemas"))[0];
    ?>

    <div class="boxes">
        <div class="box">Users <span><?php echo $users; ?></span></div>
        <div class="box">Movies <span><?php echo $movies; ?></span></div>
        <div class="box">Cinemas <span><?php echo $cinemas; ?></span></div>
    </div>
</div>

</body>
</html>
