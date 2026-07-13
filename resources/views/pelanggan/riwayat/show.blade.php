@extends('layouts.pelanggan')
@section('page_title', 'Detail Riwayat')

@push('styles')
<style>
    .detail-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        max-width: 600px;
        margin: 0 auto 24px;
        overflow: hidden;
    }
    .dc-header {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        background: #fafafa;
        text-align: center;
    }
    .dc-body {
        padding: 24px;
    }
    .dc-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .dc-row:last-child {
        border-bottom: none;
    }
    .dc-label {
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
    }
    .dc-value {
        color: #0f172a;
        font-size: 14px;
        font-weight: 700;
        text-align: right;
        max-width: 60%;
    }
    .qr-container {
        background: #fff;
        padding: 24px;
        border-radius: 16px;
        display: inline-block;
        border: 2px dashed #cbd5e1;
        margin: 20px 0;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 15px;
    }
    .btn-back:hover {
        color: #0f172a;
    }
</style>
@endpush

@section('content')

@php
    $transaksi = $barang->transaksiGadai->first();
    $status = $transaksi ? $transaksi->status : $barang->status_verifikasi;
    $fotoBarang = $barang->foto_barang ? (preg_match('/^https?:\/\//', $barang->foto_barang) ? $barang->foto_barang : asset('storage/' . ltrim($barang->foto_barang, '/'))) : null;
    $pendingPayment = $transaksi ? \App\Models\PembayaranOnlineModels::where('id_transaksi_gadai', $transaksi->id_transaksi_gadai)->where('status', 'menunggu_konfirmasi')->first() : null;
@endphp


