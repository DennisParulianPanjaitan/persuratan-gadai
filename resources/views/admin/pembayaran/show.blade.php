@extends('layouts.admin')

@section('content')
<div class="page-shell">
    <x-page-header
        title="Detail Pembayaran"
        breadcrumb="Dashboard > Pembayaran > Detail"
        buttonText=""
        buttonUrl=""
    />

    <x-card>
        <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 24px;">
            <a href="{{ route('admin.pembayaran.index') }}" class="btn btn-outline" style="border: none; background: #f8fafc; color: #475569; border-radius: 8px; padding: 8px 12px; display: inline-flex; align-items: center; gap: 6px; font-weight: 600;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h3 style="margin: 0; font-size: 18px; color: #0f172a;">Request Pembayaran Online</h3>
            @if($pembayaran->status == 'menunggu_konfirmasi')
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-700">Menunggu</span>
            @elseif($pembayaran->status == 'disetujui')
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700">Disetujui</span>
            @else
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            
            <!-- Kolom Kiri: Detail Informasi -->
            <div style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px;">
                <h4 style="margin: 0 0 16px 0; font-size: 15px; color: #334155; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">Informasi Transaksi</h4>
                
                <table style="width: 100%; border-collapse: separate; border-spacing: 0 12px; font-size: 14px;">
                    <tr>
                        <td style="color: #64748b; width: 45%;">Kode Transaksi</td>
                        <td style="font-weight: 700; color: #0f172a;">{{ $pembayaran->transaksiGadai->kode_transaksi }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Nama Pelanggan</td>
                        <td style="font-weight: 600; color: #1d4ed8;">{{ $pembayaran->transaksiGadai->pelanggan->user->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Barang Gadai</td>
                        <td style="font-weight: 600; color: #334155;">{{ $pembayaran->transaksiGadai->barang->nama_barang ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Tanggal Jatuh Tempo</td>
                        <td style="font-weight: 600; color: #ef4444;">{{ \Carbon\Carbon::parse($pembayaran->transaksiGadai->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</td>
                    </tr>
                </table>

                <h4 style="margin: 24px 0 16px 0; font-size: 15px; color: #334155; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">Detail Pembayaran</h4>

                <table style="width: 100%; border-collapse: separate; border-spacing: 0 12px; font-size: 14px;">
                    <tr>
                        <td style="color: #64748b; width: 45%;">Jenis Pengajuan</td>
                        <td style="font-weight: 700; color: #0f172a;">
                            {{ ucfirst($pembayaran->jenis_pembayaran) }}
                        </td>
                    </tr>
                    @if($pembayaran->jenis_pembayaran == 'perpanjangan')
                    <tr>
                        <td style="color: #64748b;">Tambahan Bulan</td>
                        <td style="font-weight: 600; color: #0f172a;">{{ $pembayaran->jumlah_bulan }} Bulan</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color: #64748b;">Tanggal Upload</td>
                        <td style="font-weight: 600; color: #0f172a;">{{ \Carbon\Carbon::parse($pembayaran->created_at)->translatedFormat('d F Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-size: 15px;">Total Transfer</td>
                        <td style="font-weight: 800; color: #10b981; font-size: 18px;">Rp {{ number_format($pembayaran->nominal_bayar, 0, ',', '.') }}</td>
                    </tr>
                </table>

                @if($pembayaran->status == 'ditolak')
                    <div style="margin-top: 20px; background: #fee2e2; border: 1px solid #fca5a5; padding: 12px; border-radius: 8px;">
                        <span style="display: block; font-size: 12px; color: #b91c1c; font-weight: 700; margin-bottom: 4px;">Alasan Penolakan:</span>
                        <div style="font-size: 14px; color: #991b1b;">{{ $pembayaran->catatan_admin }}</div>
                    </div>
                @endif
            </div>

            <!-- Kolom Kanan: Bukti Pembayaran -->
            <div style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; text-align: center; display: flex; flex-direction: column;">
                <h4 style="margin: 0 0 16px 0; font-size: 15px; color: #334155; text-align: left; border-bottom: 1px solid #cbd5e1; padding-bottom: 8px;">Bukti Transfer</h4>
                
                <div style="flex-grow: 1; display: flex; align-items: center; justify-content: center; background: #f1f5f9; border-radius: 8px; overflow: hidden; padding: 10px;">
                    @if($pembayaran->bukti_pembayaran)
                        @php
                            $fotoBkt = preg_match('/^https?:\/\//', $pembayaran->bukti_pembayaran)
                                ? $pembayaran->bukti_pembayaran
                                : asset('storage/' . ltrim($pembayaran->bukti_pembayaran, '/'));
                        @endphp
                        <a href="{{ $fotoBkt }}" target="_blank">
                            <img src="{{ $fotoBkt }}" alt="Bukti Transfer" style="max-width: 100%; max-height: 400px; object-fit: contain; border-radius: 4px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                        </a>
                    @else
                        <span style="color: #94a3b8; font-style: italic;">Tidak ada foto bukti</span>
                    @endif
                </div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 10px;">* Klik gambar untuk memperbesar</div>

                @if($pembayaran->status == 'menunggu_konfirmasi')
                    <div style="margin-top: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
                        <button type="button" onclick="showTolakAlert()" style="flex: 1; padding: 12px; background: white; color: #ef4444; border: 1px solid #ef4444; border-radius: 8px; font-weight: 600; cursor: pointer;">
                            <i class="bi bi-x-circle"></i> Tolak
                        </button>
                        <button type="button" onclick="showTerimaAlert()" style="flex: 1; padding: 12px; background: #10b981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                            <i class="bi bi-check-circle"></i> Terima & Proses
                        </button>
                    </div>

                    <!-- Hidden Forms -->
                    <form id="form-tolak" action="{{ route('admin.pembayaran.tolak', $pembayaran->id_pembayaran) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="catatan_admin" id="input-catatan">
                    </form>

                    <form id="form-terima" action="{{ route('admin.pembayaran.terima', $pembayaran->id_pembayaran) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                    </form>
                @endif
            </div>

        </div>
    </x-card>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showTolakAlert() {
        Swal.fire({
            title: 'Tolak Pembayaran?',
            text: 'Silakan masukkan alasan penolakan. Pesan ini akan dibaca oleh pelanggan.',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Ketik alasan di sini (cth: Bukti transfer buram/tidak valid)...',
            inputAttributes: {
                'aria-label': 'Ketik alasan penolakan'
            },
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
            preConfirm: (catatan) => {
                if (!catatan) {
                    Swal.showValidationMessage('Alasan penolakan wajib diisi!');
                }
                return catatan;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('input-catatan').value = result.value;
                document.getElementById('form-tolak').submit();
            }
        });
    }

    function showTerimaAlert() {
        let titleText = 'Validasi Pembayaran';
        let htmlText = 'Apakah Anda yakin ingin menyetujui pembayaran ini? <br><br>';
        
        @if($pembayaran->jenis_pembayaran == 'tebus')
            htmlText += '<strong style="color:#10b981;">Status barang akan berubah menjadi Ditebus.</strong>';
        @else
            htmlText += '<strong style="color:#3b82f6;">Masa jatuh tempo akan otomatis diperpanjang sesuai pengajuan.</strong>';
        @endif

        Swal.fire({
            title: titleText,
            html: htmlText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10b981',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('form-terima').submit();
            }
        });
    }
</script>
@endpush
@endsection
