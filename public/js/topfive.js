/**
 * Horizontal category strip controls.
 *
 * The scroll buttons are a convenience; the container is natively scrollable
 * and keyboard focusable, so the strip remains usable if this script fails.
 */
document.addEventListener("DOMContentLoaded", function () {
    const scrollLeftButton = document.querySelector(".unique-scroll-left");
    const scrollRightButton = document.querySelector(".unique-scroll-right");
    const sliderContainer = document.querySelector(".unique-slider-container");

    if (!sliderContainer) {
        return;
    }

    // Respect the visitor's motion preference for the scroll animation.
    const prefersReducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    ).matches;
    const behavior = prefersReducedMotion ? "auto" : "smooth";

    if (scrollLeftButton) {
        scrollLeftButton.addEventListener("click", function () {
            sliderContainer.scrollBy({ left: -300, behavior: behavior });
        });
    }

    if (scrollRightButton) {
        scrollRightButton.addEventListener("click", function () {
            sliderContainer.scrollBy({ left: 300, behavior: behavior });
        });
    }
});
