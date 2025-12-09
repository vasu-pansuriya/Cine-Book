function addFormValidation(formId, rules) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let hasError = false;

        for (let field in rules) {
            const input = form.querySelector(`[name="${field}"]`);
            const errorSpan = document.getElementById(`${field}_error`);
            if (!input || !errorSpan) continue;

            errorSpan.textContent = ''; // clear previous error

            const value = input.value.trim();

            // Required check
            if (rules[field].required && value === '') {
                errorSpan.textContent = rules[field].message || 'This field is required';
                errorSpan.style.color = 'red';
                hasError = true;
                continue;
            }

            // Number check
            if (rules[field].number && value && isNaN(value)) {
                errorSpan.textContent = rules[field].message || 'Must be a number';
                errorSpan.style.color = 'red';
                hasError = true;
                continue;
            }

            // Email check
            if (rules[field].email && value) {
                const re = /\S+@\S+\.\S+/;
                if (!re.test(value)) {
                    errorSpan.textContent = rules[field].message || 'Invalid email';
                    errorSpan.style.color = 'red';
                    hasError = true;
                    continue;
                }
            }
        }

        if (hasError) e.preventDefault(); // stop form submission
    });
}
