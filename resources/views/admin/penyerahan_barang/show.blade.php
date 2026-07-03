@extends('layouts.admin')

@push('styles')
<style>
    /* Base Container */
    .detail-container {
        max-width: 1000px;
        margin: 0 auto;
        animation: fadeIn 0.4s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .detail-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        overflow: hidden;
        border: 1px solid var(--border);
        margin-bottom: 24px;
    }

    /* Grid Layout */
    .detail-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 0;
    }

    /* Left Sidebar (Image) */
    .detail-image-panel {
        background: #f8fafc;
        padding: 32px;
        display: flex;
        flex-direction: column;
        border-right: 1px solid var(--border);
        align-items: center;
        justify-content: flex-start;
    }

    .image-wrapper {
        width: 100%;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        background: #fff;
        position: relative;
    }

    .image-wrapper::after {
        content: '';
        display: block;
        padding-bottom: 100%; /* 1:1 Aspect Ratio */
    }

    .image-wrapper img, .image-wrapper .no-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        background: #f1f5f9;
        font-weight: 500;
    }

    .no-image i {
        font-size: 32px;
        margin-bottom: 8px;
        color: #cbd5e1;
    }

    /* Right Sidebar (Content) */
    .detail-content-panel {
        padding: 32px 40px;
    }

    .product-title {
        font-size: 26px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
        line-height: 1.3;
        letter-spacing: -0.02em;
    }

    .badge-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 28px;
    }

    .modern-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .badge-price {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
    }

    .badge-category {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #bfdbfe;
    }

    .badge-weight {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    /* Info Sections */
    .info-section {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .info-section:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        transform: translateY(-2px);
    }

    .info-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }

    .info-section.customer::before { background: #3b82f6; }
    .info-section.notes::before { background: #f59e0b; }

    .info-title {
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-title i {
        font-size: 16px;
    }
    
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        border-bottom: 1px dashed #e2e8f0;
        padding-bottom: 6px;
    }

    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-label {
        color: #64748b;
        font-size: 14px;
    }

    .info-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
        text-align: right;
        max-width: 60%;
    }

    /* Action Area */
    .action-area {
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
    }
    
    .action-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 16px;
    }

    .action-buttons {
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .btn-action {
        flex: 1;
        padding: 14px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-accept {
        background: #10b981;
        color: #fff;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }

    .btn-accept:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.3);
    }

    .btn-reject {
        background: #fff;
        color: #ef4444;
        border: 1px solid #fca5a5;
    }

    .btn-reject:hover {
        background: #fef2f2;
        border-color: #ef4444;
    }

    /* SweetAlert Form Controls */
    .swal-custom-input {
        width: 100%;
        box-sizing: border-box;
        margin-top: 8px;
        padding: 12px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 15px;
        font-family: inherit;
        transition: all 0.2s ease;
        background: #f8fafc;
    }
    .swal-custom-input:focus {
        outline: none;
        border-color: #10b981;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .swal-custom-label {
        font-weight: 600;
        font-size: 14px;
        color: #475569;
        display: block;
        margin-bottom: 4px;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .detail-image-panel {
            border-right: none;
            border-bottom: 1px solid var(--border);
            padding: 24px;
        }
        
        .image-wrapper {
            max-width: 350px;
        }

        .detail-content-panel {
            padding: 24px;
        }

        .action-buttons {
            flex-direction: column;
        }
        
        .btn-action {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="page-shell">
    <x-page-header
        title="Proses Transaksi Gadai"
        breadcrumb="Dashboard > Penyerahan Barang > Proses Transaksi"
    />

    <div class="detail-container">
        <!-- Main Card -->
        <div class="detail-card">
            <div class="detail-grid">
                
                <!-- Left Panel: Image -->
                <div class="detail-image-panel">
                    <div class="image-wrapper">
                        @php
                            $currentFoto = $barang->foto_barang 
                                ? (preg_match('/^https?:\/\//', $barang->foto_barang) ? $barang->foto_barang : asset('storage/' . ltrim($barang->foto_barang, '/'))) 
                                : null;
                        @endphp
                        @if($currentFoto)
                            <img src="{{ $currentFoto }}" alt="Foto {{ $barang->nama_barang }}">
                        @else
                            <div class="no-image">
                                <i class="bi bi-image"></i>
                                Tidak Ada Foto
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Panel: Info -->
                <div class="detail-content-panel">
                    <h1 class="product-title">{{ $barang->nama_barang }}</h1>
                    
                    <div class="badge-group">
                        <span class="modern-badge badge-price">
                            <i class="bi bi-tag-fill"></i> Harga ACC: Rp {{ number_format($barang->harga_gadai_sementara, 0, ',', '.') }}
                        </span>
                        <span class="modern-badge badge-category">
                            <i class="bi bi-grid-fill"></i> {{ $barang->jenisBarang->nama_jenis ?? '-' }}
                        </span>
                        @if($barang->berat)
                            <span class="modern-badge badge-weight">
                                <i class="bi bi-speedometer2"></i> {{ $barang->berat }} gram
                            </span>
                        @endif
                    </div>

                    <!-- Customer Info Section -->
                    <div class="info-section customer">
                        <div class="info-title">
                            <i class="bi bi-person-badge-fill" style="color: #3b82f6;"></i> Informasi Kepemilikan
                        </div>
                        <div class="info-list">
                            @if($barang->pelanggan)
                                <div class="info-item">
                                    <span class="info-label">Pemilik</span>
                                    <span class="info-value">{{ $barang->pelanggan->nama }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">No. Telepon</span>
                                    <span class="info-value">{{ $barang->pelanggan->no_hp }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Alamat</span>
                                    <span class="info-value">{{ $barang->pelanggan->alamat ?? '-' }}</span>
                                </div>
                            @else
                                <div style="color: #ef4444; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Data pelanggan tidak valid!
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="info-section notes">
                        <div class="info-title">
                            <i class="bi bi-journal-text" style="color: #f59e0b;"></i> Keterangan Barang Fisik
                        </div>
                        <div class="info-list">
                            <div class="info-item">
                                <span class="info-label">Kondisi Terakhir</span>
                                <span class="info-value">{{ $barang->kondisi ?? 'Tidak disebutkan' }}</span>
                            </div>
                            <div class="info-item" style="border: none; flex-direction: column; align-items: flex-start; gap: 6px;">
                                <span class="info-label">Catatan Tambahan</span>
                                <span class="info-value" style="text-align: left; max-width: 100%; color: #475569; font-weight: 400; line-height: 1.5;">
                                    {{ $barang->keterangan ?? 'Tidak ada catatan tambahan.' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Area -->
                    <div class="action-area">
                        <div class="action-title">Tindakan Selanjutnya</div>
                        
                        <div class="action-buttons">
                            <!-- Reject Action -->
                            <form method="POST" action="{{ route('admin.penyerahan_barang.tolak', $barang) }}" class="js-swal-confirm" data-title="Tolak Barang Fisik?" data-text="Barang fisik tidak sesuai dengan pengajuan online? Status akan diubah menjadi Ditolak dan proses dibatalkan." data-confirm="Ya, Tolak Barang" data-cancel="Kembali" style="flex: 1;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-action btn-reject">
                                    <i class="bi bi-x-circle"></i> Fisik Tidak Sesuai (Tolak)
                                </button>
                            </form>

                            <!-- Accept Action -->
                            <form method="POST" action="{{ route('admin.penyerahan_barang.terima', $barang) }}" id="form-terima" style="flex: 1;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="uang_pinjaman" id="input_uang_pinjaman">
                                <input type="hidden" name="tanggal_jatuh_tempo" id="input_tanggal_jatuh_tempo">

                                <button type="button" class="btn-action btn-accept" onclick="showGadaiAlert()">
                                    <i class="bi bi-check-circle"></i> Terima & Terbitkan Surat
                                </button>

                                @error('uang_pinjaman')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 8px; font-weight: 500;">{{ $message }}</div>
                                @enderror
                                @error('tanggal_jatuh_tempo')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 8px; font-weight: 500;">{{ $message }}</div>
                                @enderror
                            </form>
                        </div>
                    </div>
                    <!-- End Actions -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showGadaiAlert() {
    // Hitung tanggal jatuh tempo (default 4 bulan dari sekarang)
    let defaultDate = new Date();
    defaultDate.setMonth(defaultDate.getMonth() + 4);
    
    // Format YYYY-MM-DD
    let dateString = defaultDate.getFullYear() + '-' + 
                     String(defaultDate.getMonth() + 1).padStart(2, '0') + '-' + 
                     String(defaultDate.getDate()).padStart(2, '0');

    Swal.fire({
        title: 'Detail Pencairan Gadai',
        html: `
            <div style="text-align: left; margin-top: 15px;">
                <label class="swal-custom-label">Pencairan Dana (Rp)</label>
                <input id="swal-uang" class="swal-custom-input" type="number" value="{{ (int)$barang->harga_gadai_sementara }}" min="1" placeholder="Misal: 500000">
            </div>
            <div style="text-align: left; margin-top: 20px;">
                <label class="swal-custom-label">Tanggal Jatuh Tempo (Default 4 Bulan)</label>
                <input id="swal-tanggal" class="swal-custom-input" type="date" value="${dateString}">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-send-check"></i> Proses Gadai',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#94a3b8',
        customClass: {
            confirmButton: 'btn-action btn-accept',
            popup: 'rounded-16'
        },
        preConfirm: () => {
            const uang = document.getElementById('swal-uang').value;
            const tanggal = document.getElementById('swal-tanggal').value;
            
            if (!uang || !tanggal) {
                Swal.showValidationMessage('Semua kolom wajib diisi dengan benar!');
                return false;
            }
            if (uang <= 0) {
                Swal.showValidationMessage('Nominal pinjaman tidak boleh 0!');
                return false;
            }
            
            return { uang, tanggal };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Memasukkan nilai dari popup ke form tersembunyi
            document.getElementById('input_uang_pinjaman').value = result.value.uang;
            document.getElementById('input_tanggal_jatuh_tempo').value = result.value.tanggal;
            
            // Tampilkan loading screen opsional
            Swal.fire({
                title: 'Memproses...',
                text: 'Menerbitkan surat gadai baru',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form
            document.getElementById('form-terima').submit();
        }
    });
}
</script>
@endpush