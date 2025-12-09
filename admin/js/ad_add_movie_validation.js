document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addMovieForm");

    const movieNameInput = document.getElementById("movie_name");
    const movieImgInput = document.getElementById("movie_img");
    const languageInput = document.getElementById("language");
    const certInput = document.getElementById("certification");
    const priceInput = document.getElementById("movie_price");
    const releaseInput = document.getElementById("release_date");

    const movieNameError = document.getElementById("movie_name_error");
    const movieImgError = document.getElementById("movie_img_error");
    const languageError = document.getElementById("language_error");
    const certError = document.getElementById("certification_error");
    const priceError = document.getElementById("movie_price_error");
    const releaseError = document.getElementById("release_date_error");

    function setError(input, errorElem, message) {
        input.classList.add("input-error");
        input.classList.remove("input-valid");
        errorElem.textContent = message;
        return false;
    }

    function setSuccess(input, errorElem, message) {
        input.classList.remove("input-error");
        input.classList.add("input-valid");
        errorElem.textContent = message || "";
        return true;
    }

    function validateMovieName() {
        const val = movieNameInput.value.trim();
        if (val === "") {
            return setError(movieNameInput, movieNameError, "Movie name is required.");
        }
        if (val.length < 2) {
            return setError(movieNameInput, movieNameError, "Movie name must be at least 2 characters.");
        }
        return setSuccess(movieNameInput, movieNameError, "");
    }

    function validateMovieImg() {
        const val = movieImgInput.value.trim();
        if (val === "") {
            return setError(movieImgInput, movieImgError, "Poster name/URL is required.");
        }
        // optional: basic extension check
        const allowed = /\.(jpg|jpeg|png|avif|webp)$/i;
        if (!allowed.test(val) && !val.startsWith("http")) {
            return setError(movieImgInput, movieImgError, "Should be image file or valid URL.");
        }
        return setSuccess(movieImgInput, movieImgError, "");
    }

    function validateLanguage() {
        const val = languageInput.value.trim();
        if (val === "") {
            return setError(languageInput, languageError, "Language is required.");
        }
        return setSuccess(languageInput, languageError, "");
    }

    function validateCert() {
        const val = certInput.value.trim().toUpperCase();
        if (val === "") {
            return setError(certInput, certError, "Certification is required.");
        }
        // optional strict: only some values allowed
        const allowed = ["U", "UA", "A", "U/A", "R"];
        if (!allowed.includes(val)) {
            return setError(certInput, certError, "Use valid cert (U, UA, A).");
        }
        return setSuccess(certInput, certError, "");
    }

    function validatePrice() {
        const val = priceInput.value.trim();
        if (val === "") {
            return setError(priceInput, priceError, "Price is required.");
        }
        const num = Number(val);
        if (isNaN(num) || num <= 0) {
            return setError(priceInput, priceError, "Price must be a positive number.");
        }
        return setSuccess(priceInput, priceError, "");
    }

    function validateRelease() {
        const val = releaseInput.value;
        if (!val) {
            return setError(releaseInput, releaseError, "Release date is required.");
        }
        // optional: ensure date is not extremely in past
        return setSuccess(releaseInput, releaseError, "");
    }

    function validateAll() {
        const v1 = validateMovieName();
        const v2 = validateMovieImg();
        const v3 = validateLanguage();
        const v4 = validateCert();
        const v5 = validatePrice();
        const v6 = validateRelease();
        return v1 && v2 && v3 && v4 && v5 && v6;
    }

    // Live validation
    movieNameInput.addEventListener("input", validateMovieName);
    movieImgInput.addEventListener("input", validateMovieImg);
    languageInput.addEventListener("input", validateLanguage);
    certInput.addEventListener("input", validateCert);
    priceInput.addEventListener("input", validatePrice);
    releaseInput.addEventListener("input", validateRelease);

    movieNameInput.addEventListener("blur", validateMovieName);
    movieImgInput.addEventListener("blur", validateMovieImg);
    languageInput.addEventListener("blur", validateLanguage);
    certInput.addEventListener("blur", validateCert);
    priceInput.addEventListener("blur", validatePrice);
    releaseInput.addEventListener("blur", validateRelease);

    form.addEventListener("submit", function (e) {
        if (!validateAll()) {
            e.preventDefault();
        }
    });
});
