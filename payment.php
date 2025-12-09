<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
include "db_connect.php";

// Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // If UPI form is submitted (final payment)
    if (isset($_POST['upi'], $_POST['password'])) {

        $upi_id = trim($_POST['upi']);
        // We DO NOT store the UPI password for security reasons

        // Get booking data from session
        $movie_id      = $_SESSION['movie_id'] ?? null;
        $cinema_id     = $_SESSION['cinema_id'] ?? null;
        $time          = $_SESSION['time'] ?? null;
        $date          = $_SESSION['date'] ?? null;
        $selectedSeats = $_SESSION['selectedSeats'] ?? [];
        $user_id       = $_SESSION['user_id'];

        if (!$movie_id || !$cinema_id || !$time || !$date || empty($selectedSeats)) {
            die("Booking session data missing. Please start again.");
        }

        $movie_price  = 200;
        $no_of_seats  = count($selectedSeats);
        $total_price  = $movie_price * $no_of_seats;
        $booking_date = date("Y-m-d", strtotime($date));
        $seat_ids_str = implode(",", $selectedSeats); // For bookings table only

        // ---- Insert into bookings (secure) ----
        $bookingSql = "INSERT INTO bookings 
            (movie_id, user_id, seat_id, movie_price, no_of_seats, total_price, booking_date, upi_id, show_time)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $bookingStmt = $conn->prepare($bookingSql);
        if (!$bookingStmt) {
            die("Booking prepare failed: " . $conn->error);
        }

        $bookingStmt->bind_param(
            "iisdiisss",
            $movie_id,
            $user_id,
            $seat_ids_str,
            $movie_price,
            $no_of_seats,
            $total_price,
            $booking_date,
            $upi_id,
            $time
        );

        if (!$bookingStmt->execute()) {
            die("Booking insert failed: " . $bookingStmt->error);
        }
        $bookingStmt->close();

        // ---- Insert each seat separately in seats table ----
        $seatSql = "INSERT INTO seats (seat_id, movie_id, cinema_id) VALUES (?, ?, ?)";
        $seatStmt = $conn->prepare($seatSql);
        if (!$seatStmt) {
            die("Seat prepare failed: " . $conn->error);
        }

        foreach ($selectedSeats as $seatCode) {
            $seatCodeTrimmed = trim($seatCode);
            if ($seatCodeTrimmed === '') continue;

            $seatStmt->bind_param("sii", $seatCodeTrimmed, $movie_id, $cinema_id);
            // You might want to handle duplicate seats gracefully
            $seatStmt->execute();
        }

        $seatStmt->close();

        // Clear booking data from session
        unset($_SESSION['movie_id'], $_SESSION['cinema_id'], $_SESSION['time'], $_SESSION['date'], $_SESSION['selectedSeats']);

        echo "<script>alert('Payment successful! Booking done.'); window.location.href='index.php';</script>";
        exit;
    } else {
        // First time landing here from previous page -> store booking data to session
        $_SESSION['movie_id']      = $_POST['movie_id'] ?? null;
        $_SESSION['cinema_id']     = $_POST['cinema_id'] ?? null;
        $_SESSION['time']          = $_POST['time'] ?? null;
        $_SESSION['date']          = $_POST['date'] ?? null;
        $_SESSION['selectedSeats'] = isset($_POST['selectedSeats']) ? explode(",", $_POST['selectedSeats']) : [];
    }
}

// Get data from session for display
$movie_id      = $_SESSION['movie_id'] ?? null;
$cinema_id     = $_SESSION['cinema_id'] ?? null;
$time          = $_SESSION['time'] ?? null;
$date          = $_SESSION['date'] ?? null;
$selectedSeats = $_SESSION['selectedSeats'] ?? [];

if (!$movie_id || !$cinema_id || !$time || !$date || empty($selectedSeats)) {
    die("No session data found. Please select a show again.");
}

// Fetch movie details
$sql    = "SELECT movie_name, language, certification FROM movies WHERE id = ?";
$stmt   = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movies = $result->fetch_assoc();
$stmt->close();

// Fetch cinema name
$sql    = "SELECT name FROM cinemas WHERE id = ?";
$stmt   = $conn->prepare($sql);
$stmt->bind_param("i", $cinema_id);
$stmt->execute();
$result      = $stmt->get_result();
$cinema_name = $result->fetch_assoc();
$stmt->close();

