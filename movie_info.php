<?php
session_start();
include 'db_connect.php';

// Get movie ID
$movie_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isLoggedIn = isset($_SESSION['user_id']);

// Fetch movie by ID
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();

// If movie not found
if ($result->num_rows == 0) {
    die("Movie not found!");
}

$movie = $result->fetch_assoc();

// Cast arrays
$cast_img_array = explode(", ", $movie['cast_img']);
$cast_array = explode(", ", $movie['cast']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Info</title>

    <link href="CSS/movie_info.css" rel="stylesheet">

    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        body {
            overflow: auto;
            font-family: "Funnel Sans", sans-serif;
        }

        .movie_info_container {
            background-image: linear-gradient(to top left, #A71D31, #0d0a0b);
        }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="movie_info_container">

        <div class="movie_img">
            <img src="movie img/<?= $movie["movie_img"]; ?>" alt="Movie">
        </div>

        <div class="movie_info">
            <div class="movie_info_child">
                <h1><?= $movie["movie_name"]; ?></h1><br>

                <p>
                    Available In: <?= $movie["available_in"]; ?><br>
                    Language: <?= $movie["language"]; ?> <br>
                    <?= $movie["duration"]; ?> •
                    <?= $movie["type"]; ?> •
                    <?= $movie["certification"]; ?> <br>
                    <?= $movie["release_date"]; ?>
                </p><br>

                <div class="btns">
                    <button class="button" id="btnTrailer">Trailer</button>
                    <button class="button" id="btnBookNow">Book Now</button>
                </div>
            </div>
        </div>

    </div>

    <!-- About Movie -->
    <div class="description">
        <h3>About the Movie</h3>
        <p><?= $movie["description"]; ?></p>
    </div>

    <br><br>

    <!-- Cast Section -->
    <div class="cast">
        <h3>Cast</h3>

        <div class="cast-container">
            <?php for ($i = 0; $i < count($cast_img_array); $i++): ?>
                <div class="cast-cards">
                    <img src="cast img/<?= $cast_img_array[$i]; ?>" class="cast_img" height="120" width="120">
                    <p><?= $cast_array[$i] ?? ""; ?></p>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <br><br>

    <?php include 'footer.php'; ?>

    <!-- Login Check -->
    <script>
        const IS_LOGGED_IN = <?= $isLoggedIn ? 'true' : 'false'; ?>;
        const TRAILER_URL = "<?= $movie['trailer']; ?>";
        const CINEMA_URL = "cinema.php?id=<?= $movie_id; ?>";

        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById("btnTrailer").onclick = () => {
                if (!IS_LOGGED_IN) {
                    alert("Please login first to watch the trailer!");
                    window.location.href = "login.php";
                } else {
                    window.open(TRAILER_URL, "_blank");
                }
            };

            document.getElementById("btnBookNow").onclick = () => {
                if (!IS_LOGGED_IN) {
                    alert("Please login first to book tickets!");
                    window.location.href = "login.php";
                } else {
                    window.location.href = CINEMA_URL;
                }
            };
        });
    </script>

</body>

</html>