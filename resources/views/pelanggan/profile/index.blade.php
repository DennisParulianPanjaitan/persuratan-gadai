@extends('layouts.pelanggan')

@section('title', 'Profil Saya')

@push('styles')
<style>
    .profile-container {
        max-width: 900px;
        margin: 0 auto;
        animation: fadeIn 0.4s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .profile-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
        overflow: hidden;
        margin-bottom: 32px;
        border: 1px solid var(--border);
    }

    .profile-header {
        background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
        height: 140px;
        position: relative;
    }

    .profile-body {
        padding: 32px;
        position: relative;
    }

    .profile-avatar-wrapper {
        position: absolute;
        top: -70px;
        left: 32px;
        z-index: 10;
    }

    .profile-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        object-fit: cover;
        background: #eff6ff;
    }

    .profile-avatar-btn {
        position: absolute;
        bottom: 4px;
        right: 4px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        color: #475569;
        transition: all 0.2s ease;
        overflow: hidden;
    }

    .profile-avatar-btn:hover {
        color: #3b82f6;
        transform: scale(1.05);
    }

    .profile-avatar-btn input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .profile-info-header {
        margin-left: 170px;
        margin-bottom: 32px;
        min-height: 70px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .profile-name {
        font-size: 24px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        letter-spacing: -0.02em;
    }

    .profile-role-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #dbeafe;
        color: #2563eb;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #3b82f6;
        font-size: 18px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #475569;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        color: #94a3b8;
        font-size: 18px;
        pointer-events: none;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px 14px 44px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-family: inherit;
        font-size: 14px;
        color: var(--text);
        background: #f8fafc;
        transition: all 0.2s ease;
    }
    
    textarea.form-control {
        padding-top: 14px;
        min-height: 100px;
        resize: vertical;
    }

    .form-control:focus {
        outline: none;
        background: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .form-hint {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 4px;
    }

    .btn-save {
        background: #2563eb;
        color: #fff;
        border: none;
        padding: 14px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s ease;
        float: right;
    }

    .btn-save:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
    }

    .btn-save:active {
        transform: translateY(0);
    }

    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }

    /* Alerts */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: fadeIn 0.3s ease-out;
    }

    .alert-success {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert-error {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        flex-direction: column;
        align-items: flex-start;
    }

    .alert-error ul {
        margin-left: 24px;
        margin-top: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            height: 100px;
        }
        
        .profile-avatar-wrapper {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .profile-info-header {
            margin-left: 0;
            margin-top: 60px;
            text-align: center;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .btn-save {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill" style="font-size: 18px;"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-error">
            <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                <i class="bi bi-exclamation-triangle-fill" style="font-size: 18px;"></i>
                Terdapat beberapa kesalahan:
            </div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pelanggan.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="profile-card">
            <div class="profile-header"></div>
            
            <div class="profile-body">
                <div class="profile-avatar-wrapper">
                    @php
                        $avatarUrl = $user->foto_profil 
                            ? (Str::startsWith($user->foto_profil, ['http://', 'https://']) ? $user->foto_profil : asset('storage/' . $user->foto_profil))
                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=3b82f6&color=fff&size=256';
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="Foto Profil" class="profile-avatar" id="avatarPreview">
                    <div class="profile-avatar-btn" title="Ubah Foto Profil">
                        <i class="bi bi-camera-fill"></i>
                        <input type="file" name="foto_profil" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)">
                    </div>
                </div>

                <div class="profile-info-header">
                    <h2 class="profile-name">{{ $user->nama }}</h2>
                    <div><span class="profile-role-badge">Pelanggan</span></div>
                </div>

                <div class="section-title">
                    <i class="bi bi-person-lines-fill"></i> Data Pribadi
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" class="form-control" name="nama" value="{{ old('nama', $user->nama) }}" required placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Handphone</label>
                        <div class="input-wrapper">
                            <i class="bi bi-phone input-icon"></i>
                            <input type="text" class="form-control" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}" required placeholder="Contoh: 08123456789">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email <span style="font-weight: 400; color: #94a3b8;">(Opsional)</span></label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" class="form-control" name="email" value="{{ old('email', $pelanggan->email ?? '') }}" placeholder="Contoh: nama@email.com">
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label class="form-label">Alamat Lengkap</label>
                        <div class="input-wrapper">
                            <i class="bi bi-geo-alt input-icon" style="top: 14px;"></i>
                            <textarea class="form-control" name="alamat" required placeholder="Masukkan alamat domisili lengkap...">{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="section-title mt-2">
                    <i class="bi bi-shield-lock-fill"></i> Keamanan Akun
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Username Login</label>
                        <div class="input-wrapper">
                            <i class="bi bi-at input-icon"></i>
                            <input type="text" class="form-control" name="username" value="{{ old('username', $user->username) }}" required placeholder="Masukkan username unik">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password Baru <span style="font-weight: 400; color: #94a3b8;">(Opsional)</span></label>
                        <div class="input-wrapper">
                            <i class="bi bi-key input-icon"></i>
                            <input type="password" class="form-control" name="password" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="form-hint">Kosongkan kolom ini jika Anda tidak ingin mengubah password saat ini.</div>
                    </div>
                </div>

                <div class="clearfix mt-4 pt-3 border-top" style="border-top: 1px solid var(--border); padding-top: 24px; margin-top: 24px;">
                    <button type="submit" class="btn-save">
                        <i class="bi bi-save"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
