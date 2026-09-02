/**
 * Cart: date constraints and live cost calculation.
 *
 * Every lookup is guarded because the payment block is not rendered when the
 * user checks out with loyalty points, and none of it exists on an empty
 * cart. Validation messages are written into a live region instead of
 * window.alert so they are announced in place and do not steal focus.
 */
document.addEventListener("DOMContentLoaded", function () {
    const totalDisplay = document.getElementById("total-display");
    const dateInputs = document.querySelectorAll(".date-input");

    function updateTotal() {
        if (!totalDisplay) {
            return;
        }
        let total = 0;
        document.querySelectorAll(".total-cost").forEach(function (item) {
            const itemCost = parseFloat(item.textContent.replace(" zł", ""));
            if (!isNaN(itemCost)) {
                total += itemCost;
            }
        });
        totalDisplay.textContent = "Razem: " + total.toFixed(2) + " zł";
    }

    function updateCost(row) {
        const startDateInput = row.querySelector('input[name="start"]');
        const endDateInput = row.querySelector('input[name="end"]');
        const pricePerDayEl = row.querySelector(".price-per-day");
        const totalCostEl = row.querySelector(".total-cost");

        if (!startDateInput || !endDateInput || !pricePerDayEl || !totalCostEl) {
            return;
        }

        const pricePerDay = parseFloat(
            pricePerDayEl.textContent.replace(" zł", "")
        );

        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            const diffDays =
                Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
            const totalCost = diffDays > 0 ? pricePerDay * diffDays : 0;
            totalCostEl.textContent = totalCost.toFixed(2) + " zł";
        } else {
            totalCostEl.textContent = "0 zł";
        }

        updateTotal();
    }

    updateTotal();

    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const todayString = today.toISOString().split("T")[0];

    dateInputs.forEach(function (input) {
        if (input.name === "start") {
            input.min = todayString;
        }
        input.addEventListener("change", function () {
            const row = input.closest("tr");
            if (!row) {
                return;
            }
            const startDateInput = row.querySelector('input[name="start"]');
            const endDateInput = row.querySelector('input[name="end"]');

            if (startDateInput && startDateInput.value && endDateInput) {
                endDateInput.min = startDateInput.value;
                // House rule: a film may be rented for at most 14 days.
                const maxDate = new Date(startDateInput.value);
                maxDate.setDate(maxDate.getDate() + 13);
                endDateInput.max = maxDate.toISOString().split("T")[0];
            }

            updateCost(row);
        });
    });

    function areDatesComplete() {
        return Array.from(dateInputs).every(function (input) {
            return input.value !== "";
        });
    }

    const checkoutButton = document.getElementById("checkout-button");
    const paymentSection = document.getElementById("payment-section");

    if (checkoutButton && paymentSection) {
        checkoutButton.addEventListener("click", function (event) {
            let notice = document.getElementById("checkout-notice");

            if (!areDatesComplete()) {
                event.preventDefault();

                if (!notice) {
                    notice = document.createElement("p");
                    notice.id = "checkout-notice";
                    notice.className = "rv-field-error";
                    notice.setAttribute("role", "alert");
                    checkoutButton.insertAdjacentElement("afterend", notice);
                }
                notice.textContent =
                    "Uzupełnij daty rozpoczęcia i zakończenia dla każdego filmu w koszyku.";

                const firstEmpty = Array.from(dateInputs).find(function (input) {
                    return input.value === "";
                });
                if (firstEmpty) {
                    firstEmpty.focus();
                }
                return;
            }

            if (notice) {
                notice.remove();
            }

            paymentSection.style.display = "block";

            const cardNumber = document.getElementById("cardNumber");
            if (cardNumber) {
                cardNumber.focus();
            }
        });
    }

    const expiryDateInput = document.getElementById("expiryDate");
    if (expiryDateInput) {
        expiryDateInput.min = new Date().toISOString().slice(0, 7);
    }
});
