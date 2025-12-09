<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: ad_login.php');
  exit;
}

include '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Cinema | CineBook Admin</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: #f5f6fc;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .header {
      background: linear-gradient(90deg, #6a11cb, #2575fc);
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
    }

    .sidebar a:hover {
      background: #322f54;
    }

    .icon {
      font-size: 22px;
    }

    .content {
      flex: 1;
      padding: 30px;
    }

    .dashboard-title {
      font-size: 28px;
      margin-bottom: 20px;
      font-weight: bold;
      color: #333;
    }

    .card {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      max-width: 800px;
    }

    input,
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
      outline: none;
      transition: 0.2s;
    }

    button {
      background: #6a11cb;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 6px;
    }

    .error-box {
      background: #fee;
      padding: 15px;
      border: 1px solid #f99;
      border-radius: 8px;
      margin-bottom: 20px;
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

    @media (max-width:768px) {
      .sidebar {
        display: none;
      }

      .content {
        padding: 20px;
      }
    }
  </style>

  <!-- same common validation -->
  <script src="js/ad_cinemas_validation.js" defer></script>

</head>

<body>

  <div class="header">
    <div>📽️ CineBook Admin Panel</div>
  </div>

  <div class="layout">

    <div class="sidebar">
      <a href="dashboard.php"><span class="icon material-icons">dashboard</span>Dashboard</a>
      <a href="ad_movies.php"><span class="icon material-icons">movie</span>Movies</a>
      <a href="ad_cinemas.php"><span class="icon material-icons">theaters</span>Cinemas</a>
      <a href="ad_users.php"><span class="icon material-icons">people</span>Users</a>
      <a href="logout.php" style="color:#ff4d4d;"><span class="icon material-icons">logout</span>Logout</a>
    </div>

    <div class="content">
      <h2 class="dashboard-title">Edit Cinema</h2>

      <?php
      $id = intval($_GET['id'] ?? 0);
      if ($id <= 0) {
        echo "<p>Missing cinema ID</p>";
        exit;
      }

      $stmt = $conn->prepare("SELECT * FROM cinemas WHERE id=?");
      $stmt->bind_param('i', $id);
      $stmt->execute();
      $res = $stmt->get_result();
      $cinema = $res->fetch_assoc();
      $stmt->close();

      if (!$cinema) {
        echo "<p>Cinema not found.</p>";
        exit;
      }

      $errors = [];
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cinema_name = trim($_POST['name'] ?? '');
        $features    = trim($_POST['features'] ?? '');
        $show_times  = trim($_POST['show_times'] ?? '');
        $cancelation = trim($_POST['cancelation'] ?? '');

        if ($cinema_name === '') $errors[] = "Cinema name is required.";

        if (empty($errors)) {
          $stmt = $conn->prepare("UPDATE cinemas SET name=?, features=?, show_times=?, cancelation=? WHERE id=?");
          $stmt->bind_param('ssssi', $cinema_name, $features, $show_times, $cancelation, $id);
          if ($stmt->execute()) {
            echo "<script>alert('Cinema updated successfully!'); window.location.href='ad_cinemas.php';</script>";
            exit;
          } else {
            $errors[] = "DB error: " . $conn->error;
          }
          $stmt->close();
        }
      }

      if (!empty($errors)) {
        echo '<div class="error-box">';
        foreach ($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>";
        echo '</div>';
      }
      ?>

      <div class="card">
        <form method="post" id="editCinemaForm">
          <label>Cinema Name</label>
          <input type="text" name="name" id="cinema_name" value="<?php echo htmlspecialchars($cinema['name']); ?>">
          <span class="field-error" id="cinema_name_error"></span>

          <label>Features</label>
          <textarea name="features" id="features" rows="4"><?php echo htmlspecialchars($cinema['features']); ?></textarea>
          <span class="field-error" id="features_error"></span>

          <label>Show Times</label>
          <input type="text" name="show_times" id="show_times" value="<?php echo htmlspecialchars($cinema['show_times']); ?>">
          <span class="field-error" id="show_times_error"></span>

          <label>Cancellation Policy</label>
          <input type="text" name="cancelation" id="cancelation" value="<?php echo htmlspecialchars($cinema['cancelation']); ?>">
          <span class="field-error" id="cancelation_error"></span>

          <button type="submit">Update Cinema</button>
        </form>
      </div>
    </div>
  </div>

  <div class="footer">© 2025 CineBook | Admin Panel</div>

</body>

</html>