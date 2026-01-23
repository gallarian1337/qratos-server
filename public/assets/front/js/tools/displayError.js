export const displayError = (form, message) => {
    const div = document.createElement("div");
    div.classList.add("q-alert", "q-alert-danger");
    div.innerText = message;
    form.before(div);
};
