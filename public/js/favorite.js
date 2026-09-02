/**
 * Client-side "favourite" marker (stored per browser in localStorage).
 *
 * The state is conveyed to assistive technology through aria-pressed on the
 * button and a visually hidden label, not by the icon colour alone.
 */
(function () {
    "use strict";

    function readFavorite(movieId) {
        try {
            return localStorage.getItem("favorite-" + movieId) === "true";
        } catch (e) {
            return false;
        }
    }

    function writeFavorite(movieId, value) {
        try {
            localStorage.setItem("favorite-" + movieId, value ? "true" : "false");
        } catch (e) {
            /* Storage unavailable - the toggle still works for this page view. */
        }
    }

    function render(movieId, isFavorite) {
        var icon = document.getElementById("favorite-icon-" + movieId);
        var button = document.querySelector(
            '.heart.favoriting[data-movie-id="' + movieId + '"]'
        );

        if (icon) {
            icon.classList.remove("bi-heart", "bi-heart-fill");
            icon.classList.add(isFavorite ? "bi-heart-fill" : "bi-heart");
        }

        if (button) {
            button.setAttribute("aria-pressed", isFavorite ? "true" : "false");
            var title = button.getAttribute("data-movie-title") || "";
            button.setAttribute(
                "aria-label",
                (isFavorite ? "Usuń z ulubionych" : "Dodaj do ulubionych") +
                    (title ? " film " + title : "")
            );
        }
    }

    window.toggleFavorite = function (movieId) {
        var next = !readFavorite(movieId);
        writeFavorite(movieId, next);
        render(movieId, next);
    };

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".heart.favoriting").forEach(function (button) {
            var movieId = button.getAttribute("data-movie-id");
            render(movieId, readFavorite(movieId));
        });
    });
})();
