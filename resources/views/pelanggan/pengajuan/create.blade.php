@extends('layouts.pelanggan')
@section('page_title', 'Pengajuan Gadai')

@push('styles')
<style>
    .mobile-form-container {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 16px;
        font-size: 15px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        background-color: #f8fafc;
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        background-color: #fff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
        display: block;
    }

    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        background-color: #f8fafc;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }

    .upload-area:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .upload-icon {
        font-size: 32px;
        color: #94a3b8;
        margin-bottom: 10px;
    }

    .upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .btn-submit {
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 20px;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        background-color: #2563eb;
    }

    .alert-danger {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        color: #991b1b;
        font-size: 14px;
    }

    #preview-image {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        display: none;
        margin-top: 15px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')

<div class="mobile-form-container">
    <div style="text-align: center; margin-bottom: 24px;">
        <div style="background: #eff6ff; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #3b82f6; font-size: 28px;">
            <i class="bi bi-file-earmark-plus-fill"></i>
        </div>
        <h2 style="margin: 0 0 8px 0; color: #0f172a; font-size: 20px; font-weight: 700;">Ajukan Barang Gadai</h2>
        <p style="margin: 0; color: #64748b; font-size: 14px;">Isi formulir di bawah ini dengan lengkap. Tim kami akan segera meninjau pengajuan Anda.</p>
    </div>

    @if ($errors->any())
        <div class="alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pelanggan.pengajuan_gadai.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label" for="id_jenis_barang">Jenis Barang <span style="color:#ef4444">*</span></label>
            <select name="id_jenis_barang" id="id_jenis_barang" class="form-select" required>
                <option value="" disabled selected>-- Pilih Jenis --</option>
                @foreach($jenisBarangList as $jenis)
                    <option value="{{ $jenis->id_jenis_barang }}" {{ old('id_jenis_barang') == $jenis->id_jenis_barang ? 'selected' : '' }}>
                        {{ $jenis->nama_jenis }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="nama_barang">Nama Barang <span style="color:#ef4444">*</span></label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" placeholder="Contoh: Laptop Asus ROG G531" value="{{ old('nama_barang') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="harga_beli">Perkiraan Harga Beli Saat Ini (Rp) <span style="color:#ef4444">*</span></label>
            <input type="number" name="harga_beli" id="harga_beli" class="form-control" placeholder="Contoh: 15000000" min="0" value="{{ old('harga_beli') }}" required>
            <span class="form-text">Masukkan perkiraan harga pasaran barang Anda sekarang.</span>
        </div>

        <div class="form-group">
            <label class="form-label" for="kondisi">Kondisi Barang <span style="color:#ef4444">*</span></label>
            <select name="kondisi" id="kondisi" class="form-select" required>
                <option value="" disabled selected>-- Pilih Kondisi --</option>
                <option value="Sangat Baik" {{ old('kondisi') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik (Mulus, Normal)</option>
                <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik (Ada lecet pemakaian)</option>
                <option value="Cukup" {{ old('kondisi') == 'Cukup' ? 'selected' : '' }}>Cukup (Minus minor)</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" for="berat">Berat Barang (Gram/Kg) <span style="color:#ef4444">*</span></label>
            <input type="number" step="0.01" name="berat" id="berat" class="form-control" placeholder="Contoh: 1.5 (untuk 1.5 Kg) atau 500 (untuk 500 gram)" min="0" value="{{ old('berat') }}" required>
            <span class="form-text">Masukkan berat dari barang yang diajukan.</span>
        </div>

        <div class="form-group">
            <label class="form-label">Foto Barang <span style="color:#ef4444">*</span></label>
            <div class="upload-area">
                <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                <div style="font-weight: 600; color: #334155; margin-bottom: 4px;">Sentuh untuk Upload Foto</div>
                <div style="font-size: 12px; color: #94a3b8;">Format: JPG, PNG (Maks 2MB)</div>
                <input type="file" name="foto_barang" id="foto_barang" class="upload-input" accept="image/jpeg,image/png,image/webp" required onchange="previewFile()">
            </div>
            <div style="text-align: center;">
                <img id="preview-image" src="#" alt="Preview">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="keterangan">Keterangan Tambahan (Opsional)</label>
            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Contoh: Kelengkapan dus, charger, tas ori...">{{ old('keterangan') }}</textarea>
        </div>

        <button type="submit" class="btn-submit js-submit-btn">
            <i class="bi bi-send-fill"></i> Kirim Pengajuan
        </button>
    </form>
</div>

@endsection

@push('scripts')
<script>
    function previewFile() {
        const preview = document.getElementById('preview-image');
        const file = document.getElementById('foto_barang').files[0];
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            preview.src = reader.result;
            preview.style.display = 'inline-block';
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    // Hindari double click
    document.querySelector('form').addEventListener('submit', function(e) {
        const btn = document.querySelector('.js-submit-btn');
        if (btn) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="width: 1rem; height: 1rem; border: .2em solid currentColor; border-right-color: transparent; border-radius: 50%; animation: spinner-border .75s linear infinite; display: inline-block;"></span> Memproses...';
            btn.style.opacity = '0.7';
            btn.style.pointerEvents = 'none';
        }
    });
</script>
<style>
    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
</style>
@endpush
