/* ==========================================================
   KDFMS - APP.JS
   Global Application JavaScript
========================================================== */

document.addEventListener("DOMContentLoaded", function () {

    /* ======================================================
       LIVE CLOCK
    ====================================================== */

    function updateClock() {

        const clock = document.getElementById("liveClock");

        if (!clock) return;

        const now = new Date();

        clock.textContent = now.toLocaleTimeString();

    }

    updateClock();

    setInterval(updateClock, 1000);

    /* ======================================================
       SIDEBAR TOGGLE
    ====================================================== */

    const menuToggle = document.getElementById("menuToggle");
    const sidebar = document.querySelector(".sidebar");

    if (menuToggle && sidebar) {

        menuToggle.addEventListener("click", function () {

            sidebar.classList.toggle("collapsed");

        });

    }

    /* ======================================================
       AUTO DISMISS ALERTS
    ====================================================== */

    const alerts = document.querySelectorAll(".alert");

    alerts.forEach(function (alert) {

        setTimeout(function () {

            alert.classList.add("fade");

            setTimeout(function () {

                alert.remove();

            }, 500);

        }, 4000);

    });

    /* ======================================================
       CONFIRM DELETE
    ====================================================== */

    const deleteButtons = document.querySelectorAll(".btn-delete");

    deleteButtons.forEach(function (button) {

        button.addEventListener("click", function (event) {

            if (!confirm("Are you sure you want to delete this record?")) {

                event.preventDefault();

            }

        });

    });

    /* ======================================================
       ENABLE BOOTSTRAP TOOLTIPS
    ====================================================== */

    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

    tooltipTriggerList.map(function (tooltipTriggerEl) {

        return new bootstrap.Tooltip(tooltipTriggerEl);

    });

});