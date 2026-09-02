/**
 * Price range filter.
 *
 * noUiSlider is a pointer-driven control, so the visible number inputs are
 * the accessible equivalent: they are keyboard operable, properly labelled,
 * and kept in sync with the slider in both directions. The hidden inputs
 * are what actually gets submitted, preserving the original query contract.
 */
document.addEventListener("DOMContentLoaded", function () {
    const priceSlider = document.getElementById("price-slider");
    const priceRangeDisplay = document.getElementById("price-range-display");
    const priceMinInput = document.getElementById("price_min");
    const priceMaxInput = document.getElementById("price_max");
    const priceMinVisible = document.getElementById("price_min_input");
    const priceMaxVisible = document.getElementById("price_max_input");

    if (!priceSlider || !priceMinInput || !priceMaxInput) {
        return;
    }

    function syncDisplay(min, max) {
        priceMinInput.value = min;
        priceMaxInput.value = max;
        if (priceRangeDisplay) {
            priceRangeDisplay.textContent = min + " - " + max;
        }
    }

    // Without the library the number inputs still drive the form.
    if (typeof noUiSlider === "undefined") {
        if (priceMinVisible && priceMaxVisible) {
            const handler = function () {
                syncDisplay(priceMinVisible.value, priceMaxVisible.value);
            };
            priceMinVisible.addEventListener("change", handler);
            priceMaxVisible.addEventListener("change", handler);
        }
        return;
    }

    noUiSlider.create(priceSlider, {
        start: [parseInt(priceMinInput.value, 10), parseInt(priceMaxInput.value, 10)],
        connect: true,
        step: 1,
        range: { min: 0, max: 100 },
    });

    priceSlider.noUiSlider.on("update", function (values) {
        const min = Math.round(values[0]);
        const max = Math.round(values[1]);
        syncDisplay(min, max);
        if (priceMinVisible) {
            priceMinVisible.value = min;
        }
        if (priceMaxVisible) {
            priceMaxVisible.value = max;
        }
    });

    // Typing in the number inputs moves the slider.
    if (priceMinVisible && priceMaxVisible) {
        const push = function () {
            priceSlider.noUiSlider.set([
                priceMinVisible.value,
                priceMaxVisible.value,
            ]);
        };
        priceMinVisible.addEventListener("change", push);
        priceMaxVisible.addEventListener("change", push);
    }
});
