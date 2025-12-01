<?php
include 'db_connect.php';

if(isset($_GET['id'])){
    $movie_id = htmlspecialchars($_GET['id']);
}


// Fetch movie data
// $sql = "SELECT movie_name, available_in, language, duration, type, certification, release_date FROM movies";
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

$i = $movie_id - 1;
$j = 0; // used for $cast_array
$movies = []; // Create an empty array

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row; // Store each movie in an array
    }

    $cast_img_string = $movies[$i]['cast_img']; // Get cast data (comma-separated)
    // Convert the comma-separated string into an array
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
            /* Allows scrolling */
            /* font-family: 'Poppins', sans-serif;; */
            font-family: "Funnel Sans", sans-serif;
            font-weight: 500;
        }

        .movie_info_container {
            /* background-image: <?= $movies[$i]["gradient"]; ?>; */
            background-image:  linear-gradient( to top left, #A71D31, #0d0a0b);
        }
    </style>


</head>

<body>

    

<!-- header -->
<?php
    include 'header.php'
    ?>
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
                    <!-- <button class="button" onclick="window.location.href = '<?= $movies[$i]['trailer']; ?>';">Trailer</button> -->
                    <button class="button" onclick="window.open('<?= $movies[$i]['trailer']; ?>', '_blank');">
                        Trailer
                    </button>


                    <button class="button" onclick="window.location.href='cinema.php?id=<?php echo $movie_id;?>'">Book Now</button>
                </div>
            </div>
        </div> 
    </div>

    <!-- About Movie -->

    <div class="description">
        <h3>About the Movie</h3>
        <p>
            <?= $movies[$i]["description"]; ?>
        </p>
    </div>
    <br><br>

    <!-- cast  -->

    <div class="cast">

        <h3>Cast</h3>

        <div class="cast-container">
            <?php foreach ($cast_img_array as $cast_member) { ?>
                <div class="cast-cards">
                    <img src="cast img/<?php echo $cast_member; ?>" class="cast_img" height="120px" width="120px">

                    <P> <?php echo $cast_array[$j] ?> </P>
                    <?php $j++; ?>
                </div>
            <?php } ?>

        </div>

    </div>
    <br><br>

    <!-- footer -->

    <?php
    include 'footer.php'
    ?>

</body>

</html>