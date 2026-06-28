//
/*
==========================================================
GERLIAN JAYA ADMIN DASHBOARD
layout.js
==========================================================
*/

document.addEventListener("DOMContentLoaded", () => {

    // ==========================
    // ELEMENT
    // ==========================

    const sidebar = document.querySelector(".sidebar");
    const main = document.querySelector(".main");
    const toggle = document.getElementById("toggleSidebar");

    // ==========================
    // LOAD STATUS
    // ==========================

    const collapsed = localStorage.getItem("sidebar");

    if (collapsed === "close") {

        sidebar.classList.add("close");
        main.classList.add("expand");

    }

    // ==========================
    // TOGGLE SIDEBAR
    // ==========================

    toggle.addEventListener("click", () => {

        sidebar.classList.toggle("close");
        main.classList.toggle("expand");

        if (sidebar.classList.contains("close")) {

            localStorage.setItem("sidebar", "close");

        } else {

            localStorage.setItem("sidebar", "open");

        }

    });

});
