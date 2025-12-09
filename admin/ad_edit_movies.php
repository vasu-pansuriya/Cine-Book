<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ad_login.php");
    exit();
}

include '../db_connect.php';

// fetch movie by ID
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<p>Missing movie ID</p>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$movie = $res->fetch_assoc();
$stmt->close();

if (!$movie) {
    echo "<p>Movie not found.</p>";
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_name   = trim($_POST['movie_name'] ?? '');
    $movie_img    = $_POST['movie_img'] ?? '';
    $language     = trim($_POST['language'] ?? '');
    $cert         = trim($_POST['certification'] ?? '');
    $price        = intval($_POST['movie_price'] ?? 0);
    $release      = $_POST['release_date'] ?? null;
    $available_in = $_POST['available_in'] ?? '';
    $duration     = $_POST['duration'] ?? '';
    $type         = $_POST['type'] ?? '';
    $description  = $_POST['description'] ?? '';
    $cast         = $_POST['cast'] ?? '';
    $cast_img     = $_POST['cast_img'] ?? '';
    $trailer      = $_POST['trailer'] ?? '';
    $gradient     = "vasu";

    // NEW: premiere flag
    $is_premiere  = isset($_POST['is_premiere']) ? 1 : 0;

    if ($movie_name === '') $errors[] = "Movie name is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE movies 
            SET movie_name=?, movie_img=?, available_in=?, language=?, duration=?, type=?, certification=?, release_date=?, movie_price=?, description=?, `cast`=?, cast_img=?, trailer=?, gradient=?, is_premiere=? 
            WHERE id=?");

        $stmt->bind_param(
            'ssssssssisssssii',
            $movie_name,
            $movie_img,
            $available_in,
            $language,
            $duration,
            $type,
            $cert,
            $release,
            $price,
            $description,
            $cast,
            $cast_img,
            $trailer,
            $gradient,
            $is_premiere,
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header('Location: ad_movies.php');
            exit;
        } else {
            $errors[] = "DB error: " . $conn->error;
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie | CineBook Admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
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

        .header {
            background: linear-gradient(90deg, #6a11cb, #2575fc);
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
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            font-size: 17px;
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #322f54;
        }

        .icon {
            font-size: 22px;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .dashboard-title {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-width: 800px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
        }

        button {
            background: #6a11cb;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .error-box {
            background: #fee;
            padding: 15px;
            border: 1px solid #f99;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .footer {
            background: #1d1b31;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 16px;
            margin-top: auto;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
            margin-bottom: 15px;
        }

        @media (max-width:768px) {
            .sidebar {
                display: none;
            }

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
            <a href="dashboard.php"><span class="icon material-icons">dashboard</span>Dashboard</a>
            <a href="ad_movies.php"><span class="icon material-icons">movie</span>Movies</a>
            <a href="ad_cinemas.php"><span class="icon material-icons">theaters</span>Cinemas</a>
            <a href="ad_users.php"><span class="icon material-icons">people</span>Users</a>
            <a href="logout.php" style="color:#ff4d4d;"><span class="icon material-icons">logout</span>Logout</a>
        </div>

        <div class="content">
            <h2 class="dashboard-title">Edit Movie</h2>

            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>"; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <form method="post">
                    <label>Movie Name</label>
                    <input type="text" name="movie_name" value="<?php echo htmlspecialchars($movie['movie_name']); ?>" required>

                    <label>Poster URL / File Name</label>
                    <input type="text" name="movie_img" value="<?php echo htmlspecialchars($movie['movie_img']); ?>">

                    <label>Language</label>
                    <input type="text" name="language" value="<?php echo htmlspecialchars($movie['language']); ?>">

                    <label>Certification</label>
                    <input type="text" name="certification" value="<?php echo htmlspecialchars($movie['certification']); ?>">

                    <label>Price (Rs)</label>
                    <input type="number" name="movie_price" value="<?php echo htmlspecialchars($movie['movie_price']); ?>" min="0">

                    <label>Release Date</label>
                    <input type="date" name="release_date" value="<?php echo htmlspecialchars($movie['release_date']); ?>">

                    <label>Available In</label>
                    <input type="text" name="available_in" value="<?php echo htmlspecialchars($movie['available_in']); ?>">

                    <label>Duration</label>
                    <input type="text" name="duration" value="<?php echo htmlspecialchars($movie['duration']); ?>">

                    <label>Type</label>
                    <input type="text" name="type" value="<?php echo htmlspecialchars($movie['type']); ?>">

                    <label>Cast</label>
                    <input type="text" name="cast" value="<?php echo htmlspecialchars($movie['cast']); ?>">

                    <label>Cast Img (comma separated)</label>
                    <input type="text" name="cast_img" value="<?php echo htmlspecialchars($movie['cast_img']); ?>">

                    <label>Trailer URL</label>
                    <input type="text" name="trailer" value="<?php echo htmlspecialchars($movie['trailer']); ?>">

                    <label>Description</label>
                    <textarea name="description" rows="6"><?php echo htmlspecialchars($movie['description']); ?></textarea>

                    <div class="checkbox-row">
                        <input type="checkbox" name="is_premiere" id="is_premiere" value="1"
                            <?php echo !empty($movie['is_premiere']) ? 'checked' : ''; ?>>
                        <label for="is_premiere" style="margin:0;">
                            Mark as <b>Premiere / Blockbuster</b> movie
                        </label>
                    </div>

                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">© 2025 CineBook | Admin Panel</div>

    <script src="js/ad_edit_movie_validation.js"></script>

</body>

</html>