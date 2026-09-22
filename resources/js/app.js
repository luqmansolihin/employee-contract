// Global Autocomplete Disabler
document.addEventListener("DOMContentLoaded", () => {
    // Disable autocomplete on all forms
    document.querySelectorAll("form").forEach((form) => {
        form.setAttribute("autocomplete", "off");
    });

    // Disable autocomplete on all inputs (text, email, password, search, number, date, tel, etc.)
    document
        .querySelectorAll(
            'input:not([type="hidden"]):not([type="submit"]):not([type="checkbox"]):not([type="radio"]), select, textarea',
        )
        .forEach((input) => {
            input.setAttribute("autocomplete", "off");
        });
});
