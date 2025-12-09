<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ad_login.php');
    exit;
}

include '../db_connect.php';

$message = "";

// Handle Featured toggle (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
    $movieId = (int)$_POST['toggle_id'];

    // Get current featured status
    $stmt = $conn->prepare("SELECT featured FROM movies WHERE id = ?");
    $stmt->bind_param('i', $movieId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row) {
        $current = (int)$row['featured'];

        if ($current === 1) {
            // Turn off featured
            $conn->query("UPDATE movies SET featured = 0 WHERE id = $movieId");
            $message = "Movie removed from featured list.";
        } else {
            // Turn on featured, but check limit 10
            $cntRes = $conn->query("SELECT COUNT(*) AS c FROM movies WHERE featured = 1");
            $cntRow = $cntRes ? $cntRes->fetch_assoc() : ['c' => 0];

            if ($cntRow['c'] >= 10) {
                $message = "You can only have 10 featured movies.";
            } else {
                $conn->query("UPDATE movies SET featured = 1 WHERE id = $movieId");
                $message = "Movie added to featured list.";
            }
        }
    }
}

// Fetch movies
$movies = [];
$res = $conn->query("SELECT id, movie_name, language, certification, movie_price, release_date, movie_img, featured FROM movies ORDER BY id DESC");
if ($res) {
    while ($row = $res->fetch_assoc()) $movies[] = $row;
    $res->free();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Movies - CineBook Admin</title>

    <!-- Google Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial;
            background: #f5f6fc;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

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

        .layout {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 60px);
        }

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

        .content {
            flex: 1;
            padding: 30px;
            transition: margin 0.3s;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .message {
            margin-bottom: 10px;
            color: #0b5ed7;
            font-size: 14px;
        }

        .add-btn {
            display: inline-block;
            background: #6a11cb;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }

        .add-btn:hover {
            background: #2575fc;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            margin-top: 20px;
        }

        .movie-card {
            flex: 1 1 280px;
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
            transition: .3s;
            display: flex;
            flex-direction: column;
            padding: 18px;
        }

        .movie-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .poster-wrapper {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }

        .poster-wrapper img {
            width: 100%;
            max-height: 220px;
            object-fit: cover;
            border-radius: 10px;
        }

        .movie-title {
            font-size: 20px;
            font-weight: bold;
            color: #6a11cb;
            margin-bottom: 4px;
        }

        .movie-info {
            color: #666;
            font-size: 15px;
            margin-bottom: 3px;
        }

        .featured-badge {
            display: inline-block;
            margin-top: 4px;
            padding: 3px 8px;
            font-size: 12px;
            border-radius: 12px;
            background: #ffb703;
            color: #000;
            font-weight: 600;
        }

        .card-actions {
            margin-top: auto;
        }

        .edit-btn,
        .delete-btn,
        .feature-btn {
            padding: 7px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            margin-right: 6px;
            border: none;
            cursor: pointer;
        }

        .edit-btn {
            background: #2575fc;
        }

        .edit-btn:hover {
            background: #0b5ed7;
        }

        .delete-btn {
            background: #e63946;
        }

        .delete-btn:hover {
            background: #c71c29;
        }

        .feature-btn.on {
            background: #2ecc71;
        }

        .feature-btn.off {
            background: #6c757d;
        }

        .footer {
            background: #1d1b31;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 16px;
            margin-top: auto;
        }

        @media (max-width:900px) {
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

        @media (max-width:768px) {
            .content {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <div>📽️ CineBook Admin Panel</div>
    </div>

    <div class="layout">

        <div class="sidebar">
            <a href="dashboard.php"><span class="icon material-icons">dashboard</span><span>Dashboard</span></a>
            <a href="ad_movies.php"><span class="icon material-icons">movie</span><span>Movies</span></a>
            <a href="ad_cinemas.php"><span class="icon material-icons">theaters</span><span>Cinemas</span></a>
            <a href="ad_users.php"><span class="icon material-icons">people</span><span>Users</span></a>
            <a href="logout.php" style="color:#ff4d4d;"><span class="icon material-icons">logout</span><span>Logout</span></a>
        </div>

        <div class="content">
            <h2 class="page-title">Manage Movies</h2>
            <a href="ad_add_movies.php" class="add-btn">+ Add New Movie</a>

            <?php if ($message): ?>
                <div class="message"><?= htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="card-container">
                <?php if (empty($movies)): ?>
                    <p>No movies found.</p>
                    <?php else: foreach ($movies as $m): ?>
                        <div class="movie-card">
                            <div class="poster-wrapper">
                                <?php if (!empty($m['movie_img'])): ?>
                                    <img src="../movie img/<?= htmlspecialchars($m['movie_img']); ?>" alt="<?= htmlspecialchars($m['movie_name']); ?>">
                                <?php else: ?>
                                    <div style="width:100%;height:180px;background:#ddd;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#666;font-size:14px;">
                                        No Poster
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="movie-title"><?php echo htmlspecialchars($m['movie_name']); ?></div>
                            <div class="movie-info">Language: <?php echo htmlspecialchars($m['language']); ?></div>
                            <div class="movie-info">Certificate: <?php echo htmlspecialchars($m['certification']); ?></div>
                            <div class="movie-info">Price: ₹<?php echo htmlspecialchars($m['movie_price']); ?></div>
                            <div class="movie-info">Release: <?php echo htmlspecialchars($m['release_date']); ?></div>

                            <?php if ($m['featured']): ?>
                                <span class="featured-badge">Featured in Home Carousel</span>
                            <?php endif; ?>

                            <div class="card-actions" style="margin-top:10px;">
                                <a class="edit-btn" href="ad_edit_movies.php?id=<?php echo $m['id']; ?>">Edit</a>
                                <a class="delete-btn" href="ad_delete_movies.php?id=<?php echo $m['id']; ?>" onclick="return confirm('Delete this movie?')">Delete</a>

                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="toggle_id" value="<?php echo $m['id']; ?>">
                                    <button type="submit"
                                        class="feature-btn <?= $m['featured'] ? 'on' : 'off'; ?>">
                                        <?= $m['featured'] ? 'Unfeature' : 'Make Featured'; ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                <?php endforeach;
                endif; ?>
            </div>
        </div>
    </div>

    <div class="footer">
        © 2025 CineBook | Admin Panel
    </div>

</body>

</html>