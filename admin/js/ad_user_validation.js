document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addUserForm") || document.getElementById("editUserForm");
    if (!form) return;

    const nameInput     = document.getElementById("name");
    const usernameInput = document.getElementById("username");
    const passwordInput = document.getElementById("password");
    const emailInput    = document.getElementById("email");
    const contactInput  = document.getElementById("contact_no");

    const nameError     = document.getElementById("name_error");
    const userError     = document.getElementById("username_error");
    const passError     = document.getElementById("password_error");
    const emailError    = document.getElementById("email_error");
    const contactError  = document.getElementById("contact_no_error");

    function setError(input, errElem, msg) {
        if (!input || !errElem) return false;
        input.classList.add("input-error");
        input.classList.remove("input-valid");
        errElem.textContent = msg;
        return false;
    }

    function setSuccess(input, errElem) {
        if (!input || !errElem) return true;
        input.classList.remove("input-error");
        input.classList.add("input-valid");
        errElem.textContent = "";
        return true;
    }

    function validateName() {
        const v = nameInput.value.trim();
        if (v === "") return setError(nameInput, nameError, "Name is required.");
        if (v.length < 3) return setError(nameInput, nameError, "Minimum 3 characters.");
        return setSuccess(nameInput, nameError);
    }

    function validateUsername() {
        const v = usernameInput.value.trim();
        if (v === "") return setError(usernameInput, userError, "Username is required.");
        if (v.length < 3) return setError(usernameInput, userError, "Minimum 3 characters.");
        if (!/^[a-zA-Z0-9_]+$/.test(v)) return setError(usernameInput, userError, "Only letters, numbers & underscore.");
        return setSuccess(usernameInput, userError);
    }

    function validatePassword() {
        const v = passwordInput.value.trim();

        // For addUserForm password is required
        if (form.id === "addUserForm") {
            if (v === "") return setError(passwordInput, passError, "Password is required.");
        }

        // For editUserForm: blank = keep old password
        if (v === "") {
            passwordInput.classList.remove("input-error", "input-valid");
            passError.textContent = "";
            return true;
        }

        if (v.length < 8) return setError(passwordInput, passError, "Minimum 8 characters.");
        if (/\s/.test(v)) return setError(passwordInput, passError, "No spaces allowed.");
        return setSuccess(passwordInput, passError);
    }

    function validateEmail() {
        const v = emailInput.value.trim();
        if (v === "") return setError(emailInput, emailError, "Email is required.");
        const regex = /^\S+@\S+\.\S+$/;
        if (!regex.test(v)) return setError(emailInput, emailError, "Enter valid email.");
        return setSuccess(emailInput, emailError);
    }

    function validateContact() {
        const v = contactInput.value.trim();
        if (v === "") {
            contactInput.classList.remove("input-error", "input-valid");
            contactError.textContent = "";
            return true; // optional
        }
        if (!/^[0-9]{10}$/.test(v)) {
            return setError(contactInput, contactError, "Enter 10 digit number.");
        }
        return setSuccess(contactInput, contactError);
    }

    function validateAll() {
        const v1 = validateName();
        const v2 = validateUsername();
        const v3 = validatePassword();
        const v4 = validateEmail();
        const v5 = validateContact();
        return v1 && v2 && v3 && v4 && v5;
    }

    nameInput.addEventListener("input", validateName);
    usernameInput.addEventListener("input", validateUsername);
    passwordInput.addEventListener("input", validatePassword);
    emailInput.addEventListener("input", validateEmail);
    contactInput.addEventListener("input", validateContact);

    nameInput.addEventListener("blur", validateName);
    usernameInput.addEventListener("blur", validateUsername);
    passwordInput.addEventListener("blur", validatePassword);
    emailInput.addEventListener("blur", validateEmail);
    contactInput.addEventListener("blur", validateContact);

    form.addEventListener("submit", function (e) {
        if (!validateAll()) e.preventDefault();
    });
});
