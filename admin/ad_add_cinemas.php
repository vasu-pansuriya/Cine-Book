<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: ad_login.php');
  exit;
}

include '../db_connect.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cinema_name = trim($_POST['name']);
  $features    = trim($_POST['features']);
  $show_times  = trim($_POST['show_times']);
  $cancelation = trim($_POST['cancelation']);

  if ($cinema_name === '') $errors[] = "Cinema name is required.";

  if (empty($errors)) {
    $sql = "INSERT INTO cinemas (name, features, show_times, cancelation) 
            VALUES ('$cinema_name', '$features', '$show_times', '$cancelation')";
    if ($conn->query($sql) === TRUE) {
      echo "<script>alert('Cinema Added Successfully!'); window.location.href='ad_add_cinemas.php';</script>";
      exit;
    } else {
      $errors[] = "DB Error: " . $conn->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Cinema - CineBook Admin</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial;
      background: #f5f6fc;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .header {
      background: linear-gradient(90deg, #8e2de2, #4a00e0);
      color: white;
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 22px;
      font-weight: bold;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .layout {
      display: flex;
      flex: 1;
      min-height: calc(100vh - 60px);
    }

    .sidebar {
      width: 240px;
      background: #1d1b31;
      padding-top: 20px;
      color: white;
      flex-shrink: 0;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 20px;
      font-size: 17px;
      color: white;
      text-decoration: none;
      transition: 0.25s;
    }

    .sidebar a:hover {
      background: #2e2b49;
      padding-left: 25px;
    }

    .icon {
      font-size: 22px;
      min-width: 30px;
      text-align: center;
    }

    .content {
      flex: 1;
      padding: 30px;
    }

    .page-title {
      font-size: 28px;
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
    }

    .card {
      background: white;
      border-radius: 14px;
      padding: 25px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
      max-width: 900px;
      margin-top: 20px;
    }

    .card label {
      font-weight: bold;
      display: block;
      margin-bottom: 6px;
    }

    .card input,
    .card textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 15px;
      outline: none;
      transition: 0.2s;
    }

    .card button {
      padding: 12px 20px;
      background: #6a11cb;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 6px;
    }

    .card button:hover {
      background: #2575fc;
    }

    .errors {
      background: #fee;
      border: 1px solid #f99;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 15px;
    }

    .footer {
      background: #1d1b31;
      color: white;
      text-align: center;
      padding: 15px 0;
      font-size: 16px;
      margin-top: auto;
    }

    .field-error {
      color: #e63946;
      font-size: 12px;
      min-height: 14px;
      display: block;
      margin-bottom: 6px;
    }

    .input-error {
      border-color: #e63946;
    }

    .input-valid {
      border-color: #2ecc71;
    }

    @media (max-width:900px) {
      .sidebar {
        width: 80px;
      }

      .sidebar a span {
        display: none;
      }

      .sidebar a {
        justify-content: center;
      }
    }

    @media (max-width:768px) {
      .content {
        padding: 20px;
      }
    }
  </style>

  <!-- Common validation file -->
  <script src="js/ad_cinemas_validation.js" defer></script>

</head>

<body>

  <div class="header">
    <div>📽️ CineBook Admin Panel</div>
  </div>

  <div class="layout">

    <div class="sidebar">
      <a href="dashboard.php"><span class="icon material-icons">dashboard</span><span>Dashboard</span></a>
      <a href="ad_movies.php"><span class="icon material-icons">movie</span><span>Movies</span></a>
      <a href="ad_cinemas.php"><span class="icon material-icons">theaters</span><span>Cinemas</span></a>
      <a href="ad_users.php"><span class="icon material-icons">people</span><span>Users</span></a>
      <a href="logout.php" style="color:#ff4d4d;"><span class="icon material-icons">logout</span><span>Logout</span></a>
    </div>

    <div class="content">
      <h2 class="page-title">Add New Cinema</h2>

      <?php if (!empty($errors)): ?>
        <div class="errors">
          <?php foreach ($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>"; ?>
        </div>
      <?php endif; ?>

      <div class="card">
        <form method="post" id="addCinemaForm">
          <label>Cinema Name</label>
          <input type="text" name="name" id="cinema_name">
          <span class="field-error" id="cinema_name_error"></span>

          <label>Features</label>
          <textarea name="features" id="features" rows="4"></textarea>
          <span class="field-error" id="features_error"></span>

          <label>Show Times</label>
          <input type="text" name="show_times" id="show_times">
          <span class="field-error" id="show_times_error"></span>

          <label>Cancellation Policy</label>
          <input type="text" name="cancelation" id="cancelation">
          <span class="field-error" id="cancelation_error"></span>

          <button type="submit">Add Cinema</button>
        </form>
      </div>
    </div>
  </div>

  <div class="footer">
    © 2025 CineBook | Admin Panel
  </div>

</body>

</html>