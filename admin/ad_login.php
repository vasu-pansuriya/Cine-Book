<?php
session_start();
include '../db_connect.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $passwordPlain = $_POST['password'];
    $password = md5($passwordPlain); // assumes admin table stores MD5 hash

    $sql = "SELECT id, username FROM admin WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password!";
        }

        $stmt->close();
    } else {
        $error = "Database error. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <meta charset="UTF-8">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #e3f2fd;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            width: 320px;
            /* smaller width */
            padding: 16px 18px;
            /* tighter padding */
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        }

        h2 {
            text-align: center;
            margin-bottom: 12px;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #444;
        }

        .form-group input {
            width: 100%;
            padding: 7px 8px;
            margin-top: 3px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
        }

        .form-group input.error {
            border-color: #e63946;
        }

        .form-group input.valid {
            border-color: #2ecc71;
        }

        .msg {
            font-size: 11px;
            min-height: 12px;
            margin-top: 2px;
        }

        .error-message {
            color: #e63946;
        }

        .success-message {
            color: #2ecc71;
        }

        .global-error {
            color: #e63946;
            text-align: center;
            font-size: 13px;
            margin-bottom: 6px;
            min-height: 14px;
        }

        button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            background: #0057ff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #0041c4;
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Admin Login</h2>

        <div class="global-error">
            <?php if (!empty($error)) echo htmlspecialchars($error); ?>
        </div>

        <form method="post" id="adminLoginForm" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
                <div id="usernameMsg" class="msg"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                <div id="passwordMsg" class="msg"></div>
            </div>

            <button type="submit" name="login" id="loginBtn" disabled>Login</button>
        </form>
    </div>

    <script>
        (function() {
            const form = document.getElementById('adminLoginForm');
            const usernameInp = document.getElementById('username');
            const passwordInp = document.getElementById('password');
            const usernameMsg = document.getElementById('usernameMsg');
            const passwordMsg = document.getElementById('passwordMsg');
            const loginBtn = document.getElementById('loginBtn');

            function setError(input, msgElem, message) {
                input.classList.add('error');
                input.classList.remove('valid');
                msgElem.textContent = message;
                msgElem.classList.remove('success-message');
                msgElem.classList.add('error-message');
                return false;
            }

            function setSuccess(input, msgElem, message) {
                input.classList.remove('error');
                input.classList.add('valid');
                msgElem.textContent = message;
                msgElem.classList.remove('error-message');
                msgElem.classList.add('success-message');
                return true;
            }

            function validateUsername() {
                const val = usernameInp.value.trim();

                if (val === '') {
                    return setError(usernameInp, usernameMsg, 'Username is required.');
                }
                if (val.length < 3) {
                    return setError(usernameInp, usernameMsg, 'At least 3 characters required.');
                }
                if (val.length > 20) {
                    return setError(usernameInp, usernameMsg, 'Maximum 20 characters allowed.');
                }
                if (!/^[a-zA-Z0-9_]+$/.test(val)) {
                    return setError(usernameInp, usernameMsg, 'Only letters, numbers and underscore allowed.');
                }
                return setSuccess(usernameInp, usernameMsg, 'Looks good.');
            }

            function validatePassword() {
                const val = passwordInp.value;

                if (val === '') {
                    return setError(passwordInp, passwordMsg, 'Password is required.');
                }
                if (val.length < 6) {
                    return setError(passwordInp, passwordMsg, 'At least 6 characters required.');
                }
                return setSuccess(passwordInp, passwordMsg, 'Password format looks ok.');
            }

            function updateButtonState() {
                const u = validateUsername();
                const p = validatePassword();
                loginBtn.disabled = !(u && p);
            }

            usernameInp.addEventListener('input', updateButtonState);
            passwordInp.addEventListener('input', updateButtonState);

            usernameInp.addEventListener('blur', validateUsername);
            passwordInp.addEventListener('blur', validatePassword);

            form.addEventListener('submit', function(e) {
                updateButtonState();
                if (loginBtn.disabled) {
                    e.preventDefault();
                }
            });
        })();
    </script>
</body>

</html>