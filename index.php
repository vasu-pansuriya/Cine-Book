<?php
session_start();
include "db_connect.php";

// Recommended (all movies)
$sql = "SELECT id, movie_name, movie_img FROM movies";
$result = $conn->query($sql);
$movies = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
}

// Premiere / Blockbuster movies – max 5
$sqlPremiere = "SELECT id, movie_name, movie_img 
                FROM movies 
                WHERE is_premiere = 1 
                ORDER BY release_date DESC 
                LIMIT 5";
$resPrem = $conn->query($sqlPremiere);
$premiereMovies = [];

if ($resPrem && $resPrem->num_rows > 0) {
    while ($row = $resPrem->fetch_assoc()) {
        $premiereMovies[] = $row;
    }
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cine Book</title>
    <link href="CSS/index.css" rel="stylesheet">
    <script src="JS/index.js"></script>

    <style>
        ::-webkit-scrollbar {
            display: none;
        }

        body {
            overflow: auto;
            font-family: "Funnel Sans", sans-serif;
            font-weight: 500;
        }

        .background {
            background: url("movie img/b.png") no-repeat center center/cover;
        }

        .slogan {
            font-family: "Audiowide", sans-serif;
        }
    </style>
</head>

<body>

    <div class="background"></div>

    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-light text-decoration-none">
                <img src="movie img/logo.png" alt="logo" height="50px" style="margin-right: 30px;" />
                <span class="fs-4">Cine Book</span>
            </a>

            <ul class="nav nav-pills">
                <li class="nav-item"><a href="index.php" class="nav-link text-light" aria-current="page">Home</a></li>
                <li class="nav-item"><a href="features.php" class="nav-link text-light">Features</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link text-light">About</a></li>

                <?php if ($isLoggedIn): ?>
                    <button class="Btn" onclick="window.location.href='logout.php';">
                        <div class="sign">
                            <svg viewBox="0 0 512 512">
                                <path
                                    d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                                </path>
                            </svg>
                        </div>
                        <div class="text">Logout</div>
                    </button>
                <?php else: ?>
                    <li class="nav-item"><a href="login.php" class="nav-link text-light">Login</a></li>
                    <li class="nav-item"><a href="register.php" class="nav-link text-light">Register</a></li>
                <?php endif; ?>
            </ul>
        </header>

        <button class="button">Grab Now</button>
    </div>

    <!-- Recommended Movie -->
    <div class="movies-title">
        <h3><a href="#" class="underline">Recommended Movies</a></h3>
    </div>

    <div class="movie-slider-container">
        <div class="movie-slider">
            <?php foreach ($movies as $movie): ?>
                <div class="movie-card">
                    <a href="movie_info.php?id=<?= $movie['id']; ?>">
                        <img src="movie img/<?= htmlspecialchars($movie['movie_img']); ?>"
                            alt="<?= htmlspecialchars($movie['movie_name']); ?>" />
                    </a>
                    <p><?= htmlspecialchars($movie['movie_name']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="prev" style="display: none" onclick="moveSlider(-1)">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="8" class="arrow" />
                <path
                    d="M8 0a8 8 0 0 0 0 16A8 8 0 0 0 8 0m3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"
                    class="background" />
            </svg>
        </button>
        <button class="next" onclick="moveSlider(1)">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" heigmovie viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="8" class="background" />
                <path
                    d="M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"
                    class="arrow" />
            </svg>
        </button>
    </div>

    <!--  Slogan -->
    <div class="slogan">
        <center>
            <p><a href="#">Rule the Box Office with Cine Book</a></p>
        </center>
    </div>

    <!-- Premiere Movies -->
    <div class="pmovies-title">
        <div class="play-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
                class="bi bi-play-fill" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="8" class="play" />
                <path
                    d="m11.596 8.697-6.363
      3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363
      3.692a.802.802 0 0 1 0 1.393" class="play-icon-background">
            </svg>
        </div>
        <div class="content">
            <h3><a href="#" class="underline">Premiere Movies</a></h3>
            <p>Brand New Release</p>
        </div>
    </div>

    <div class="premiere-movies">
        <div class="pmovie-container">
            <?php if (!empty($premiereMovies)): ?>
                <?php foreach ($premiereMovies as $pm): ?>
                    <div class="pmovie-card">
                        <a href="movie_info.php?id=<?= $pm['id']; ?>">
                            <img src="movie img/<?= htmlspecialchars($pm['movie_img']); ?>"
                                alt="<?= htmlspecialchars($pm['movie_name']); ?>" />
                        </a>
                        <p><?= htmlspecialchars($pm['movie_name']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:white; padding:10px;">
                    No Premiere / Blockbuster movies selected yet.
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Trending Search  -->
    <div class="trending-search-title">
        <h3><a href="#" class="underline">Trending Search Right Now</a></h3>
    </div>

    <div class="trending-search">
        <div class="movie-trend">
            <p class="p1">Captain America: Brave New World</p>
            <p class="p2">Movies</p>
        </div>
        <div class="movie-trend">
            <p class="p1">Jaat</p>
            <p class="p2">Movies</p>
        </div>
        <div class="movie-trend">
            <p class="p1">Daaku Maharaaj</p>
            <p class="p2">Movies</p>
        </div>
        <div class="movie-trend">
            <p class="p1">Leo</p>
            <p class="p2">Movies</p>
        </div>
        <div class="movie-trend">
            <p class="p1">Pushpa : The Rule - Part 2</p>
            <p class="p2">Movies</p>
        </div>
        <div class="movie-trend">
            <p class="p1">Chhaava</p>
            <p class="p2">Movies</p>
        </div>
        <div class="movie-trend">
            <p class="p1">Marco</p>
            <p class="p2">Movies</p>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>

</html>