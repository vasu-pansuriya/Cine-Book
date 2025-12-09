<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ad_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineBook Admin Dashboard</title>

    <!-- Google Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6fc;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* HEADER */
        .header {
            background: linear-gradient(90deg, #8e2de2, #4a00e0);
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 22px;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .logout-btn {
            color: white;
            font-size: 18px;
            text-decoration: none;
            font-weight: bold;
        }

        /* LAYOUT */
        .layout {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 60px);
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            background: #1d1b31;
            padding-top: 20px;
            color: white;
            flex-shrink: 0;
            transition: width 0.3s ease-in-out;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            font-size: 17px;
            color: white;
            text-decoration: none;
            transition: 0.25s;
        }

        .sidebar a:hover {
            background: #2e2b49;
            padding-left: 25px;
        }

        .icon {
            font-size: 22px;
            min-width: 30px;
            text-align: center;
        }

        /* CONTENT */
        .content {
            flex: 1;
            padding: 30px;
            transition: margin 0.3s;
        }

        .dashboard-title {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
        }

        /* CARDS */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
        }

        .card {
            flex: 1;
            min-width: 260px;
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-6px);
        }

        .card-title {
            font-size: 20px;
            color: #6a11cb;
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 42px;
            font-weight: bold;
            color: #222;
        }

        .card-link {
            text-decoration: none !important;
            color: inherit;
        }

        .card-link:hover {
            text-decoration: none !important;
        }

        .card-link:visited {
            text-decoration: none !important;
        }

        /* FOOTER */
        .footer {
            background: #1d1b31;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 16px;
            margin-top: auto;
        }

        /* ------------------- RESPONSIVE ------------------- */

        /* auto collapse sidebar */
        @media (max-width: 900px) {
            .sidebar {
                width: 80px;
            }

            .sidebar a span {
                display: none;
            }

            .sidebar a {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
        }
    </style>

</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <div>📽️ CineBook Admin Panel</div>

    </div>

    <!-- SIDEBAR + CONTENT -->
    <div class="layout">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <a href="dashboard.php"><span class="icon material-icons">dashboard</span><span>Dashboard</span></a>
            <a href="ad_movies.php"><span class="icon material-icons">movie</span><span>Movies</span></a>
            <a href="ad_cinemas.php"><span class="icon material-icons">theaters</span><span>Cinemas</span></a>
            <a href="ad_users.php"><span class="icon material-icons">people</span><span>Users</span></a>
            <a href="logout.php" style="color: #ff4d4d;">
                <span class="icon material-icons">logout</span><span>Logout</span>
            </a>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <h2 class="dashboard-title">Welcome, Admin</h2>

            <?php
            include "../db_connect.php";
            $users = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
            $movies = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM movies"))[0];
            $cinemas = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM cinemas"))[0];
            ?>

            <div class="card-container">
                <a href="ad_users.php" class="card card-link">
                    <div class="card-title">Total Users</div>
                    <div class="card-value"><?php echo $users; ?></div>
                </a>

                <a href="ad_movies.php" class="card card-link">
                    <div class="card-title">Total Movies</div>
                    <div class="card-value"><?php echo $movies; ?></div>
                </a>

                <a href="ad_cinemas.php" class="card card-link">
                    <div class="card-title">Total Cinemas</div>
                    <div class="card-value"><?php echo $cinemas; ?></div>
                </a>

            </div>

        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        © 2025 CineBook | Admin Panel
    </div>

</body>

</html>