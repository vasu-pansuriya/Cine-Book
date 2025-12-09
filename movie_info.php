<?php
session_start();
include 'db_connect.php';

$movie_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isLoggedIn = isset($_SESSION['user_id']);

// Fetch movie data (your original style)
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

$i = $movie_id - 1;
$j = 0;
$movies = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }

    $cast_img_string = $movies[$i]['cast_img'];
    $cast_img_array = explode(", ", $cast_img_string);

    $cast_string = $movies[$i]['cast'];
    $cast_array = explode(", ", $cast_string);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Info</title>

    <link href="CSS/movie_info.css" rel="stylesheet">

    <style>
        /* Hide scrollbar in WebKit browsers */
        ::-webkit-scrollbar {
            display: none;
        }

        body {
            overflow: auto;
            font-family: "Funnel Sans", sans-serif;
            font-weight: 500;
        }

        .movie_info_container {
            /* background-image: <?= $movies[$i]["gradient"]; ?>; */
            background-image: linear-gradient(to top left, #A71D31, #0d0a0b);
        }
    </style>
</head>

<body>

    <!-- header -->
    <?php include 'header.php'; ?>

    <div class="movie_info_container">

        <div class="movie_img">
            <img src="movie img/<?= $movies[$i]["movie_img"]; ?>" alt="Movie" />
        </div>

        <div class="movie_info">
            <div class="movie_info_child">
                <h1><?= $movies[$i]["movie_name"]; ?></h1><br>
                <p>
                    Available In <?= $movies[$i]["available_in"]; ?><br>
                    Language : <?= $movies[$i]["language"]; ?> <br>
                    <?= $movies[$i]["duration"]; ?> •
                    <?= $movies[$i]["type"]; ?> •
                    <?= $movies[$i]["certification"]; ?> <br>
                    <?= $movies[$i]["release_date"]; ?>
                </p><br>

                <div class="btns">
                    <!-- Buttons without direct links -->
                    <button class="button" id="btnTrailer">Trailer</button>
                    <button class="button" id="btnBookNow">Book Now</button>
                </div>
            </div>
        </div>
    </div>

    <!-- About Movie -->
    <div class="description">
        <h3>About the Movie</h3>
        <p><?= $movies[$i]["description"]; ?></p>
    </div>
    <br><br>

    <!-- cast  -->
    <div class="cast">
        <h3>Cast</h3>

        <div class="cast-container">
            <?php foreach ($cast_img_array as $cast_member): ?>
                <div class="cast-cards">
                    <img src="cast img/<?php echo $cast_member; ?>" class="cast_img" height="120px" width="120px">
                    <p><?php echo $cast_array[$j]; ?></p>
                    <?php $j++; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <br><br>

    <!-- footer -->
    <?php include 'footer.php'; ?>

    <!-- JS: Login check for Trailer + Book Now -->
    <script>
        const IS_LOGGED_IN = <?= $isLoggedIn ? 'true' : 'false'; ?>;
        const TRAILER_URL = "<?= $movies[$i]['trailer']; ?>";
        const CINEMA_URL = "cinema.php?id=<?= $movie_id; ?>";

        document.addEventListener("DOMContentLoaded", function() {
            const btnTrailer = document.getElementById("btnTrailer");
            const btnBookNow = document.getElementById("btnBookNow");

            btnTrailer.addEventListener("click", function() {
                if (!IS_LOGGED_IN) {
                    alert("Please login first to watch the trailer!");
                    window.location.href = "login.php";
                } else {
                    window.open(TRAILER_URL, '_blank');
                }
            });

            btnBookNow.addEventListener("click", function() {
                if (!IS_LOGGED_IN) {
                    alert("Please login first to book tickets!");
                    window.location.href = "login.php";
                } else {
                    window.location.href = CINEMA_URL;
                }
            });
        });
    </script>

</body>

</html>