/**
 * Admin panel disclosure helpers.
 *
 * All panels use the `hidden` attribute rather than inline display styles,
 * and each trigger keeps aria-expanded in sync so the state is exposed to
 * assistive technology. Focus moves into a panel when it opens and returns
 * to the trigger when it closes.
 */

function openEditPanel(movieId, trigger) {
    const panel = document.getElementById("edit-panel-" + movieId);
    if (!panel) {
        return;
    }

    panel.hidden = false;

    if (trigger) {
        trigger.setAttribute("aria-expanded", "true");
        panel.dataset.returnFocusTo = trigger.id || "";
    }

    const firstField = panel.querySelector("input, select, textarea");
    if (firstField) {
        firstField.focus();
    }
}

function closeEditPanel(movieId) {
    const panel = document.getElementById("edit-panel-" + movieId);
    if (!panel) {
        return;
    }

    panel.hidden = true;

    const trigger = document.querySelector(
        '[aria-controls="edit-panel-' + movieId + '"]'
    );
    if (trigger) {
        trigger.setAttribute("aria-expanded", "false");
        trigger.focus();
    }
}

function toggleAddPanel(event, type, trigger) {
    if (event) {
        event.preventDefault();
    }

    const panelId = type === "movie" ? "add-panel-movie" : "add-panel-category";
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

function togglePromoSlider(movieId, trigger) {
    const panel = document.getElementById("promo-slider-" + movieId);
    if (!panel) {
        return;
    }

    const willOpen = panel.hidden;
    panel.hidden = !willOpen;

    if (trigger) {
        trigger.setAttribute("aria-expanded", willOpen ? "true" : "false");
    }

    if (willOpen) {
        const input = panel.querySelector("input");
        if (input) {
            input.focus();
        }
    }
}
