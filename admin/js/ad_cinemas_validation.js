document.addEventListener("DOMContentLoaded", function () {
    const addForm  = document.getElementById("addCinemaForm");
    const editForm = document.getElementById("editCinemaForm");
    const form = addForm || editForm;
    if (!form) return;

    const cinemaNameInput = document.getElementById("cinema_name");
    const featuresInput   = document.getElementById("features");
    const showTimesInput  = document.getElementById("show_times");
    const cancelInput     = document.getElementById("cancelation");

    const cinemaNameError = document.getElementById("cinema_name_error");
    const featuresError   = document.getElementById("features_error");
    const showTimesError  = document.getElementById("show_times_error");
    const cancelError     = document.getElementById("cancelation_error");

    function setError(input, errorElem, message) {
        input.classList.add("input-error");
        input.classList.remove("input-valid");
        errorElem.textContent = message;
        return false;
    }

    function setSuccess(input, errorElem) {
        input.classList.remove("input-error");
        input.classList.add("input-valid");
        errorElem.textContent = "";
        return true;
    }

    function validateCinemaName() {
        const val = cinemaNameInput.value.trim();
        if (val === "") {
            return setError(cinemaNameInput, cinemaNameError, "Cinema name is required.");
        }
        if (val.length < 3) {
            return setError(cinemaNameInput, cinemaNameError, "Minimum 3 characters.");
        }
        return setSuccess(cinemaNameInput, cinemaNameError);
    }

    function validateFeatures() {
        const val = featuresInput.value.trim();
        if (val !== "" && val.length < 5) {
            return setError(featuresInput, featuresError, "If filled, enter at least 5 characters.");
        }
        return setSuccess(featuresInput, featuresError);
    }

    function validateShowTimes() {
        const val = showTimesInput.value.trim();
        if (val === "") {
            return setError(showTimesInput, showTimesError, "Show times are required (e.g. 10:00 AM, 1:00 PM).");
        }
        return setSuccess(showTimesInput, showTimesError);
    }

    function validateCancelation() {
        const val = cancelInput.value.trim();
        if (val === "") {
            return setError(cancelInput, cancelError, "Cancellation policy is required.");
        }
        if (val.length < 5) {
            return setError(cancelInput, cancelError, "Please give a brief policy (min 5 characters).");
        }
        return setSuccess(cancelInput, cancelError);
    }

    function validateAll() {
        const v1 = validateCinemaName();
        const v2 = validateFeatures();
        const v3 = validateShowTimes();
        const v4 = validateCancelation();
        return v1 && v2 && v3 && v4;
    }

    cinemaNameInput.addEventListener("input", validateCinemaName);
    featuresInput.addEventListener("input", validateFeatures);
    showTimesInput.addEventListener("input", validateShowTimes);
    cancelInput.addEventListener("input", validateCancelation);

    cinemaNameInput.addEventListener("blur", validateCinemaName);
    featuresInput.addEventListener("blur", validateFeatures);
    showTimesInput.addEventListener("blur", validateShowTimes);
    cancelInput.addEventListener("blur", validateCancelation);

    form.addEventListener("submit", function (e) {
        if (!validateAll()) {
            e.preventDefault();
        }
    });
});
