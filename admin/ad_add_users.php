<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ad_login.php');
    exit;
}

include '../db_connect.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = trim($_POST['name'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $password   = trim($_POST['password'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $contact_no = trim($_POST['contact_no'] ?? '');

    if ($name === '')      $errors[] = "Name is required.";
    if ($username === '')  $errors[] = "Username is required.";
    if ($password === '')  $errors[] = "Password is required.";
    if ($email === '')     $errors[] = "Email is required.";

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, username, password, email, contact_no, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param('sssss', $name, $username, $hashed_password, $email, $contact_no);
        if ($stmt->execute()) {
            echo "<script>alert('User added successfully!'); window.location.href='ad_users.php';</script>";
            exit;
        } else {
            $errors[] = "DB Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User | CineBook Admin</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- common user validation JS -->
    <script src="js/ad_user_validation.js" defer></script>

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
            max-width: 600px;
        }

        label {
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }

        input {
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

        button:hover {
            background: #2575fc;
        }

        .error-box {
            background: #fee;
            padding: 15px;
            border: 1px solid #f99;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .field-error {
            color: #e63946;
            font-size: 12px;
            min-height: 14px;
            margin-bottom: 6px;
            display: block;
        }

        .input-error {
            border-color: #e63946;
        }

        .input-valid {
            border-color: #2ecc71;
        }

        .footer {
            background: #1d1b31;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 16px;
            margin-top: auto;
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
            <h2 class="dashboard-title">Add New User</h2>

            <?php
            if (!empty($errors)) {
                echo '<div class="error-box">';
                foreach ($errors as $e) echo "<div>" . htmlspecialchars($e) . "</div>";
                echo '</div>';
            }
            ?>

            <div class="card">
                <form method="post" id="addUserForm">
                    <label>Name</label>
                    <input type="text" name="name" id="name">
                    <span class="field-error" id="name_error"></span>

                    <label>Username</label>
                    <input type="text" name="username" id="username">
                    <span class="field-error" id="username_error"></span>

                    <label>Password</label>
                    <input type="password" name="password" id="password">
                    <span class="field-error" id="password_error"></span>

                    <label>Email</label>
                    <input type="email" name="email" id="email">
                    <span class="field-error" id="email_error"></span>

                    <label>Contact No</label>
                    <input type="text" name="contact_no" id="contact_no">
                    <span class="field-error" id="contact_no_error"></span>

                    <button type="submit">Add User</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">© 2025 CineBook | Admin Panel</div>

</body>

</html>