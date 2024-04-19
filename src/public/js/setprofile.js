document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector("form"),
        passField = form.querySelector(".create-password"),
        passInput = passField.querySelector(".password"),
        cPassField = form.querySelector(".confirm-password"),
        cPassInput = cPassField.querySelector(".cPassword"),
        passError = passField.querySelector(".password-error"), // Make sure this selects your error message correctly
        cPassError = cPassField.querySelector(".cPassword-error"); // Adjust if your confirm password has a different error message

    // Toggle visibility of password
    const togglePasswordVisibility = (eyeIcon) => {
        const input = eyeIcon.closest('.input-group').querySelector('input'); // Adjusted to find input related to the clicked icon
        if (input.type === 'password') {
            eyeIcon.classList.replace('bx-hide', 'bx-show');
            input.type = 'text';
        } else {
            eyeIcon.classList.replace('bx-show', 'bx-hide');
            input.type = 'password';
        }
    };

    document.querySelectorAll('.show-hide').forEach(eyeIcon => {
        eyeIcon.addEventListener('click', () => togglePasswordVisibility(eyeIcon));
    });

    // Password requirements and validation
    const requirements = [
        { regex: /.{8,}/, index: 0 }, // Minimum of 8 characters
        { regex: /[0-9]/, index: 1 }, // At least one number
        { regex: /[a-z]/, index: 2 }, // At least one lowercase letter
        { regex: /[^A-Za-z0-9]/, index: 3 }, // At least one special character
        { regex: /[A-Z]/, index: 4 }, // At least one uppercase letter
    ];

    const validatePassword = () => {
        let isValid = true;
        requirements.forEach(item => {
            const isValidRequirement = item.regex.test(passInput.value);
            const requirementItem = document.querySelectorAll(".requirement-list li")[item.index];
            if (isValidRequirement) {
                requirementItem.classList.add("valid");
                requirementItem.firstElementChild.className = "fa-solid fa-check";
            } else {
                isValid = false;
                requirementItem.classList.remove("valid");
                requirementItem.firstElementChild.className = "fa-solid fa-circle";
            }
        });
        passField.classList.toggle("invalid", !isValid);
        passError.style.display = isValid ? "none" : "flex";
    };

    const validateConfirmPassword = () => {
        const isMatch = passInput.value === cPassInput.value;
        cPassField.classList.toggle("invalid", !isMatch);
        cPassError.style.display = isMatch ? "none" : "flex";
    };

    passInput.addEventListener("keyup", validatePassword);
    cPassInput.addEventListener("keyup", validateConfirmPassword);

    form.addEventListener("submit", (e) => {
        e.preventDefault(); // Prevent form from submitting
        validatePassword();
        validateConfirmPassword();

        // Check all fields are valid before submission or redirect
        if (!passField.classList.contains("invalid") && !cPassField.classList.contains("invalid")) {
            e.target.submit();
        }
    });
});

