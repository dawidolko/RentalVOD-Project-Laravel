/**
 * Registration form: client-side password confirmation check.
 *
 * The message is rendered as visible text tied to the field through
 * aria-describedby, so the problem is not signalled by colour alone and is
 * announced to screen readers. Server-side validation remains authoritative.
 */
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");
    if (!form) {
        return;
    }

    const password = document.getElementById("password");
    const confirmation = document.getElementById("password_confirmation");
    const message = document.getElementById("password_confirmation-mismatch");

    if (!password || !confirmation || !message) {
        return;
    }

    function validate() {
        const mismatch =
            confirmation.value !== "" && password.value !== confirmation.value;

        message.hidden = !mismatch;
        confirmation.setAttribute("aria-invalid", mismatch ? "true" : "false");
        confirmation.classList.toggle("is-invalid", mismatch);
        confirmation.setCustomValidity(mismatch ? "Hasła nie są identyczne." : "");

        return !mismatch;
    }

    confirmation.addEventListener("blur", validate);
    password.addEventListener("blur", function () {
        if (confirmation.value !== "") {
            validate();
        }
    });

    form.addEventListener("submit", function (event) {
        if (!validate()) {
            event.preventDefault();
            confirmation.focus();
        }
    });
});
