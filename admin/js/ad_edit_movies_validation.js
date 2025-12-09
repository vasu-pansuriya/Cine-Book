document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("editMovieForm") || document.getElementById("addMovieForm");

    if (!form) return;

    const fields = {
        movie_name: "Movie name is required",
        movie_img: "Poster URL / File Name required",
        language: "Language required",
        certification: "Certification required (U/UA/A)",
        movie_price: "Valid price required",
        release_date: "Select release date"
    };

    function validateField(id) {
        const input = document.getElementById(id);
        const error = document.getElementById(id + "_error");
        const value = input.value.trim();

        if (value === "") {
            input.classList.add("input-error");
            input.classList.remove("input-valid");
            error.textContent = fields[id];
            return false;
        }

        if (id === "movie_price" && (isNaN(value) || value <= 0)) {
            error.textContent = fields[id];
            input.classList.add("input-error");
            input.classList.remove("input-valid");
            return false;
        }

        // success
        input.classList.remove("input-error");
        input.classList.add("input-valid");
        error.textContent = "";
        return true;
    }

    // Live validation
    for (let id in fields) {
        document.getElementById(id).addEventListener("input", () => validateField(id));
    }

    // Submit check
    form.addEventListener("submit", function (e) {
        let valid = true;
        for (let id in fields) {
            if (!validateField(id)) valid = false;
        }
        if (!valid) e.preventDefault();
    });
});
