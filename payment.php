<?php
// use to display error in starting 
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include "db_connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // If UPI form is submitted
    if (isset($_POST['upi'], $_POST['password'])) {
        $upi_id = $_POST['upi'];
        $upi_password = $_POST['password'];

        $movie_id = $_SESSION['movie_id'];
        $cinema_id = $_SESSION['cinema_id'];
        $time = $_SESSION['time'];
        $date = $_SESSION['date'];
        $selectedSeats = $_SESSION['selectedSeats'];
        $user_id = 1; // Replace with session user_id if login system

        $movie_price = 200;
        $no_of_seats = count($selectedSeats);
        $total_price = $movie_price * $no_of_seats;
        $booking_date = date("Y-m-d", strtotime($date));

        // $conn->query("SET foreign_key_checks = 0");

        $seat_ids = implode(",", $selectedSeats); // convert array to comma-separated string

        $sql = "INSERT INTO bookings (movie_id, user_id, seat_id, movie_price, no_of_seats, total_price, booking_date, upi_id, upi_password, show_time)
        VALUES ('$movie_id', '$user_id', '$seat_ids', '$movie_price', '$no_of_seats', '$total_price', '$booking_date', '$upi_id', '$upi_password', '$time')";
        if ($conn->query($sql) === TRUE) {
            echo "Query executed successfully: $sql <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


        $sql = "INSERT INTO seats (seat_id, movie_id, cinema_id )
        VALUES ( '$seat_ids', '$movie_id', '$cinema_id')";
        if ($conn->query($sql) === TRUE) {
            echo "Query executed successfully: $sql <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }



        echo "<script>alert('Payment successful! Booking done.');</script>";

        echo '<script>window.location.href="index.php";</script>';
    } else {
        // Storing booking data from previous page
        $_SESSION['movie_id'] = $_POST['movie_id'];
        $_SESSION['cinema_id'] = $_POST['cinema_id'];
        $_SESSION['time'] = $_POST['time'];
        $_SESSION['date'] = $_POST['date'];
        $_SESSION['selectedSeats'] = explode(",", $_POST['selectedSeats']);
    }
}

// Get data from session
$movie_id = $_SESSION['movie_id'] ?? null;
$cinema_id = $_SESSION['cinema_id'] ?? null;
$time = $_SESSION['time'] ?? null;
$date = $_SESSION['date'] ?? null;
$selectedSeats = $_SESSION['selectedSeats'] ?? [];

if (!$movie_id || !$cinema_id || !$time || !$date || empty($selectedSeats)) {
    die("No session data found.");
}

// Fetch movie details
$sql = "SELECT movie_name, language, certification FROM movies WHERE id = $movie_id";
$result = $conn->query($sql);
$movies = $result->fetch_assoc();

// Fetch cinema name
$sql = "SELECT name FROM cinemas WHERE id = $cinema_id";
$result = $conn->query($sql);
$cinema_name = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>UPI Payment</title>
    <link rel="stylesheet" href="CSS/payment.css">
</head>

<body style="background-color: #fff;">
    <?php include 'header.php'; ?>

    <div class="container" id="main-container">
        <div class="container" id="order-container">
            <div class="container" id="order">
                <p style="letter-spacing: 6px; font-weight:400">ORDER SUMMARY</p>
                <p id="lt-space" style="font-weight:500; margin-bottom:5px"><?= $movies["movie_name"] ?> (<?= $movies["certification"] ?>)</p>
                <p style="color:gray; margin:0px;"> <?= $movies["language"] ?> </p>
                <p id="lt-space" style="margin: 20px 0px 0px 0px; "><?= $cinema_name["name"] ?><br></p>
                <p style="letter-spacing: 1px; margin-bottom:10px">
                    Seats: <?= implode(", ", $selectedSeats) ?><br>
                    <?= date("D, d M, Y", strtotime($date)) ?><br>
                    <?= $time ?>
                </p>
            </div>

            <div class="container" id="amt-paybal">
                <p style="letter-spacing: 1px; margin-bottom: 0px;">Amount Paybal</p>
                <p>Rs <?= count($selectedSeats) * 200 ?></p>
            </div>

            <div class="container" id="name">
                <p> Cine Book </p>
            </div>
        </div>

        <!-- UPI PAYMENT FORM -->
        <div class="container" id="payment-container">
            <p style="font-size:25px; letter-spacing:3px; font-weight: 500">UPI Payment</p>
            <form method="POST">
                <div class="input-group">
                    <label for="upi">Enter UPI ID</label>
                    <input type="text" id="upi" name="upi" placeholder="example@upi" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="text" id="password" name="password" required>
                </div>
                <button type="submit" class="btn" id="btn">Pay Now</button>
            </form>
        </div>
    </div>
</body>

</html>