<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
if (!isset($_SESSION['admin'])) {
  header('Location: ad_login.php');
  exit;
}

include '../db_connect.php';

// Fetch users
$users = [];
$res = $conn->query("SELECT id, name, username, password, email, contact_no, created_at FROM users ORDER BY id DESC");
if ($res) {
  while ($row = $res->fetch_assoc()) $users[] = $row;
  $res->free();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users - CineBook Admin</title>

  <!-- Google Icons -->
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

    /* CARD + TABLE */
    .card {
      background: white;
      border-radius: 14px;
      padding: 20px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
      overflow-x: auto;
    }

    .card table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }

    .card table th,
    .card table td {
      padding: 12px 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .card table th {
      background: #6a11cb;
      color: white;
      font-weight: 500;
    }

    .card table tr:hover {
      background: #f0f0f0;
    }

    .action-btn {
      padding: 6px 12px;
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
      <h2 class="page-title">Manage Users</h2>
      <a href="ad_add_users.php" class="add-btn">+ Add New User</a>

      <div class="card" style="margin-top:20px;">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Username</th>
              <!-- <th>Password</th> -->
              <th>Email</th>
              <th>Contact No</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($users)): ?>
              <tr>
                <td colspan="8">No users found.</td>
              </tr>
              <?php else: foreach ($users as $u): ?>
                <tr>
                  <td><?php echo htmlspecialchars($u['id']); ?></td>
                  <td><?php echo htmlspecialchars($u['name']); ?></td>
                  <td><?php echo htmlspecialchars($u['username']); ?></td>
                  <!-- <td><?php echo htmlspecialchars($u['password']); ?></td> -->
                  <td><?php echo htmlspecialchars($u['email']); ?></td>
                  <td><?php echo htmlspecialchars($u['contact_no']); ?></td>
                  <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                  <td>
                    <a class="action-btn edit-btn" href="ad_edit_users.php?id=<?php echo $u['id']; ?>">Edit</a>
                    <a class="action-btn delete-btn" href="ad_delete_users.php?id=<?php echo $u['id']; ?>" onclick="return confirm('Delete this user?')">Delete</a>
                  </td>
                </tr>
            <?php endforeach;
            endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    © 2025 CineBook | Admin Panel
  </div>

</body>

</html>