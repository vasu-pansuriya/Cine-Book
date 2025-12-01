<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Features - Cine Book</title>
  <link rel="stylesheet" href="CSS/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }
    .features-container {
      max-width: 1100px;
      margin: 50px auto;
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      font-size: 36px;
      color: #e50914;
      margin-bottom: 30px;
    }
    .feature-list {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }
    .feature-box {
      background: #fdfdfd;
      padding: 25px;
      border-radius: 10px;
      border: 1px solid #eee;
      transition: 0.3s;
    }
    .feature-box:hover {
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      transform: translateY(-5px);
    }
    .feature-box h2 {
      color: #e50914;
      font-size: 22px;
      margin-bottom: 10px;
    }
    .feature-box p {
      color: #333;
      font-size: 15px;
      line-height: 1.6;
    }
  </style>
</head>
<body>

  <?php include 'header.php'; ?>

  <div class="features-container">
    <h1>Our Features</h1>
    <div class="feature-list">
      <div class="feature-box">
        <h2>🎬 Easy Movie Search</h2>
        <p>Browse movies by name, language, or certification. Quickly find the films you want to watch with our smart search system.</p>
      </div>

      <div class="feature-box">
        <h2>🎟️ Real-Time Seat Booking</h2>
        <p>View live seat availability and select your favorite seats. Already booked seats are automatically disabled.</p>
      </div>

      <div class="feature-box">
        <h2>📅 Flexible Showtimes</h2>
        <p>Choose from multiple showtimes and dates, making it easier to watch movies that fit your schedule.</p>
      </div>

      <div class="feature-box">
        <h2>💳 Secure Payments</h2>
        <p>Pay safely with UPI and other supported payment methods. Transactions are secure and seamless.</p>
      </div>

      <div class="feature-box">
        <h2>📱 Fully Responsive</h2>
        <p>Cine Book works smoothly on desktop, tablet, and mobile devices, ensuring you can book tickets anytime, anywhere.</p>
      </div>

      <div class="feature-box">
        <h2>⭐ User-Friendly Experience</h2>
        <p>Enjoy a clean, interactive interface with easy navigation, personalized booking history, and instant confirmations.</p>
      </div>
    </div>
  </div>

</body>
</html>
