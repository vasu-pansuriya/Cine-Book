<?php
include 'db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name       = $_POST["name"] ?? '';
    $username   = $_POST["username"] ?? '';
    $email      = $_POST["email"] ?? '';
    $password   = password_hash($_POST["password"] ?? '', PASSWORD_DEFAULT);
    $contact_no = $_POST["contact_no"] ?? '';

    $sql  = "INSERT INTO users (name, username, password, email, contact_no)
             VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssss", $name, $username, $password, $email, $contact_no);

        if ($stmt->execute()) {
            echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error: Username or Email might already exist.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Database error.');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cine Book</title>

    <style>
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
            max-width: 340px;
            /* Smaller width */
            background: #fff;
            padding: 18px 20px;
            /* Reduced padding */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        h2 {
            margin-bottom: 12px;
            /* Less space */
            font-size: 20px;
            /* Slightly smaller */
        }

        .form-group {
            margin-bottom: 10px;
            /* Less gap between fields */
            text-align: left;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #444;
        }

        input {
            width: 100%;
            padding: 7px 8px;
            /* Smaller input fields */
            margin-top: 3px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input.error {
            border-color: #e63946;
        }

        input.valid {
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

        button {
            width: 100%;
            padding: 8px;
            /* Smaller button */
            margin-top: 8px;
            background: #ff6e7f;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            /* Slightly smaller */
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ff4757;
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        p {
            margin-top: 10px;
            font-size: 12px;
            /* Smaller text */
        }

        a {
            color: #ff6e7f;
            font-size: 12px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Create an Account</h2>

        <form id="registerForm" method="POST" novalidate>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name">
                <div id="nameError" class="msg"></div>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
                <div id="usernameError" class="msg"></div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
                <div id="emailError" class="msg"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
                <div id="passwordError" class="msg"></div>
            </div>

            <div class="form-group">
                <label for="contact_no">Contact No</label>
                <input type="tel" name="contact_no" id="contact_no">
                <div id="contactError" class="msg"></div>
            </div>

            <button type="submit" id="registerBtn" disabled>Register</button>

            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("registerForm");
            const btn = document.getElementById("registerBtn");

            const nameInput = document.getElementById("name");
            const userInput = document.getElementById("username");
            const emailInput = document.getElementById("email");
            const passInput = document.getElementById("password");
            const phoneInput = document.getElementById("contact_no");

            const nameError = document.getElementById("nameError");
            const userError = document.getElementById("usernameError");
            const emailError = document.getElementById("emailError");
            const passError = document.getElementById("passwordError");
            const phoneError = document.getElementById("contactError");

            function setError(input, msgElem, message) {
                input.classList.add("error");
                input.classList.remove("valid");
                msgElem.textContent = message;
                msgElem.classList.remove("success-message");
                msgElem.classList.add("error-message");
                return false;
            }

            function setSuccess(input, msgElem, message) {
                input.classList.remove("error");
                input.classList.add("valid");
                msgElem.textContent = message;
                msgElem.classList.remove("error-message");
                msgElem.classList.add("success-message");
                return true;
            }

            function validateName() {
                const val = nameInput.value.trim();
                if (val === "") {
                    return setError(nameInput, nameError, "Full name is required.");
                }
                if (val.length < 3) {
                    return setError(nameInput, nameError, "At least 3 characters required.");
                }
                if (!/^[a-zA-Z\s]+$/.test(val)) {
                    return setError(nameInput, nameError, "Only letters and spaces allowed.");
                }
                return setSuccess(nameInput, nameError, "Looks good!");
            }

            function validateUsername() {
                const val = userInput.value.trim();
                if (val === "") {
                    return setError(userInput, userError, "Username is required.");
                }
                if (val.length < 3) {
                    return setError(userInput, userError, "At least 3 characters required.");
                }
                if (val.length > 20) {
                    return setError(userInput, userError, "Maximum 20 characters allowed.");
                }
                if (!/^[a-zA-Z0-9_]+$/.test(val)) {
                    return setError(userInput, userError, "Only letters, numbers and underscore allowed.");
                }
                return setSuccess(userInput, userError, "Looks good!");
            }

            function validateEmail() {
                const val = emailInput.value.trim();
                if (val === "") {
                    return setError(emailInput, emailError, "Email is required.");
                }
                const emailRegex = /^\S+@\S+\.\S+$/;
                if (!emailRegex.test(val)) {
                    return setError(emailInput, emailError, "Enter a valid email.");
                }
                return setSuccess(emailInput, emailError, "Valid email!");
            }

            function validatePassword() {
                const val = passInput.value;
                if (val === "") {
                    return setError(passInput, passError, "Password is required.");
                }
                if (val.length < 8) {
                    return setError(passInput, passError, "At least 8 characters required.");
                }
                if (/\s/.test(val)) {
                    return setError(passInput, passError, "Spaces are not allowed.");
                }
                return setSuccess(passInput, passError, "Strong password!");
            }

            function validateContact() {
                const val = phoneInput.value.trim();
                if (val === "") {
                    return setError(phoneInput, phoneError, "Contact number is required.");
                }
                if (!/^[0-9]{10}$/.test(val)) {
                    return setError(phoneInput, phoneError, "Enter a valid 10 digit number.");
                }
                return setSuccess(phoneInput, phoneError, "Valid contact number!");
            }

            function updateButtonState() {
                const v1 = validateName();
                const v2 = validateUsername();
                const v3 = validateEmail();
                const v4 = validatePassword();
                const v5 = validateContact();
                btn.disabled = !(v1 && v2 && v3 && v4 && v5);
            }

            nameInput.addEventListener("input", updateButtonState);
            userInput.addEventListener("input", updateButtonState);
            emailInput.addEventListener("input", updateButtonState);
            passInput.addEventListener("input", updateButtonState);
            phoneInput.addEventListener("input", updateButtonState);

            nameInput.addEventListener("blur", validateName);
            userInput.addEventListener("blur", validateUsername);
            emailInput.addEventListener("blur", validateEmail);
            passInput.addEventListener("blur", validatePassword);
            phoneInput.addEventListener("blur", validateContact);

            form.addEventListener("submit", function(e) {
                updateButtonState();
                if (btn.disabled) {
                    e.preventDefault();
                }
            });
        });
    </script>

</body>

</html>