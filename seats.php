<?php
session_start();
include "db_connect.php";

// // Store posted data into session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['movie_id'] = $_POST['movie_id'];
    $_SESSION['cinema_id'] = $_POST['cinema_id'];
    $_SESSION['time'] = $_POST['time'];
    $_SESSION['date'] = $_POST['date'];

    header("Location: seats.php");
    exit();
}

// Retrieve session data
if (isset($_SESSION['movie_id'], $_SESSION['cinema_id'], $_SESSION['time'], $_SESSION['date'])) {
    $movie_id = $_SESSION['movie_id'];
    $cinema_id = $_SESSION['cinema_id'];
    $time = $_SESSION['time'];
    $date = $_SESSION['date'];

    // echo "Movie ID: $movie_id <br>";
    // echo "Cinema ID: $cinema_id <br>";
    // echo "Show Time: $time <br>";
    // echo "Date: $date <br>";
} else {
    echo "No session data found.";
    exit();
}

// SELECT seat_id FROM `bookings` WHERE booking_date = '2025-04-12';

// Fetch booked seats
$bookedSeats = [];

// change the date for sql query because data is store in table in form of yyyy-mm-dd
$date = date('Y-m-d', strtotime($date));


$query = "SELECT seat_id FROM bookings 
          WHERE movie_id = '$movie_id' 
          AND show_time = '$time' 
          AND booking_date = '$date'";
$result = $conn->query($query);

if (!$result) {
    die("Query error: " . $conn->error);
}

$bookedSeats=[];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $seats = explode(',', $row['seat_id']);
        foreach ($seats as $seat) {
            $bookedSeats[] = trim($seat);
        }
    }
}
//  else {
//     echo "No data Found";
// }
// print_r($bookedSeats);




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Seat Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-bottom: 80px;
            /* match footer height */
        }

        .Btn {
            margin-top: 7vh;
            margin-left: 7vw;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition-duration: .3s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
            /* background-color: rgb(255, 65, 65); */
            background-color: rgb(248, 68, 100);
        }

        /* plus sign */
        .sign {
            width: 100%;
            transition-duration: .3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sign svg {
            width: 17px;
        }

        .sign svg path {
            fill: white;
        }

        /* text */
        .text {
            position: absolute;
            right: 0%;
            width: 0%;
            opacity: 0;
            color: white;
            font-size: 1.2em;
            font-weight: 600;
            transition-duration: .3s;
        }

        /* hover effect on button width */
        .Btn:hover {
            width: 125px;
            border-radius: 40px;
            transition-duration: .3s;
        }

        .Btn:hover .sign {
            width: 30%;
            transition-duration: .3s;
            padding-left: 20px;
        }

        /* hover effect button's text */
         .Btn:hover .text {
            opacity: 1;
            width: 70%;
            transition-duration: .3s;
            padding-right: 10px;
        }  

        /* button click effect*/
        .Btn:active {
            transform: translate(2px, 2px);
        }

        h2 {
            margin-top: 5vh;
        }

        .screen {
            background: gray;
            height: 35px;
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            color: white;
            line-height: 35px;
        }

        .seats-container-wrapper {
            width: 100%;
            max-width: fit-content;
            margin: auto;
            overflow-x: auto;
        }

        .seats-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: max-content;
            margin: auto;
        }

        .row {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 5px 0;
            flex-wrap: wrap;
        }

        .row-label {
            width: 40px;
            font-weight: bold;
            text-align: center;
        }

        .seat {
            width: 40px;
            height: 40px;
            margin: 5px;
            /* background: lightgray; */
            border: 1px solid green;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
        }

        .seat:hover {
            background-color: green;
            color: white;
        }

        .seat.selected {
            background: green;
            color: white;
        }

        .seat.booked {
            background: lightgray;
            cursor: not-allowed;
            border: 0px;
        }

        .info {
            margin-top: 20px;
        }

        .btn {
            background: blue;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            text-decoration: none;
        }

        .btn:disabled {
            background: gray;
            cursor: not-allowed;
        }

        .sticky-pay-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            text-align: center;
            padding: 15px 10px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .sticky-pay-footer .btn {
            font-size: 16px;
            padding: 10px 30px;
            background: rgb(248, 68, 100);
            color: white;
            border: none;
            text-decoration: none;
            border-radius: 5px;
        }


        @media (max-width: 768px) {
            .seat {
                width: 30px;
                height: 30px;
                line-height: 30px;
                font-size: 14px;
            }

            .row-label {
                width: 30px;
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }
        }

        @media (max-width: 480px) {
            .seat {
                width: 25px;
                height: 25px;
                line-height: 25px;
                font-size: 12px;
            }

            .row-label {
                width: 25px;
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
                padding: 6px 12px;
            }
        }
    </style>
