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

    const flashSuccess = document.getElementById('flash-success');

    if (flashSuccess && window.Swal) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: flashSuccess.dataset.message,
            confirmButtonColor: '#4F46E5'
        });
    }

    document.querySelectorAll('.js-swal-confirm').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (!window.Swal) {
                form.submit();
                return;
            }

            Swal.fire({
                icon: 'question',
                title: form.dataset.title || 'Apakah anda yakin?',
                text: form.dataset.text || '',
                showCancelButton: true,
                confirmButtonText: form.dataset.confirm || 'Ya',
                cancelButtonText: form.dataset.cancel || 'Batal',
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#64748B'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});