<div style="max-width: 600px; margin: 0 auto;">
    <a href="{{ route('pelanggan.riwayat.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
    </a>

    <div class="detail-card">
        <div class="dc-header">
            @if(in_array($status, ['terverifikasi', 'aktif']))
                <h3 style="margin: 0 0 8px 0; color: #0f172a;">Kode QR Anda</h3>
                <p style="margin: 0; color: #64748b; font-size: 13px;">Tunjukkan QR Code ini kepada petugas kami di loket untuk mempercepat proses pelayanan.</p>
                
                <div class="qr-container">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(1)->generate(route('public.surat_gadai', $barang->id_barang)) !!}
                </div>
                <div style="font-size: 16px; font-weight: 800; color: #334155; letter-spacing: 1px;">
                    {{ $transaksi ? $transaksi->kode_transaksi : 'BARANG-'.$barang->id_barang }}
                </div>
            @else
                <h3 style="margin: 0; color: #0f172a;">Detail Pengajuan</h3>
            @endif
        </div>

        <div class="dc-body">
            @if($fotoBarang)
                <div style="text-align: center; margin-bottom: 24px;">
                    <img src="{{ $fotoBarang }}" style="max-width: 100%; max-height: 250px; border-radius: 12px; border: 1px solid #e2e8f0; object-fit: contain;">
                </div>
            @endif

            <div class="dc-row">
                <span class="dc-label">Nama Barang</span>
                <span class="dc-value">{{ $barang->nama_barang }}</span>
            </div>
            <div class="dc-row">
                <span class="dc-label">Kategori</span>
                <span class="dc-value">{{ $barang->jenisBarang->nama_jenis ?? '-' }}</span>
            </div>
            <div class="dc-row">
                <span class="dc-label">Tanggal Pengajuan</span>
                <span class="dc-value">{{ \Carbon\Carbon::parse($barang->created_at)->translatedFormat('d F Y') }}</span>
            </div>
            
            @if($transaksi)
                <div class="dc-row">
                    <span class="dc-label">Uang Pinjaman</span>
                    <span class="dc-value" style="color: #1d4ed8;">Rp {{ number_format($transaksi->uang_pinjaman, 0, ',', '.') }}</span>
                </div>
                <div class="dc-row">
                    <span class="dc-label">Tanggal Gadai</span>
                    <span class="dc-value">{{ \Carbon\Carbon::parse($transaksi->tanggal_gadai)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="dc-row">
                    <span class="dc-label">Jatuh Tempo</span>
                    <span class="dc-value" style="color: #ef4444;">{{ \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</span>
                </div>
                @if($transaksi->status == 'aktif')
                    @php
                        $now = now()->startOfDay();
                        $tglGadai = \Carbon\Carbon::parse($transaksi->tanggal_gadai)->startOfDay();
                        $hariBerjalan = $tglGadai->diffInDays($now);
                        
                        $totalBunga = 0;
                        if($hariBerjalan <= 15) {
                            $totalBunga = round($transaksi->uang_pinjaman * 0.05);
                        } else {
                            $monthsDiff = $tglGadai->diffInMonths($now);
                            if($now->copy()->startOfDay()->day < $tglGadai->day) {
                                $monthsDiff--;
                            }
                            $finalMonths = max(1, $monthsDiff);
                            $totalBunga = round($transaksi->uang_pinjaman * 0.075 * $finalMonths);
                        }
                        $totalBayar = $transaksi->uang_pinjaman + $totalBunga;
                    @endphp
                    <div class="dc-row" style="background: #ecfdf5; border-radius: 8px; margin: 10px 0;">
                        <span class="dc-label" style="color: #065f46;">Est. Biaya Penebusan</span>
                        <span class="dc-value" style="color: #10b981; font-size: 18px;">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="dc-row">
                    <span class="dc-label">Status Terkini</span>
                    <span class="dc-value">{{ ucfirst($transaksi->status) }}</span>
                </div>
            @else
                <div class="dc-row">
                    <span class="dc-label">Est. Harga Beli</span>
                    <span class="dc-value">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</span>
                </div>
                <div class="dc-row">
                    <span class="dc-label">Kondisi</span>
                    <span class="dc-value">{{ $barang->kondisi }}</span>
                </div>
                @if($status == 'terverifikasi' && $barang->harga_gadai_sementara)
                    <div class="dc-row">
                        <span class="dc-label" style="color: #1d4ed8;">Penawaran Gadai Admin</span>
                        <span class="dc-value" style="color: #1d4ed8;">Rp {{ number_format($barang->harga_gadai_sementara, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="dc-row">
                    <span class="dc-label">Status Verifikasi</span>
                    <span class="dc-value">{{ ucfirst($barang->status_verifikasi) }}</span>
                </div>
            @endif

            <div class="dc-row" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                <span class="dc-label">Keterangan Tambahan:</span>
                <span style="color: #334155; font-size: 14px; background: #f8fafc; padding: 12px; border-radius: 8px; width: 100%; display: block; box-sizing: border-box;">
                    {{ $barang->keterangan ?: 'Tidak ada keterangan tambahan' }}
                </span>
            </div>

            @if($transaksi && $transaksi->status == 'aktif')
                @php
                    $isJatuhTempo = \Carbon\Carbon::parse($transaksi->tanggal_jatuh_tempo)->startOfDay()->lt(\Carbon\Carbon::today());
                @endphp
                <hr style="border: 0; border-top: 1px dashed #e2e8f0; margin: 20px 0;">
                <h4 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 700; color: #0f172a;">Aksi Pembayaran</h4>
                
                @if($pendingPayment)
                    <div style="background: #fffbeb; border: 1px solid #fde68a; padding: 16px; border-radius: 8px; color: #92400e; font-size: 14px; display: flex; gap: 12px; align-items: center;">
                        <i class="bi bi-info-circle-fill" style="font-size: 20px; color: #d97706;"></i>
                        <div>
                            <strong>Pembayaran Sedang Diproses</strong><br>
                            Anda telah mengajukan {{ ucfirst($pendingPayment->jenis_pembayaran) }} (Rp {{ number_format($pendingPayment->nominal_bayar, 0, ',', '.') }}) dan sedang menunggu konfirmasi admin.
                        </div>
                    </div>
                @elseif($isJatuhTempo)
                    <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 16px; border-radius: 8px; color: #991b1b; font-size: 14px; display: flex; gap: 12px; align-items: center;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 20px; color: #dc2626;"></i>
                        <div>
                            <strong>Melewati Jatuh Tempo</strong><br>
                            Masa gadai barang ini telah habis. Aksi pembayaran online dinonaktifkan. Silakan segera datang ke loket atau hubungi admin.
                        </div>
                    </div>
                @else
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <button type="button" onclick="showTebusAlert()" style="flex: 1; padding: 12px; background: #10b981; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="bi bi-box-arrow-in-right"></i> Tebus Gadai
                        </button>
                        <button type="button" onclick="showPerpanjangAlert()" style="flex: 1; padding: 12px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                            <i class="bi bi-arrow-repeat"></i> Perpanjang Gadai
                        </button>
                    </div>

                    <!-- Hidden Form Tebus -->
                    <form id="form-tebus" action="{{ route('pelanggan.riwayat.bayar_tebus', $transaksi->id_transaksi_gadai) }}" method="POST" enctype="multipart/form-data" style="display: none;">
                        @csrf
                        <input type="hidden" name="nominal_bayar" id="input-nominal-tebus">
                        <input type="file" name="bukti_pembayaran" id="input-file-tebus" accept="image/*">
                    </form>

                    <!-- Hidden Form Perpanjangan -->
                    <form id="form-perpanjang" action="{{ route('pelanggan.riwayat.bayar_perpanjang', $transaksi->id_transaksi_gadai) }}" method="POST" enctype="multipart/form-data" style="display: none;">
                        @csrf
                        <input type="hidden" name="jumlah_bulan" id="input-bulan-perpanjang">
                        <input type="hidden" name="nominal_bayar" id="input-nominal-perpanjang">
                        <input type="file" name="bukti_pembayaran" id="input-file-perpanjang" accept="image/*">
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    @if($transaksi && $transaksi->status == 'aktif')
        let uangPinjaman = {{ $transaksi->uang_pinjaman }};
        let tglJatuhTempo = new Date('{{ $transaksi->tanggal_jatuh_tempo }}');
        let totalBayarTebus = {{ isset($totalBayar) ? $totalBayar : 0 }};
        let kodeTrx = '{{ $transaksi->kode_transaksi }}';

        let formatRp = (angka) => 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);

        function showTebusAlert() {
            Swal.fire({
                title: 'Tebus Gadai',
                html: `
                    <div style="font-weight: 700; margin-bottom: 10px; color: #0F172A; font-size: 18px;">${kodeTrx}</div>
                    <div style="background: #f8fafc; padding: 12px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e2e8f0;">
                        <div style="font-size: 13px; color: #64748B;">Total Pelunasan:</div>
                        <div style="font-size: 20px; font-weight: 700; color: #10b981; margin: 4px 0;">${formatRp(totalBayarTebus)}</div>
                    </div>
                    
                    <div style="text-align: left; margin-bottom: 15px; font-size: 14px;">
                        Silakan transfer sesuai nominal di atas ke:<br>
                        <strong>BCA: 1234567890 (a/n Gerlian Jaya)</strong><br>
                        <strong>DANA: 081234567890 (a/n Gerlian Jaya)</strong>
                    </div>

                    <div style="text-align: left;">
                        <label style="font-weight: 700; font-size: 14px; color: #334155;">Upload Bukti Transfer (Wajib)</label>
                        <input id="swal-file-tebus" type="file" class="swal-custom-input" accept="image/*" style="padding: 10px; width: 100%; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 6px; margin-top: 5px;">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Kirim Bukti',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10b981',
                preConfirm: () => {
                    const fileInput = document.getElementById('swal-file-tebus');
                    if (!fileInput.files.length) {
                        Swal.showValidationMessage('Anda wajib mengunggah bukti pembayaran!');
                        return false;
                    }
                    return fileInput.files[0];
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    let file = result.value;
                    let fileInputTebus = document.getElementById('input-file-tebus');
                    
                    // Transfer file object to hidden input using DataTransfer
                    let dt = new DataTransfer();
                    dt.items.add(file);
                    fileInputTebus.files = dt.files;
                    
                    document.getElementById('input-nominal-tebus').value = totalBayarTebus;
                    document.getElementById('form-tebus').submit();
                }
            });
        }

        function showPerpanjangAlert() {
            let now = new Date();
            now.setHours(0,0,0,0);
            
            let remainingMonths = (tglJatuhTempo.getFullYear() - now.getFullYear()) * 12;
            remainingMonths -= now.getMonth();
            remainingMonths += tglJatuhTempo.getMonth();
            
            let maxTambahan = 4 - remainingMonths;
            
            if (maxTambahan <= 0) {
                Swal.fire({
                    title: 'Batas Maksimal!',
                    text: 'Sisa waktu gadai saat ini masih penuh (4 bulan). Anda harus menunggu setidaknya 1 bulan berlalu untuk bisa memperpanjangnya lagi.',
                    icon: 'error'
                });
                return;
            }

            let bungaPerBulan = Math.round(uangPinjaman * 0.075);
            let optionsHtml = '';
            for (let i = 1; i <= maxTambahan; i++) {
                optionsHtml += `<option value="${i}">${i} Bulan</option>`;
            }

            Swal.fire({
                title: 'Perpanjang Gadai',
                html: `
                    <div style="font-weight: 700; margin-bottom: 10px; color: #0F172A; font-size: 18px;">${kodeTrx}</div>
                    
                    <div style="text-align: left; margin-bottom: 15px;">
                        <label style="font-weight: 700; font-size: 14px; color: #334155;">Pilih Durasi (Maks ${maxTambahan} Bulan)</label>
                        <select id="swal-bulan-perpanjang" class="swal-custom-input" style="padding: 10px; width: 100%; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 6px; margin-top: 5px;" onchange="
                            document.getElementById('swal-biaya-display').innerText = formatRp(this.value * ${bungaPerBulan});
                        ">
                            ${optionsHtml}
                        </select>
                    </div>

                    <div style="background: #f8fafc; padding: 12px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #e2e8f0; text-align: left;">
                        <div style="font-size: 13px; color: #64748B;">Total Biaya Perpanjangan:</div>
                        <div id="swal-biaya-display" style="font-size: 20px; font-weight: 700; color: #3b82f6; margin: 4px 0;">${formatRp(bungaPerBulan)}</div>
                    </div>
                    
                    <div style="text-align: left; margin-bottom: 15px; font-size: 14px;">
                        Silakan transfer sesuai nominal di atas ke:<br>
                        <strong>BCA: 1234567890 (a/n Gerlian Jaya)</strong><br>
                        <strong>DANA: 081234567890 (a/n Gerlian Jaya)</strong>
                    </div>

                    <div style="text-align: left;">
                        <label style="font-weight: 700; font-size: 14px; color: #334155;">Upload Bukti Transfer (Wajib)</label>
                        <input id="swal-file-perpanjang" type="file" class="swal-custom-input" accept="image/*" style="padding: 10px; width: 100%; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 6px; margin-top: 5px;">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Kirim Bukti',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3b82f6',
                preConfirm: () => {
                    const fileInput = document.getElementById('swal-file-perpanjang');
                    const bulan = document.getElementById('swal-bulan-perpanjang').value;
                    
                    if (!fileInput.files.length) {
                        Swal.showValidationMessage('Anda wajib mengunggah bukti pembayaran!');
                        return false;
                    }
                    return {
                        file: fileInput.files[0],
                        bulan: bulan,
                        nominal: bulan * bungaPerBulan
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    let data = result.value;
                    let fileInputPerpanjang = document.getElementById('input-file-perpanjang');
                    
                    // Transfer file object to hidden input using DataTransfer
                    let dt = new DataTransfer();
                    dt.items.add(data.file);
                    fileInputPerpanjang.files = dt.files;
                    
                    document.getElementById('input-bulan-perpanjang').value = data.bulan;
                    document.getElementById('input-nominal-perpanjang').value = data.nominal;
                    document.getElementById('form-perpanjang').submit();
                }
            });
        }
    @endif
</script>
@endpush

@endsection
