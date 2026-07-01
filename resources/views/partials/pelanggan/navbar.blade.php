<header class="navbar">

    <div class="navbar-left">
        <button id="toggleSidebar" class="toggle-btn">
            <i class="bi bi-list"></i>
        </button>
        <h2 class="brand">
            UD Gerlian Jaya
        </h2>
    </div>

<div class="navbar-right">
    <div class="profile" style="display: flex; align-items: center; gap: 15px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            @php
                $pelangganNav = \App\Models\PelangganModels::where('id_user', Auth::id())->first();
                $namaNav = $pelangganNav ? $pelangganNav->nama : (Auth::user()->username ?? 'Pelanggan');
            @endphp
            <div class="avatar" style="width: 36px; height: 36px; background: #3b82f6; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">
                {{ strtoupper(substr($namaNav, 0, 2)) }}
            </div>
            <div class="profile-info" style="display: flex; flex-direction: column;">
                <span class="profile-name" style="font-weight: 600; font-size: 14px; color: #1f2937;">
                    {{ $namaNav }}
                </span>
                <span class="profile-role" style="font-size: 12px; color: #6b7280; text-transform: capitalize;">
                    Pelanggan
                </span>
            </div>
        </div>

        @if(Auth::check())
        <div style="border-left: 1px solid #e5e7eb; padding-left: 15px; margin-left: 5px;">
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; display: flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 600; padding: 5px 10px; border-radius: 6px; transition: background 0.2s;">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
</header>
