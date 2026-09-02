/**
 * Poster lightbox.
 *
 * Accessibility notes:
 *  - focus moves into the dialog on open and returns to the trigger on close
 *  - Escape closes the dialog (WCAG 2.1.2, no keyboard trap)
 *  - the alt text of the enlarged image follows the poster that was opened
 */
document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("imageOverlay");
    if (!overlay) {
        return;
    }

    const overlayImage = overlay.querySelector(".overlay-image");
    const closeButton = overlay.querySelector(".close-btn");
    let lastFocused = null;

    function openOverlay(imageSource, altText) {
        lastFocused = document.activeElement;
        overlayImage.src = imageSource;
        overlayImage.alt = altText || "Powiększony plakat filmu";
        overlay.style.display = "flex";
        if (closeButton) {
            closeButton.focus();
        }
    }

    function closeOverlay() {
        overlay.style.display = "none";
        overlayImage.src = "";
        if (lastFocused) {
            lastFocused.focus();
            lastFocused = null;
        }
    }

    // Clicking a poster opens it.
    document.querySelectorAll(".product-img").forEach(function (image) {
        image.addEventListener("click", function () {
            openOverlay(this.src, this.alt);
        });
    });

    // The dedicated magnifier button on each card.
    document
        .querySelectorAll(".btn-action.magnification")
        .forEach(function (button) {
            button.addEventListener("click", function () {
                const banner = this.closest(".showcase-banner");
                const img = banner && banner.querySelector(".product-img");
                if (img) {
                    openOverlay(img.src, img.alt);
                }
            });
        });

    if (closeButton) {
        closeButton.addEventListener("click", closeOverlay);
    }

    overlay.addEventListener("click", function (e) {
        if (e.target === overlay) {
            closeOverlay();
        }
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && overlay.style.display === "flex") {
            closeOverlay();
        }
    });
});
