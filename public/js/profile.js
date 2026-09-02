/**
 * Profile page behaviour.
 *
 * Extracted from inline <script> blocks in user/profile.blade.php so the
 * markup stays readable. Data arrives on window.rvProfileData.
 *
 * Accessibility notes:
 *  - disclosure buttons keep aria-expanded in sync with their panel
 *  - the e-mail autocomplete is an ARIA combobox: options are reachable with
 *    the arrow keys, chosen with Enter, dismissed with Escape
 *  - chart changes are announced through a live region
 *  - validation errors are rendered as text next to the field, not alerts
 */
(function () {
    "use strict";

    const data = window.rvProfileData || {};

    /* ---------------------------------------------------------------
       Disclosure panels
       --------------------------------------------------------------- */

    function togglePanel(panelId, trigger) {
        const panel = document.getElementById(panelId);
        if (!panel) {
            return;
        }

        const willOpen = panel.hidden;
        panel.hidden = !willOpen;

        if (trigger) {
            trigger.setAttribute("aria-expanded", willOpen ? "true" : "false");
        }

        if (willOpen) {
            const firstField = panel.querySelector("input, select, textarea");
            if (firstField) {
                firstField.focus();
            }
        }
    }

    window.toggleReviewForm = function (loanId, trigger) {
        togglePanel("review-form-" + loanId, trigger);
    };

    window.togglePaymentForm = function (loanId, trigger) {
        togglePanel("payment-form-" + loanId, trigger);
    };

    window.toggleRecommendationForm = function (loanId, trigger) {
        togglePanel("recommendation-form-" + loanId, trigger);
    };

    /* ---------------------------------------------------------------
       Snackbar (loyalty point notifications)
       --------------------------------------------------------------- */

    function showSnackbar(message) {
        const snackbar = document.getElementById("snackbar");
        if (!snackbar) {
            return;
        }
        snackbar.textContent = message;
        snackbar.className = "show";
        window.setTimeout(function () {
            snackbar.className = snackbar.className.replace("show", "");
        }, 8000);
    }

    /* ---------------------------------------------------------------
       Card expiry validation
       --------------------------------------------------------------- */

    function initPaymentForms() {
        const currentDate = new Date();
        const minExpiry =
            currentDate.getFullYear() +
            "-" +
            String(currentDate.getMonth() + 1).padStart(2, "0");

        document
            .querySelectorAll('form[id^="payment-form-"]')
            .forEach(function (form) {
                const expiryInput = form.querySelector('[name="expiryDate"]');
                if (!expiryInput) {
                    return;
                }

                expiryInput.min = minExpiry;

                form.addEventListener("submit", function (event) {
                    if (!expiryInput.value) {
                        return;
                    }

                    const expiry = new Date(expiryInput.value + "-01");
                    const startOfMonth = new Date(
                        currentDate.getFullYear(),
                        currentDate.getMonth(),
                        1
                    );

                    let notice = form.querySelector(".rv-expiry-error");

                    if (expiry < startOfMonth) {
                        event.preventDefault();

                        if (!notice) {
                            notice = document.createElement("p");
                            notice.className = "rv-field-error rv-expiry-error";
                            notice.setAttribute("role", "alert");
                            expiryInput.insertAdjacentElement("afterend", notice);
                        }
                        notice.textContent =
                            "Data ważności karty nie może być z przeszłości.";
                        expiryInput.setAttribute("aria-invalid", "true");
                        expiryInput.focus();
                    } else if (notice) {
                        notice.remove();
                        expiryInput.removeAttribute("aria-invalid");
                    }
                });
            });
    }

    /* ---------------------------------------------------------------
       Avatar upload guard
       --------------------------------------------------------------- */

    function initAvatarForm() {
        const avatarForm = document.getElementById("avatarForm");
        if (!avatarForm) {
            return;
        }

        const fileInput = document.getElementById("avatar");
        if (!fileInput) {
            return;
        }

        avatarForm.addEventListener("submit", function (event) {
            if (fileInput.files.length === 0) {
                event.preventDefault();
                fileInput.classList.add("is-invalid");
                fileInput.setAttribute("aria-invalid", "true");
                fileInput.focus();
            } else {
                fileInput.classList.remove("is-invalid");
                fileInput.removeAttribute("aria-invalid");
            }
        });
    }

    /* ---------------------------------------------------------------
       Friend e-mail autocomplete (ARIA combobox)
       --------------------------------------------------------------- */

    function initEmailAutocomplete() {
        const emailInput = document.getElementById("email");
        const emailList = document.getElementById("emailList");
        const status = document.getElementById("emailListStatus");

        if (!emailInput || !emailList || !data.searchUsersUrl) {
            return;
        }

        let options = [];
        let activeIndex = -1;
        let debounceTimer = null;

        function closeList() {
            emailList.innerHTML = "";
            options = [];
            activeIndex = -1;
            emailInput.setAttribute("aria-expanded", "false");
            emailInput.removeAttribute("aria-activedescendant");
        }

        function setActive(index) {
            options.forEach(function (option, i) {
                option.classList.toggle("is-active", i === index);
                option.setAttribute("aria-selected", i === index ? "true" : "false");
            });

            activeIndex = index;

            if (index >= 0 && options[index]) {
                emailInput.setAttribute("aria-activedescendant", options[index].id);
                options[index].scrollIntoView({ block: "nearest" });
            } else {
                emailInput.removeAttribute("aria-activedescendant");
            }
        }

        function choose(index) {
            if (index < 0 || !options[index]) {
                return;
            }
            emailInput.value = options[index].dataset.email;
            closeList();
            emailInput.focus();
        }

        function render(users) {
            emailList.innerHTML = "";
            options = [];
            activeIndex = -1;

            if (!users.length) {
                emailInput.setAttribute("aria-expanded", "false");
                if (status) {
                    status.textContent = "Brak podpowiedzi.";
                }
                return;
            }

            users.forEach(function (user, index) {
                const option = document.createElement("div");
                option.className = "suggestion";
                option.id = "email-option-" + index;
                option.setAttribute("role", "option");
                option.setAttribute("aria-selected", "false");
                option.dataset.email = user.email;
                option.textContent = user.email;

                option.addEventListener("click", function () {
                    choose(index);
                });

                emailList.appendChild(option);
                options.push(option);
            });

            emailInput.setAttribute("aria-expanded", "true");

            if (status) {
                status.textContent =
                    users.length === 1
                        ? "1 podpowiedź. Użyj strzałek, aby wybrać."
                        : users.length +
                          " podpowiedzi. Użyj strzałek, aby wybrać.";
            }
        }

        emailInput.addEventListener("input", function () {
            const query = emailInput.value;

            window.clearTimeout(debounceTimer);

            if (query.length < 3) {
                closeList();
                return;
            }

            debounceTimer = window.setTimeout(function () {
                fetch(data.searchUsersUrl + "?q=" + encodeURIComponent(query))
                    .then(function (response) {
                        return response.ok ? response.json() : [];
                    })
                    .then(render)
                    .catch(function () {
                        closeList();
                    });
            }, 250);
        });

        emailInput.addEventListener("keydown", function (event) {
            if (!options.length) {
                return;
            }

            if (event.key === "ArrowDown") {
                event.preventDefault();
                setActive((activeIndex + 1) % options.length);
            } else if (event.key === "ArrowUp") {
                event.preventDefault();
                setActive((activeIndex - 1 + options.length) % options.length);
            } else if (event.key === "Enter" && activeIndex >= 0) {
                event.preventDefault();
                choose(activeIndex);
            } else if (event.key === "Escape") {
                closeList();
            }
        });

        document.addEventListener("click", function (event) {
            if (!emailInput.contains(event.target) && !emailList.contains(event.target)) {
                closeList();
            }
        });
    }

    /* ---------------------------------------------------------------
       Weekly expenses chart
       --------------------------------------------------------------- */

    function initExpensesChart() {
        const canvas = document.getElementById("expensesChart");
        if (!canvas || typeof Chart === "undefined") {
            return;
        }

        const expensesData = Array.isArray(data.expenses) ? data.expenses : [];
        const prevButton = document.getElementById("prevWeek");
        const nextButton = document.getElementById("nextWeek");
        const status = document.getElementById("expensesStatus");

        function chunkArray(array, size) {
            const result = [];
            for (let i = 0; i < array.length; i += size) {
                result.push(array.slice(i, i + size));
            }
            return result;
        }

        const weeks = chunkArray(expensesData, 7);
        let currentWeekIndex = 0;

        const styles = getComputedStyle(document.documentElement);
        const textColor = styles.getPropertyValue("--rv-text").trim() || "#333";
        const gridColor =
            styles.getPropertyValue("--rv-border-soft").trim() || "#ccc";

        const chart = new Chart(canvas.getContext("2d"), {
            type: "bar",
            data: {
                labels: [],
                datasets: [
                    {
                        label: "Wydatki (zł)",
                        data: [],
                        backgroundColor: "rgba(179, 18, 42, 0.55)",
                        borderColor: "rgba(179, 18, 42, 1)",
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: window.matchMedia("(prefers-reduced-motion: reduce)")
                    .matches
                    ? false
                    : undefined,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: textColor },
                        grid: { color: gridColor },
                    },
                    x: {
                        ticks: { color: textColor },
                        grid: { color: gridColor },
                    },
                },
                plugins: {
                    legend: { labels: { color: textColor } },
                },
            },
        });

        function updateChart(weekIndex) {
            const weekData = weeks[weekIndex] || [];

            chart.data.labels = weekData.map(function (day) {
                return day.date;
            });
            chart.data.datasets[0].data = weekData.map(function (day) {
                return day.amount;
            });
            chart.update();

            // Disable rather than hide, so the controls do not shift the layout
            // and their state stays perceivable.
            if (prevButton) {
                prevButton.disabled = weekIndex <= 0;
            }
            if (nextButton) {
                nextButton.disabled = weekIndex >= weeks.length - 1;
            }

            if (status) {
                status.textContent =
                    "Wyświetlono tydzień " +
                    (weekIndex + 1) +
                    " z " +
                    Math.max(weeks.length, 1) +
                    ".";
            }
        }

        if (prevButton) {
            prevButton.addEventListener("click", function () {
                if (currentWeekIndex > 0) {
                    currentWeekIndex--;
                    updateChart(currentWeekIndex);
                }
            });
        }

        if (nextButton) {
            nextButton.addEventListener("click", function () {
                if (currentWeekIndex < weeks.length - 1) {
                    currentWeekIndex++;
                    updateChart(currentWeekIndex);
                }
            });
        }

        updateChart(currentWeekIndex);
    }

    document.addEventListener("DOMContentLoaded", function () {
        initPaymentForms();
        initAvatarForm();
        initEmailAutocomplete();
        initExpensesChart();

        if (data.pointsMessage) {
            showSnackbar(data.pointsMessage);
        }
    });
})();
