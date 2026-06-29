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

    // ==========================
    // SWAL CONFIRM + HARGA GADAI
    // ==========================

    document.querySelectorAll('.js-swal-confirm-price').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (!window.Swal) {
                form.submit();
                return;
            }

            const hargaBeli   = parseFloat(form.dataset.hargaBeli) || 0;
            const namaBarang  = form.dataset.namaBarang || 'Barang';
            const hargaGadaiDefault = Math.round(hargaBeli * 0.5);

            const fmt = (n) => 'Rp ' + Math.round(n).toLocaleString('id-ID');

            const hitungKalkulasi = (hargaGadai) => {
                const hargaJual    = hargaGadai * 1.5;
                const bungaBulan   = hargaGadai * 0.075;
                const totalBunga   = bungaBulan * 4;
                const totalDitebus = hargaGadai + totalBunga;
                return { hargaJual, bungaBulan, totalBunga, totalDitebus };
            };

            const renderKalkulasi = (hargaGadai) => {
                const el = document.getElementById('swal-calc-result');
                if (!el) return;
                if (!hargaGadai || hargaGadai <= 0) {
                    el.innerHTML = '';
                    return;
                }
                const k = hitungKalkulasi(hargaGadai);
                el.innerHTML = `
                    <table style="width:100%;font-size:0.82rem;border-collapse:collapse;margin-top:4px;text-align:left;">
                        <tr><td style="padding:4px 6px;color:#64748B;">Harga Beli</td><td style="padding:4px 6px;font-weight:600;">${fmt(hargaBeli)}</td></tr>
                        <tr style="background:#F8FAFC;"><td style="padding:4px 6px;color:#64748B;">Harga Gadai (input)</td><td style="padding:4px 6px;font-weight:600;color:#4F46E5;">${fmt(hargaGadai)}</td></tr>
                        <tr><td style="padding:4px 6px;color:#64748B;">Harga Jual (150%)</td><td style="padding:4px 6px;font-weight:600;">${fmt(k.hargaJual)}</td></tr>
                        <tr style="background:#F8FAFC;"><td style="padding:4px 6px;color:#64748B;">Bunga / bulan (7.5%)</td><td style="padding:4px 6px;font-weight:600;">${fmt(k.bungaBulan)}</td></tr>
                        <tr><td style="padding:4px 6px;color:#64748B;">Jangka Waktu</td><td style="padding:4px 6px;font-weight:600;">4 Bulan</td></tr>
                        <tr style="background:#F8FAFC;"><td style="padding:4px 6px;color:#64748B;">Total Ditebus</td><td style="padding:4px 6px;font-weight:700;color:#0F172A;">${fmt(k.totalDitebus)}</td></tr>
                    </table>
                `;
            };

            Swal.fire({
                title: form.dataset.title || 'Terima Barang Gadai',
                html: `
                    <p style="margin-bottom:10px;color:#64748B;font-size:0.9rem;">
                        <strong style="color:#0F172A;">${namaBarang}</strong><br>
                        Harga beli: <strong>${fmt(hargaBeli)}</strong> &mdash; Default gadai (50%): <strong>${fmt(hargaGadaiDefault)}</strong>
                    </p>
                    <input type="number" id="swal-harga-gadai" class="swal2-input"
                           placeholder="Masukkan harga gadai (Rp)"
                           value="${hargaGadaiDefault}" min="1" step="1000"
                           style="margin-bottom:6px;">
                    <div id="swal-calc-result"></div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: form.dataset.confirm || 'Ya, Terima',
                cancelButtonText: form.dataset.cancel || 'Batal',
                confirmButtonColor: '#4F46E5',
                cancelButtonColor: '#64748B',
                didOpen: () => {
                    // Render kalkulasi untuk nilai default
                    renderKalkulasi(hargaGadaiDefault);

                    // Update real-time saat user mengubah nilai
                    document.getElementById('swal-harga-gadai').addEventListener('input', (e) => {
                        renderKalkulasi(parseFloat(e.target.value) || 0);
                    });
                },
                preConfirm: () => {
                    const val = parseFloat(document.getElementById('swal-harga-gadai').value);
                    if (!val || val <= 0) {
                        Swal.showValidationMessage('Masukkan nominal harga gadai yang valid.');
                        return false;
                    }
                    return val;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    // Isi hidden input lalu submit form biasa
                    const hiddenInput = form.querySelector('.js-harga-gadai-input');
                    if (hiddenInput) hiddenInput.value = result.value;
                    form.submit();
                }
            });
        });
    });

});

