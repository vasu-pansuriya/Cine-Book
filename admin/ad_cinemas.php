<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


include '../db_connect.php';

// Fetch cinemas
$cinemas = [];
$res = $conn->query("SELECT id, name, features, show_times, cancelation FROM cinemas ORDER BY id DESC");
if ($res) {
  while ($row = $res->fetch_assoc()) $cinemas[] = $row;
  $res->free();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Cinemas - CineBook Admin</title>

  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

  <script src="validation.js"></script>

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

    /* HEADER */
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

    .logout-btn {
      color: white;
      font-size: 18px;
      text-decoration: none;
      font-weight: bold;
    }

    /* LAYOUT */
    .layout {
      display: flex;
      flex: 1;
      min-height: calc(100vh - 60px);
    }

    /* SIDEBAR */
    .sidebar {
      width: 240px;
      background: #1d1b31;
      padding-top: 20px;
      color: white;
      flex-shrink: 0;
      transition: width 0.3s ease-in-out;
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

    /* CONTENT */
    .content {
      flex: 1;
      padding: 30px;
      transition: margin 0.3s;
    }

    .page-title {
      font-size: 28px;
      font-weight: bold;
      color: #333;
      margin-bottom: 20px;
    }

    .add-btn {
      display: inline-block;
      background: #6a11cb;
      color: white;
      padding: 10px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 16px;
      font-weight: bold;
      transition: 0.3s;
    }

    .add-btn:hover {
      background: #2575fc;
    }

    /* CARDS */
    .card-container {
      display: flex;
      flex-wrap: wrap;
      gap: 25px;
      margin-top: 20px;
    }

    .cinema-card {
      flex: 1 1 280px;
      background: white;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
      transition: .3s;
      display: flex;
      flex-direction: column;
      padding: 18px;
    }

    .cinema-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .cinema-title {
      font-size: 20px;
      font-weight: bold;
      color: #6a11cb;
      margin-bottom: 8px;
    }

    .cinema-info {
      color: #666;
      font-size: 15px;
      margin-bottom: 5px;
    }

    .card-actions {
      margin-top: auto;
    }

    .edit-btn,
    .delete-btn {
      padding: 7px 12px;
      border-radius: 6px;
      text-decoration: none;
      color: white;
      font-size: 14px;
      margin-right: 6px;
    }

    .edit-btn {
      background: #2575fc;
    }

    .edit-btn:hover {
      background: #0b5ed7;
    }

    .delete-btn {
      background: #e63946;
    }

    .delete-btn:hover {
      background: #c71c29;
    }

    /* FOOTER */
    .footer {
      background: #1d1b31;
      color: white;
      text-align: center;
      padding: 15px 0;
      font-size: 16px;
      margin-top: auto;
    }

    /* RESPONSIVE */
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
</head>

<body>

  <!-- HEADER -->
  <div class="header">
    <div>📽️ CineBook Admin Panel</div>

  </div>

  <!-- LAYOUT -->
  <div class="layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
      <a href="dashboard.php"><span class="icon material-icons">dashboard</span><span>Dashboard</span></a>
      <a href="ad_movies.php"><span class="icon material-icons">movie</span><span>Movies</span></a>
      <a href="ad_cinemas.php"><span class="icon material-icons">theaters</span><span>Cinemas</span></a>
      <a href="ad_users.php"><span class="icon material-icons">people</span><span>Users</span></a>
      <a href="logout.php" style="color:#ff4d4d;"><span class="icon material-icons">logout</span><span>Logout</span></a>
    </div>

    <!-- CONTENT -->
    <div class="content">
      <h2 class="page-title">Manage Cinemas</h2>
      <a href="ad_add_cinemas.php" class="add-btn">+ Add New Cinema</a>

      <div class="card-container">
        <?php if (empty($cinemas)): ?>
          <p>No cinemas found.</p>
          <?php else: foreach ($cinemas as $c): ?>
            <div class="cinema-card">
              <div class="cinema-title"><?php echo htmlspecialchars($c['name']); ?></div>
              <div class="cinema-info">Features: <?php echo htmlspecialchars($c['features']); ?></div>
              <div class="cinema-info">Show Times: <?php echo htmlspecialchars($c['show_times']); ?></div>
              <div class="cinema-info">Cancellation: <?php echo htmlspecialchars($c['cancelation']); ?></div>
              <div class="card-actions">
                <a class="edit-btn" href="ad_edit_cinemas.php?id=<?php echo $c['id']; ?>">Edit</a>
                <a class="delete-btn" href="ad_delete_cinemas.php?id=<?php echo $c['id']; ?>" onclick="return confirm('Delete this cinema?')">Delete</a>
              </div>
            </div>
        <?php endforeach;
        endif; ?>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    © 2025 CineBook | Admin Panel
  </div>

</body>

</html>