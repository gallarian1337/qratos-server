import { displayError } from "./../../tools/displayError.js";

export const login = () => {
    const form = document.getElementById("form-login");

    if (!form) return;

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        document.querySelectorAll(".q-alert").forEach(el => el.remove());

        const email = document.getElementById("email");
        const password = document.getElementById("password");

        if (email === null || password === null) {
            displayError(form, "formulaire invalide");

            return;
        }

        const emailMinLength = Number(email.getAttribute("minlength")) || 6;
        const emailMaxLength = Number(email.getAttribute("maxlength")) || 180;
        const passwordMinLength = Number(password.getAttribute("minlength")) || 12;
        const passwordMaxLength = Number(password.getAttribute("maxlength")) || 255;
        const emailValue = email.value.trim();
        const passwordValue = password.value;

        if (
            emailValue === "" ||
            passwordValue === "" ||
            emailValue.length < emailMinLength ||
            emailValue.length > emailMaxLength ||
            passwordValue.length < passwordMinLength ||
            passwordValue.length > passwordMaxLength
        ) {
            displayError(form, "identifiants invalides");

            return;
        }

        form.submit();
    })
};
