document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.toggle-password').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const wrapper = this.closest('.password-wrapper');
            const input   = wrapper.querySelector('input');

            if (input.type === 'password') {
                input.type       = 'text';
                this.textContent = 'Hide';
            } else {
                input.type       = 'password';
                this.textContent = 'Show';
            }
        });
    });

    function checkPasswordStrength(password) {
        if (password.length < 8) {
            return 'weak';
        }
        const hasUpper  = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        if (hasUpper && hasNumber) {
            return 'strong';
        }
        return 'medium';
    }

    const passwordInput = document.getElementById('password');
    const strengthBar   = document.querySelector('.strength-bar');

    if (passwordInput && strengthBar) {
        passwordInput.addEventListener('input', function () {
            const strength = checkPasswordStrength(this.value);
            strengthBar.setAttribute('data-strength', strength);

            if (strength === 'weak')   strengthBar.style.width = '33%';
            if (strength === 'medium') strengthBar.style.width = '66%';
            if (strength === 'strong') strengthBar.style.width = '100%';
        });
    }

    const confirmInput = document.getElementById('confirm-password');
    const confirmError = document.getElementById('confirm-error');

    if (confirmInput && confirmError && passwordInput) {
        confirmInput.addEventListener('input', function () {
            if (this.value !== passwordInput.value) {
                confirmError.textContent = 'Passwords do not match';
            } else {
                confirmError.textContent = '';
            }
        });
    }

    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');

    if (emailInput && emailError) {
        emailInput.addEventListener('input', function () {
            const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!pattern.test(this.value)) {
                emailError.textContent = 'Enter a valid email address';
            } else {
                emailError.textContent = '';
            }
        });
    }

});