</head>

<body>

    <!-- From Uiverse.io by vinodjangid07 -->
    <button class="Btn" onclick="window.location.href='cinema.php?id=<?php echo $movie_id;?>'">

        <div class="sign"><svg viewBox="0 0 512 512" style="transform: scaleX(-1);">
                <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
            </svg>
        </div>

        <div class="text">Back</div>
    </button>




    <h2>Select Your Seat</h2>
    <div class="screen">SCREEN</div>
    <div class="seats-container-wrapper">
        <div class="seats-container" id="seatsContainer"></div>
    </div>
    <div class="info">
        <p>Selected Seats: <span id="selectedSeats">None</span></p>
    </div>

    <div class="sticky-pay-footer">
        <a id="payButton" class="btn" href="#" style="pointer-events: none;" onclick="proceedToPayment()">Proceed to Pay ₹<span id="totalPrice">0</span></a>
    </div>


    <script>
        const seatsContainer = document.getElementById("seatsContainer");
        const selectedSeatsDisplay = document.getElementById("selectedSeats");
        const totalPriceDisplay = document.getElementById("totalPrice");
        const payButton = document.getElementById("payButton");
        const seatPrice = 200;
        let selectedSeats = [];

        // Pass PHP booked seats to JS
        const bookedSeats = <?= json_encode($bookedSeats); ?>;

        function createSeats() {
            const rows = ["A", "B", "C", "D", "E", "F"];
            rows.forEach(row => {
                const rowDiv = document.createElement("div");
                rowDiv.classList.add("row");

                const rowLabel = document.createElement("div");
                rowLabel.classList.add("row-label");
                rowLabel.innerText = row;
                rowDiv.appendChild(rowLabel);

                for (let i = 1; i <= 20; i++) {
                    const seat = document.createElement("div");
                    seat.classList.add("seat");
                    seat.innerText = i;
                    seat.id = `${row}${i}`;

                    if (bookedSeats.includes(seat.id)) {
                        seat.classList.add("booked");
                    } else {
                        seat.addEventListener("click", () => toggleSeat(seat, row));
                    }

                    rowDiv.appendChild(seat);
                }
                seatsContainer.appendChild(rowDiv);
            });
        }

        function toggleSeat(seat, row) {
            if (seat.classList.contains("booked")) return;
            seat.classList.toggle("selected");
            const seatNumber = row + seat.innerText;
            if (seat.classList.contains("selected")) {
                selectedSeats.push(seatNumber);
            } else {
                selectedSeats = selectedSeats.filter(s => s !== seatNumber);
            }
            updateInfo();
        }

        function updateInfo() {
            selectedSeatsDisplay.innerText = selectedSeats.length > 0 ? selectedSeats.join(", ") : "None";
            const totalPrice = selectedSeats.length * seatPrice;
            totalPriceDisplay.innerText = totalPrice;
            payButton.href = selectedSeats.length > 0 ? `payment.php?amount=${totalPrice}` : "#";
            payButton.style.pointerEvents = selectedSeats.length > 0 ? "auto" : "none";
        }

        function proceedToPayment() {
            if (selectedSeats.length === 0) {
                alert("Please select at least one seat!");
                return;
            }

            const movieId = <?= json_encode($_SESSION['movie_id'] ?? ""); ?>;
            const cinemaId = <?= json_encode($_SESSION['cinema_id'] ?? ""); ?>;
            const date = <?= json_encode($_SESSION['date'] ?? ""); ?>;
            const time = <?= json_encode($_SESSION['time'] ?? ""); ?>;
            const selectedSeatsString = selectedSeats.join(",");

            fetch('payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `movie_id=${movieId}&cinema_id=${cinemaId}&time=${encodeURIComponent(time)}&date=${encodeURIComponent(date)}&selectedSeats=${selectedSeatsString}`
            }).then(() => {
                window.location.href = 'payment.php';
            });
        }

        createSeats();
    </script>
</body>

</html>