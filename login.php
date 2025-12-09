<?php
session_start();
include 'db_connect.php';

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    // ---- Admin login (hardcoded) ----
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION["user_id"] = 0;       // admin id placeholder
        $_SESSION["username"] = 'admin';
        echo "<script>alert('Admin Login Successful!'); window.location.href='admin/dashboard.php';</script>";
        exit;
    }

    // ---- Normal user login from database ----
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Verify password (hashed in DB)
            if (password_verify($password, $row["password"])) {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                echo "<script>alert('Login Successful!'); window.location.href='index.php';</script>";
                exit;
            } else {
                echo "<script>alert('Incorrect Password!');</script>";
            }
        } else {
            echo "<script>alert('User not found!');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Database error. Please try again later.');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cine Book</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional external CSS -->

    <style>
        /* Global styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
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
            outline: none;
            transition: border-color 0.2s ease;
        }

        input.error {
            border-color: #e63946;
            /* red */
        }

        input.valid {
            border-color: #2ecc71;
            /* green */
        }

        .error-message {
            margin-top: 4px;
            font-size: 12px;
            min-height: 14px;
            color: #e63946;
            /* red text */
        }

        .success-message {
            margin-top: 4px;
            font-size: 12px;
            min-height: 14px;
            color: #2ecc71 !important;
            /* green text */
        }

        button {
            width: 100%;
            padding: 10px;
            background: #6a11cb;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #2575fc;
        }

        button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        p {
            margin-top: 10px;
            font-size: 14px;
        }

        a {
            color: #6a11cb;
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
        <h2>User Login</h2>
        <form id="loginForm" action="" method="POST" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
                <div id="usernameError" class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                <div id="passwordError" class="error-message"></div>
            </div>

            <button type="submit" id="loginBtn" disabled>Login</button>

            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>
    </div>

    <script>
        (function() {
            const form = document.getElementById('loginForm');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const usernameError = document.getElementById('usernameError');
            const passwordError = document.getElementById('passwordError');
            const loginBtn = document.getElementById('loginBtn');

            // Username: 3–20 chars, letters/numbers/underscore only
            function validateUsername() {
                const value = usernameInput.value.trim();
                let message = '';

                if (!value) {
                    message = 'Username is required.';
                } else if (value.length < 3) {
                    message = 'Username must be at least 3 characters.';
                } else if (value.length > 20) {
                    message = 'Username must be 20 characters or less.';
                } else if (!/^[a-zA-Z0-9_]+$/.test(value)) {
                    message = 'Only letters, numbers and underscore are allowed.';
                }

                if (message === '') {
                    usernameInput.classList.remove('error');
                    usernameInput.classList.add('valid');
                    usernameError.textContent = 'Looks good!';
                    usernameError.classList.remove('error-message');
                    usernameError.classList.add('success-message');
                    return true;
                } else {
                    usernameInput.classList.add('error');
                    usernameInput.classList.remove('valid');
                    usernameError.textContent = message;
                    usernameError.classList.add('error-message');
                    usernameError.classList.remove('success-message');
                    return false;
                }
            }

            // Password: at least 8 chars, no spaces
            function validatePassword() {
                const value = passwordInput.value;
                let message = '';

                if (!value) {
                    message = 'Password is required.';
                } else if (value.length < 8) {
                    message = 'Password must be at least 8 characters.';
                } else if (/\s/.test(value)) {
                    message = 'Password must not contain spaces.';
                }

                if (message === '') {
                    passwordInput.classList.remove('error');
                    passwordInput.classList.add('valid');
                    passwordError.textContent = 'Strong password!';
                    passwordError.classList.remove('error-message');
                    passwordError.classList.add('success-message');
                    return true;
                } else {
                    passwordInput.classList.add('error');
                    passwordInput.classList.remove('valid');
                    passwordError.textContent = message;
                    passwordError.classList.add('error-message');
                    passwordError.classList.remove('success-message');
                    return false;
                }
            }

            function updateButtonState() {
                const validUser = validateUsername();
                const validPass = validatePassword();
                loginBtn.disabled = !(validUser && validPass);
            }

            // Live validation on input
            usernameInput.addEventListener('input', updateButtonState);
            passwordInput.addEventListener('input', updateButtonState);

            // On blur, also validate
            usernameInput.addEventListener('blur', validateUsername);
            passwordInput.addEventListener('blur', validatePassword);

            // On submit, prevent if invalid
            form.addEventListener('submit', function(e) {
                const validUser = validateUsername();
                const validPass = validatePassword();

                if (!validUser || !validPass) {
                    e.preventDefault();
                    updateButtonState();
                }
            });

            // Initial state
            updateButtonState();
        })();
    </script>
</body>

</html>