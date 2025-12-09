<?php
include 'db_connect.php';

session_start(); // Start session

if (isset($_GET['id'])) {
    $movie_id = htmlspecialchars($_GET['id']);
}

// Fetch movie data
$sql = "SELECT movie_name, language, type, certification FROM movies";
$result = $conn->query($sql);

$i = $movie_id - 1;
$j = 0; // used for $cast_array
$movies = []; // Create an empty array

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row; // Store each movie in an array
    }
    $type_string = $movies[$i]['type'];
    $type_arr = explode(', ', $type_string);
}

// Fetch cinemas
$sql = "SELECT * FROM cinemas";
$result = $conn->query($sql);
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
        <h1 style="letter-spacing: 4px;"><?= htmlspecialchars($movies[$i]["movie_name"]); ?></h1>
        <div class="child-container">
            <p><?= htmlspecialchars($movies[$i]["certification"]); ?></p>

            <?php foreach ($type_arr as $type) { ?>
                <p><?= htmlspecialchars($type); ?></p>
            <?php } ?>
        </div>
    </div>

    <!-- date -->
    <?php
    $selected_date = date("d-m-Y"); // Today's date in "DD-MM-YYYY" format
    $dates = [];

    // Generate the next 8 days
    for ($i = 0; $i < 8; $i++) {
        $date = strtotime("+$i days");
        $formatted_date = date("d-m-Y", $date); // Format: DD-MM-YYYY
        $day = strtoupper(date("D", $date));
        $dateNum = date("d", $date);
        $month = strtoupper(date("M", $date));

        // Store date info in array
        $dates[] = [
            'full_date' => $formatted_date,
            'day' => $day,
            'dateNum' => $dateNum,
            'month' => $month
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

    <!-- user select booking date -->
    <?php $booking_date = $selected_date; ?>

    <!-- Hidden input to store the selected date -->
    <input type="hidden" id="selectedDate" value="<?= $booking_date; ?>">

    <script>
        function selectDate(element, dateValue) {
            document.querySelectorAll(".date-card").forEach(card => {
                card.classList.remove("selected");
            });

            element.classList.add("selected");
            document.getElementById("selectedDate").value = dateValue;
        }
    </script>

    <!-- Cinema  -->
    <div class="container" id="cinema-container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="cinema-card">
                <h3><?= htmlspecialchars($row['name']); ?></h3>

                <?php if (!empty($row['features'])) { ?>
                    <?php
                    $features = explode(",", $row['features']);
                    foreach ($features as $feature) {
                        $feature = trim($feature);

                        if ($feature == "M-Ticket") {
                            echo '<img src="movie img/phone.svg" height="22px" alt="M-Ticket" style="margin-right: 10px">';
                            echo '<span class="feature" style="color:rgb(235, 78, 98)">M-Ticket</span>';
                        } else {
                            echo '<img src="movie img/food.svg" height="22px" alt="Food & Beverage" style="margin-right: 10px">';
                            echo '<span class="feature" style="color:rgb(242, 156, 28)">Food & Beverage</span>';
                        }
                    }
                    ?>
                <?php } ?>

                <div class="show-times">
                    <?php
                    $show_times = explode(",", $row['show_times']);
                    foreach ($show_times as $time) { ?>
                        <button class="show-time"
                            onclick="storeSession(<?= $movie_id ?>, <?= $row['id']; ?>, '<?= trim($time); ?>')">
                            <?= htmlspecialchars(trim($time)); ?>
                        </button>
                    <?php } ?>
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
                        }).then(() => {
                            window.location.href = 'seats.php';
                        });
                    }
                </script>

                <!-- FIXED: use 'cancelation' (DB column) instead of 'cancellation' -->
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