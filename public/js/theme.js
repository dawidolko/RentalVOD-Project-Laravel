/**
 * Theme toggle.
 *
 * The site defaults to the visitor's OS preference (prefers-color-scheme)
 * and remembers an explicit choice in localStorage, so the setting survives
 * navigation between pages. The toggle button exposes its state through
 * aria-pressed and a visually hidden label so the control is understandable
 * without seeing the icon.
 */
(function () {
    "use strict";

    var STORAGE_KEY = "rv-theme";

    function systemTheme() {
        return window.matchMedia &&
            window.matchMedia("(prefers-color-scheme: light)").matches
            ? "light"
            : "dark";
    }

    function storedTheme() {
        try {
            return localStorage.getItem(STORAGE_KEY);
        } catch (e) {
            return null;
        }
    }

    function storeTheme(theme) {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (e) {
            /* Private browsing - the choice simply will not persist. */
        }
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute("data-bs-theme", theme);
        if (document.body) {
            document.body.setAttribute("data-bs-theme", theme);
        }

        var icon = document.getElementById("theme-icon");
        var button = document.getElementById("theme-toggle");
        var label = document.getElementById("theme-toggle-text");

        if (icon) {
            icon.classList.remove("fa-moon", "fa-sun");
            icon.classList.add("fas", theme === "dark" ? "fa-moon" : "fa-sun");
        }
        if (button) {
            button.setAttribute("aria-pressed", theme === "dark" ? "true" : "false");
        }
        if (label) {
            label.textContent =
                theme === "dark" ? "Przełącz na tryb jasny" : "Przełącz na tryb ciemny";
        }
    }

    // Apply before first paint where possible to avoid a flash of the
    // wrong theme.
    applyTheme(storedTheme() || systemTheme());

    document.addEventListener("DOMContentLoaded", function () {
        applyTheme(storedTheme() || systemTheme());

        var button = document.getElementById("theme-toggle");
        if (!button) {
            return;
        }

        button.addEventListener("click", function () {
            var current =
                document.documentElement.getAttribute("data-bs-theme") || systemTheme();
            var next = current === "dark" ? "light" : "dark";
            applyTheme(next);
            storeTheme(next);
        });
    });

    // Follow the OS when the visitor has not made an explicit choice.
    if (window.matchMedia) {
        window
            .matchMedia("(prefers-color-scheme: light)")
            .addEventListener("change", function () {
                if (!storedTheme()) {
                    applyTheme(systemTheme());
                }
            });
    }
})();

/**
 * Admin quick-jump select (mobile). Navigates on change; kept here because
 * it lives in the same navbar partial.
 */
document.addEventListener("DOMContentLoaded", function () {
    var adminSelect = document.getElementById("admin-select");
    if (!adminSelect) {
        return;
    }
    adminSelect.addEventListener("change", function () {
        if (adminSelect.value) {
            window.location.href = adminSelect.value;
        }
    });
});
