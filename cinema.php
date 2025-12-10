<?php
include 'db_connect.php';
session_start();

// Get movie ID
$movie_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($movie_id <= 0) {
    die("Invalid Movie ID!");
}

// Fetch correct movie using WHERE id = ?
$stmt = $conn->prepare("SELECT movie_name, language, type, certification FROM movies WHERE id = ?");
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$movie_result = $stmt->get_result();

if ($movie_result->num_rows == 0) {
    die("Movie not found!");
}

$movie = $movie_result->fetch_assoc();

// Convert type into array
$type_arr = explode(", ", $movie['type']);

// Fetch cinemas
$cinemas = $conn->query("SELECT * FROM cinemas");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema</title>
    <link href="CSS/cinema.css" rel="stylesheet">

    <style>
        #body {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body id="body">

    <!-- header -->
    <?php include 'header.php'; ?>

    <div class="container" id="movie_name">
        <h1 style="letter-spacing: 4px;"><?= htmlspecialchars($movie["movie_name"]); ?></h1>

        <div class="child-container">
            <p><?= htmlspecialchars($movie["certification"]); ?></p>

            <?php foreach ($type_arr as $type) { ?>
                <p><?= htmlspecialchars($type); ?></p>
            <?php } ?>
        </div>
    </div>

    <!-- Date Selection -->
    <?php
    $selected_date = date("d-m-Y"); // today's date
    $dates = [];

    for ($i = 0; $i < 8; $i++) {
        $date = strtotime("+$i days");
        $dates[] = [
            'full_date' => date("d-m-Y", $date),
            'day' => strtoupper(date("D", $date)),
            'dateNum' => date("d", $date),
            'month' => strtoupper(date("M", $date)),
        ];
    }
    ?>

    <div class="container" id="date">
        <?php foreach ($dates as $date): ?>
            <div class="date-card <?= ($date['full_date'] == $selected_date) ? 'selected' : ''; ?>"
                onclick="selectDate(this, '<?= $date['full_date'] ?>')">
                <p><?= $date['day']; ?></p>
                <p style="font-size: 25px;"><?= $date['dateNum']; ?></p>
                <p><?= $date['month']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Hidden Selected Date -->
    <input type="hidden" id="selectedDate" value="<?= $selected_date; ?>">

    <script>
        function selectDate(elm, dateVal) {
            document.querySelectorAll(".date-card").forEach(card => card.classList.remove("selected"));
            elm.classList.add("selected");
            document.getElementById("selectedDate").value = dateVal;
        }
    </script>

    <!-- Cinema List -->
    <div class="container" id="cinema-container">
        <?php while ($row = $cinemas->fetch_assoc()) { ?>
            <div class="cinema-card">
                <h3><?= htmlspecialchars($row['name']); ?></h3>

                <!-- Features -->
                <?php if (!empty($row['features'])): ?>
                    <?php
                    $features = explode(",", $row['features']);
                    foreach ($features as $feature):
                        $feature = trim($feature);
                        if ($feature == "M-Ticket") {
                            echo '<img src="movie img/phone.svg" height="22px"><span style="color:rgb(235, 78, 98)"> M-Ticket</span>';
                        } else {
                            echo '<img src="movie img/food.svg" height="22px"><span style="color:rgb(242, 156, 28)"> Food & Beverage</span>';
                        }
                    endforeach;
                    ?>
                <?php endif; ?>

                <!-- Show Times -->
                <div class="show-times">
                    <?php
                    $show_times = explode(",", $row['show_times']);
                    foreach ($show_times as $time):
                    ?>
                        <button class="show-time"
                            onclick="storeSession(<?= $movie_id ?>, <?= $row['id']; ?>, '<?= trim($time); ?>')">
                            <?= htmlspecialchars(trim($time)); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <script>
                    function storeSession(movieId, cinemaId, time) {
                        let date = document.getElementById('selectedDate').value;

                        fetch('seats.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `movie_id=${movieId}&cinema_id=${cinemaId}&time=${encodeURIComponent(time)}&date=${encodeURIComponent(date)}`
                        }).then(() => window.location.href = 'seats.php');
                    }
                </script>

                <!-- Cancellation Policy -->
                <p class="cancellation">
                    <?= !empty($row['cancelation']) ? htmlspecialchars($row['cancelation']) : ''; ?>
                </p>
            </div>
        <?php } ?>
    </div>

    <br><br><br>

    <!-- footer -->
    <?php include 'footer.php'; ?>

</body>

</html>