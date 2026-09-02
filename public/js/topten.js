/**
 * TOP 10 slider.
 *
 * Announces the newly shown slide through a live region so the change is
 * perceivable without sight, and guards against the controls being absent
 * (the section is not rendered when there is no ranking data).
 */
document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".top-movies-slide");
    const prevButton = document.getElementById("prevSlide");
    const nextButton = document.getElementById("nextSlide");

    if (!slides.length || !prevButton || !nextButton) {
        return;
    }

    let currentSlide = 0;

    function showSlide(index) {
        slides[currentSlide].classList.remove("active");
        currentSlide = (index + slides.length) % slides.length;
        slides[currentSlide].classList.add("active");

        const status = document.getElementById("top-movies-status");
        if (status) {
            status.textContent =
                "Slajd " + (currentSlide + 1) + " z " + slides.length;
        }
    }

    prevButton.addEventListener("click", function () {
        showSlide(currentSlide - 1);
    });

    nextButton.addEventListener("click", function () {
        showSlide(currentSlide + 1);
    });
});
