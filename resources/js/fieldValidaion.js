
function validateFieldRealTime(field) {
    field.value = field.value
        .replace(/^\s+/g, "") // Remove leading spaces
        .replace(/\s\s+/g, " ") // Collapse multiple spaces
        .replace(/^\.+/g, ""); // Prevent leading dots
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#usersForm');

    if (!form) return;

    const nameInput = document.querySelector('#name');
    const emailInput = document.querySelector('#email');
    const passwordInput = document.querySelector('#password');
    const confirmPasswordInput = document.querySelector('#password_confirmation');

    const nameError = document.querySelector('#name-error');
    const emailError = document.querySelector('#email-error');
    const passwordError = document.querySelector('#password-error');
    const confirmPasswordError = document.querySelector('#confirm-password-error');

    // Apply real-time cleaning
    nameInput.addEventListener('input', () => validateFieldRealTime(nameInput));
    emailInput.addEventListener('input', () => validateFieldRealTime(emailInput));

    form.addEventListener('submit', function (event) {
        let isValid = true;

        const name = nameInput.value.trim().replace(/\s+/g, ' ');
        const namePattern = /^[A-Za-z]+(?:\s[A-Za-z]+)*$/;
        if (name === '' || !namePattern.test(name)) {
            isValid = false;
            nameInput.classList.add('is-invalid');
            nameError.textContent = 'Please enter a valid name (letters only, no extra spaces).';
        } else {
            nameInput.classList.remove('is-invalid');
            nameInput.classList.add('is-valid');
            nameError.textContent = '';
            nameInput.value = name;
        }

        const email = emailInput.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            isValid = false;
            emailInput.classList.add('is-invalid');
            emailError.textContent = 'Please enter a valid email address.';
        } else {
            emailInput.classList.remove('is-invalid');
            emailInput.classList.add('is-valid');
            emailError.textContent = '';
            emailInput.value = email;
        }

        const password = passwordInput.value.trim();
        if (password.length < 5) {
            isValid = false;
            passwordInput.classList.add('is-invalid');
            passwordError.textContent = 'Password must be at least 5 characters.';
        } else {
            passwordInput.classList.remove('is-invalid');
            passwordInput.classList.add('is-valid');
            passwordError.textContent = '';
            passwordInput.value = password;
        }

        const confirmPassword = confirmPasswordInput.value.trim();
        if (confirmPassword !== password) {
            isValid = false;
            confirmPasswordInput.classList.add('is-invalid');
            confirmPasswordError.textContent = 'Passwords do not match.';
        } else {
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.add('is-valid');
            confirmPasswordError.textContent = '';
            confirmPasswordInput.value = confirmPassword;
        }

        form.classList.add('was-validated');

        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
});
