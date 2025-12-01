<?php

include 'db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Secure password hashing
    $contact_no = $_POST["contact_no"];

    // Insert data into database
    $sql = "INSERT INTO users (name, username, password, email, contact_no) 
            VALUES ('$name', '$username', '$password', '$email', '$contact_no')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cine Book</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS file -->
    <style>
        /* Global styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #ff6e7f, #bfe9ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #ff6e7f;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ff4757;
        }

        p {
            margin-top: 10px;
            font-size: 14px;
        }

        a {
            color: #ff6e7f;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive styles */
        @media (max-width: 480px) {
            .container {
                width: 90%;
            }
        }
    </style>
    
</head>
<body>
    <div class="container">
        <h2>Create an Account</h2>

        <script>
            function validate_data(){
                var fullname = document.getElementById('name').value;
                var fname_error = document.getElementById('fname_error')

                if(fullname.length == ""){
                    fname_error.innerHTML = "Full name Cannnot be empty";
                    return false;
                }
                else{
                    if (fullname.length < 3) {
                        fname_error.innerHTML = "Full name must be greater than 25 character.";
                        return false;
                    } 
                    fname_regex = /^[a-zA-Z](3,25)$/;
    if(!fname_regex.test(fullname)){
                        fname_error.innerHTML = "Full name must have character.";
                    }
                    else {
                        fname_error.innerHTML = "";
                        return true;    
                    }
                }
                
            }
        </script>
        <form action="" method="POST" onsubmit="return validate_data()">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label for="contact_no">Contact No</label>
                <input type="tel" name="contact_no" id="contact_no" required>
            </div>

            <button type="submit">Register</button>

            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
