<?php
session_start();
include '../db_connect.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = MD5($_POST['password']);

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body{font-family: Arial;background: #e3f2fd;}
        .box{width: 350px;margin: 100px auto;background:white;padding: 20px;box-shadow: 0px 0px 10px rgba(0,0,0,.2);}
        input{width: 100%;padding: 10px;margin: 8px 0;}
        button{width: 100%;padding: 10px;background:#0057ff;color:white;border: none;}
        h2{text-align:center;}
        p{color:red;text-align:center;}
    </style>
</head>
<body>
<div class="box">
    <h2>Admin Login</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <?php if(isset($error)) echo "<p>$error</p>"; ?>
        <button type="submit" name="login">Login</button>
    </form>
</div>
</body>
</html>