$total_amount = count($selectedSeats) * 200;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>UPI Payment - Cine Book</title>
    <link rel="stylesheet" href="CSS/payment.css">

    <style>
        /* Make the cards a bit smaller/tighter */
        body {
            background-color: #fff;
            font-family: "Funnel Sans", sans-serif;
        }

        #main-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 40px auto;
            max-width: 900px;
        }

        #order-container,
        #payment-container {
            max-width: 380px;
            padding: 16px 18px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            background: #fdfdfd;
        }

        #order p {
            margin: 4px 0;
            font-size: 14px;
        }

        #amt-paybal p {
            margin: 4px 0;
        }

        #payment-container p {
            margin: 6px 0 14px;
        }

        .input-group {
            margin-bottom: 10px;
            text-align: left;
        }

        .input-group label {
            font-size: 13px;
            font-weight: 600;
        }

        .input-group input {
            width: 100%;
            padding: 7px 8px;
            margin-top: 3px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .input-group input.error {
            border-color: #e63946;
        }

        .input-group input.valid {
            border-color: #2ecc71;
        }

        .field-msg {
            font-size: 11px;
            min-height: 12px;
            margin-top: 2px;
        }

        .error-message {
            color: #e63946;
        }

        .success-message {
            color: #2ecc71;
        }

        .btn {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            background: #a71d31;
            color: #fff;
            margin-top: 6px;
            transition: 0.2s;
        }

        .btn:hover {
            background: #8d1527;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            #main-container {
                flex-direction: column;
                align-items: center;
                max-width: 95%;
            }
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container" id="main-container">
        <!-- ORDER SUMMARY -->
        <div class="container" id="order-container">
            <div class="container" id="order">
                <p style="letter-spacing: 4px; font-weight:400; font-size:13px;">ORDER SUMMARY</p>
                <p id="lt-space" style="font-weight:500; margin-bottom:3px; font-size:16px;">
                    <?= htmlspecialchars($movies["movie_name"]); ?> (<?= htmlspecialchars($movies["certification"]); ?>)
                </p>
                <p style="color:gray; margin:0px; font-size:13px;">
                    <?= htmlspecialchars($movies["language"]); ?>
                </p>
                <p id="lt-space" style="margin: 15px 0px 0px 0px; font-size:13px;">
                    <?= htmlspecialchars($cinema_name["name"]); ?><br>
                </p>
                <p style="letter-spacing: 1px; margin-bottom:8px; font-size:13px;">
                    Seats: <?= htmlspecialchars(implode(", ", $selectedSeats)); ?><br>
                    <?= date("D, d M, Y", strtotime($date)); ?><br>
                    <?= htmlspecialchars($time); ?>
                </p>
            </div>

            <div class="container" id="amt-paybal" style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;">
                <p style="letter-spacing: 1px; margin-bottom: 0px; font-size:13px;">Amount Payable</p>
                <p style="font-size:18px; font-weight:600;">₹ <?= $total_amount; ?></p>
            </div>

            <div class="container" id="name" style="margin-top:8px;">
                <p style="font-size:13px; color:#555;">Cine Book</p>
            </div>
        </div>

        <!-- UPI PAYMENT FORM -->
        <div class="container" id="payment-container">
            <p style="font-size:20px; letter-spacing:2px; font-weight: 500">UPI Payment</p>

            <form method="POST" id="upiForm" novalidate>
                <div class="input-group">
                    <label for="upi">Enter UPI ID</label>
                    <input type="text" id="upi" name="upi" placeholder="example@upi">
                    <div id="upiMsg" class="field-msg"></div>
                </div>

                <div class="input-group">
                    <label for="password">UPI PIN</label>
                    <input type="password" id="password" name="password">
                    <div id="pinMsg" class="field-msg"></div>
                </div>

                <button type="submit" class="btn" id="btnPay" disabled>Pay Now</button>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const upiInput = document.getElementById("upi");
            const pinInput = document.getElementById("password");
            const upiMsg = document.getElementById("upiMsg");
            const pinMsg = document.getElementById("pinMsg");
            const btn = document.getElementById("btnPay");
            const form = document.getElementById("upiForm");

            function setError(input, msgElem, message) {
                input.classList.add("error");
                input.classList.remove("valid");
                msgElem.textContent = message;
                msgElem.classList.remove("success-message");
                msgElem.classList.add("error-message");
                return false;
            }

            function setSuccess(input, msgElem, message) {
                input.classList.remove("error");
                input.classList.add("valid");
                msgElem.textContent = message;
                msgElem.classList.remove("error-message");
                msgElem.classList.add("success-message");
                return true;
            }

            function validateUPI() {
                const val = upiInput.value.trim();
                if (val === "") {
                    return setError(upiInput, upiMsg, "UPI ID is required.");
                }
                // simple UPI format: something@bank
                const upiRegex = /^[a-zA-Z0-9.\-_]{2,}@[a-zA-Z]{2,}$/;
                if (!upiRegex.test(val)) {
                    return setError(upiInput, upiMsg, "Enter valid UPI ID (example@bank).");
                }
                return setSuccess(upiInput, upiMsg, "UPI ID looks good.");
            }

            function validatePIN() {
                const val = pinInput.value.trim();
                if (val === "") {
                    return setError(pinInput, pinMsg, "UPI PIN is required.");
                }
                if (!/^[0-9]{4,6}$/.test(val)) {
                    return setError(pinInput, pinMsg, "UPI PIN must be 4–6 digits.");
                }
                return setSuccess(pinInput, pinMsg, "UPI PIN format is valid.");
            }

            function updateButtonState() {
                const v1 = validateUPI();
                const v2 = validatePIN();
                btn.disabled = !(v1 && v2);
            }

            upiInput.addEventListener("input", updateButtonState);
            pinInput.addEventListener("input", updateButtonState);

            upiInput.addEventListener("blur", validateUPI);
            pinInput.addEventListener("blur", validatePIN);

            form.addEventListener("submit", function(e) {
                updateButtonState();
                if (btn.disabled) {
                    e.preventDefault();
                }
            });

        })();
    </script>
</body>

</html>