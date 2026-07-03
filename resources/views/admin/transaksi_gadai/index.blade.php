@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Transaksi Gadai"
			breadcrumb="Dashboard > Transaksi Gadai"
		/>

		<x-card>
			@if (session('success'))
				<div id="flash-success" data-message="{{ session('success') }}"></div>
			@endif
            @if (session('error'))
				<div id="flash-error" data-message="{{ session('error') }}"></div>
			@endif

			<form method="GET" action="{{ url()->current() }}">
				<div class="table-toolbar">
					<div>
						<div class="table-toolbar__title">Daftar Transaksi Aktif</div>
						<div class="table-toolbar__subtitle">Kelola dan tebus barang yang sedang digadai.</div>
					</div>
					<div class="table-toolbar__actions">
					</div>
				</div>

				<div class="filter-grid" style="grid-template-columns: max-content 400px; gap: 16px;">
					<label class="filter-field">
						<span class="filter-field__label">Tanggal Gadai</span>
						<input type="date" name="tanggal" value="{{ request('tanggal') }}" class="filter-input" style="padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; background: #fff; min-width: 200px; width: 100%;">
					</label>

					<div class="filter-field filter-field--search">
						<span class="filter-field__label">Pencarian</span>
						<x-search name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi, pelanggan, atau barang..." />
					</div>
				</div>
			</form>

			<x-table :headers="['Kode Transaksi', 'Pelanggan', 'Barang', 'Uang Pinjaman', 'Jatuh Tempo', 'Aksi']">
				@forelse ($transaksiList as $trx)
					<tr>
						<td>
                            <div style="font-weight:600;color:#0F172A;">{{ $trx->kode_transaksi }}</div>
                            <div style="font-size:12px;color:#64748B;">{{ \Carbon\Carbon::parse($trx->tanggal_gadai)->format('d M Y') }}</div>
                        </td>
						<td>{{ $trx->pelanggan->nama ?? '-' }}</td>
						<td>{{ $trx->barang->nama_barang ?? '-' }}</td>
						<td>{{ 'Rp ' . number_format($trx->uang_pinjaman, 0, ',', '.') }}</td>
						<td>
                            @php
                                $jatuhTempo = \Carbon\Carbon::parse($trx->tanggal_jatuh_tempo);
                                $isTerlewat = $jatuhTempo->isPast() && !$jatuhTempo->isToday();
                            @endphp
							<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $isTerlewat ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
								{{ $jatuhTempo->format('d M Y') }}
							</span>
						</td>
						<td>
							<div style="display:flex;flex-wrap:wrap;gap:8px;">
								<x-button href="{{ route('admin.barang.show', $trx->barang) }}" variant="secondary">Lihat</x-button>
                                
                                @if($isTerlewat)
                                    <button type="button" class="button button--danger" onclick="showJualAlert('{{ $trx->id_transaksi_gadai }}')">Jual</button>
                                    
                                    <form id="form-jual-{{ $trx->id_transaksi_gadai }}" method="POST" action="{{ route('admin.transaksi_gadai.jual', $trx) }}" style="display: none;">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                @else
                                    <button type="button" class="button" style="background-color: #F59E0B; color: white;" onclick="showPerpanjangAlert('{{ $trx->id_transaksi_gadai }}', '{{ $trx->kode_transaksi }}', {{ $trx->uang_pinjaman }}, '{{ $trx->tanggal_jatuh_tempo }}')">Perpanjang</button>
                                    
                                    <form id="form-perpanjang-{{ $trx->id_transaksi_gadai }}" method="POST" action="{{ route('admin.transaksi_gadai.perpanjang', $trx) }}" style="display: none;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="tambahan_bulan" id="input-bulan-{{ $trx->id_transaksi_gadai }}">
                                        <input type="hidden" name="biaya_perpanjangan" id="input-biaya-{{ $trx->id_transaksi_gadai }}">
                                    </form>

                                    <button type="button" class="button button--primary" onclick="showTebusAlert('{{ $trx->id_transaksi_gadai }}', '{{ $trx->kode_transaksi }}', {{ $trx->uang_pinjaman }}, '{{ $trx->tanggal_gadai }}')">Tebus</button>
                                    
                                    <form id="form-tebus-{{ $trx->id_transaksi_gadai }}" method="POST" action="{{ route('admin.transaksi_gadai.tebus', $trx) }}" style="display: none;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="total_ditebus" id="input-total-{{ $trx->id_transaksi_gadai }}">
                                    </form>
                                @endif
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="6" style="text-align:center;padding:24px;color:#64748B;">Tidak ada transaksi aktif.</td>
					</tr>
				@endforelse
			</x-table>
			
			<x-pagination :paginator="$transaksiList" />
		</x-card>
	</div>
@endsection

@push('styles')
<style>
	.swal-tebus-grid {
		display: grid;
		grid-template-columns: 140px 1fr;
		gap: 8px;
		text-align: left;
		margin-top: 15px;
		font-size: 14px;
		color: #334155;
	}
	.swal-tebus-grid > div {
		padding: 4px 0;
	}
	.swal-tebus-label {
		font-weight: 600;
	}
    .swal-custom-input {
		width: 100%;
		box-sizing: border-box;
		margin-top: 8px;
		padding: 10px 14px;
		border: 1px solid #CBD5E1;
		border-radius: 8px;
		font-size: 15px;
		font-family: inherit;
	}
</style>
@endpush

@push('scripts')
<script>
function showTebusAlert(id, kode, uangPinjaman, tanggalGadaiStr) {
    let now = new Date();
    now.setHours(0,0,0,0);
    let tanggalGadai = new Date(tanggalGadaiStr);
    tanggalGadai.setHours(0,0,0,0);
    
    // Hitung selisih hari
    const msPerDay = 1000 * 60 * 60 * 24;
    let daysDiff = Math.floor((now - tanggalGadai) / msPerDay);
    if (daysDiff < 0) daysDiff = 0; // Jaga-jaga jika tanggal gadai di masa depan
    
    let totalBunga = 0;
    let labelBulan = "";

    if (daysDiff <= 15) {
        totalBunga = Math.round(uangPinjaman * 0.05);
        labelBulan = daysDiff + " Hari (Bunga 5%)";
    } else {
        // Hitung bulan penuh yang sudah berlalu
        let monthsDiff = (now.getFullYear() - tanggalGadai.getFullYear()) * 12;
        monthsDiff -= tanggalGadai.getMonth();
        monthsDiff += now.getMonth();
        
        if (now.getDate() < tanggalGadai.getDate()) {
            monthsDiff--;
        }
        
        let finalMonths = Math.max(1, monthsDiff);
        
        totalBunga = Math.round(uangPinjaman * 0.075 * finalMonths);
        labelBulan = finalMonths + " Bulan (7.5%/bln)";
    }

    let totalBayar = uangPinjaman + totalBunga;

    let formatRp = (angka) => 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);

    Swal.fire({
        title: 'Tebus Gadai',
        html: `
            <div style="font-weight: 700; margin-bottom: 10px; color: #0F172A; font-size: 18px;">${kode}</div>
            <div class="swal-tebus-grid">
                <div class="swal-tebus-label">Uang Pinjaman:</div>
                <div>${formatRp(uangPinjaman)}</div>
                
                <div class="swal-tebus-label">Durasi/Rentang:</div>
                <div>${labelBulan}</div>
                
                <div class="swal-tebus-label">Total Bunga:</div>
                <div>${formatRp(totalBunga)}</div>
            </div>
            
            <hr style="border:0; border-top: 1px solid #E2E8F0; margin: 15px 0;">
            
            <div style="text-align: left;">
                <label style="font-weight: 700; font-size: 14px; color: #059669;">Total Akhir Ditebus (Rp)</label>
                <input id="swal-total" class="swal-custom-input" type="number" value="${totalBayar}" min="${uangPinjaman}">
                <div style="font-size: 12px; color: #64748B; margin-top: 4px;">*Nilai dapat disesuaikan manual.</div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Tebus Sekarang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#059669',
        preConfirm: () => {
            const total = document.getElementById('swal-total').value;
            if (!total || total < uangPinjaman) {
                Swal.showValidationMessage('Total tebus tidak boleh kurang dari uang pinjaman utama!');
                return false;
            }
            return total;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('input-total-' + id).value = result.value;
            document.getElementById('form-tebus-' + id).submit();
        }
    });
}

function showPerpanjangAlert(id, kode, uangPinjaman, tanggalJatuhTempoStr) {
    let now = new Date();
    now.setHours(0,0,0,0);
    
    let jatuhTempo = new Date(tanggalJatuhTempoStr);
    
    // Hitung sisa bulan dari HARI INI ke JATUH TEMPO
    let remainingMonths = (jatuhTempo.getFullYear() - now.getFullYear()) * 12;
    remainingMonths -= now.getMonth();
    remainingMonths += jatuhTempo.getMonth();
    
    // Karena batas rentang maksimal ke depan adalah 4 bulan,
    // maka maksimal tambahan adalah 4 dikurangi sisa bulan saat ini.
    let maxTambahan = 4 - remainingMonths;
    
    if (maxTambahan <= 0) {
        Swal.fire({
            title: 'Belum Waktunya / Batas Maksimal!',
            text: 'Sisa waktu gadai saat ini masih penuh (4 bulan). Anda harus menunggu setidaknya 1 bulan berlalu untuk bisa memperpanjangnya lagi.',
            icon: 'error'
        });
        return;
    }

    let formatRp = (angka) => 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    let bungaPerBulan = Math.round(uangPinjaman * 0.075);
    
    let optionsHtml = '';
    for (let i = 1; i <= maxTambahan; i++) {
        optionsHtml += `<option value="${i}">${i} Bulan</option>`;
    }

    Swal.fire({
        title: 'Perpanjang Gadai',
        html: `
            <div style="font-weight: 700; margin-bottom: 10px; color: #0F172A; font-size: 18px;">${kode}</div>
            <div style="font-size: 13px; color: #64748B; margin-bottom: 15px;">Uang Pinjaman: ${formatRp(uangPinjaman)}</div>
            
            <div style="text-align: left; margin-bottom: 15px;">
                <label style="font-weight: 700; font-size: 14px; color: #334155;">Tambah Berapa Bulan? (Maks ${maxTambahan})</label>
                <select id="swal-bulan" class="swal-custom-input" onchange="document.getElementById('swal-biaya').value = this.value * ${bungaPerBulan}">
                    ${optionsHtml}
                </select>
            </div>
            
            <div style="text-align: left;">
                <label style="font-weight: 700; font-size: 14px; color: #059669;">Biaya Bunga yang Dibayar (Rp)</label>
                <input id="swal-biaya" class="swal-custom-input" type="number" value="${bungaPerBulan}" min="0">
                <div style="font-size: 12px; color: #64748B; margin-top: 4px;">*Dihitung otomatis (7.5%/bln), tapi bisa diubah sesuai kesepakatan final.</div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Perpanjang Sekarang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#F59E0B',
        preConfirm: () => {
            const bulan = document.getElementById('swal-bulan').value;
            const biaya = document.getElementById('swal-biaya').value;
            
            if (!bulan || !biaya || biaya < 0) {
                Swal.showValidationMessage('Pastikan jumlah bulan dan biaya valid!');
                return false;
            }
            return { bulan, biaya };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('input-bulan-' + id).value = result.value.bulan;
            document.getElementById('input-biaya-' + id).value = result.value.biaya;
            document.getElementById('form-perpanjang-' + id).submit();
        }
    });
}

function showJualAlert(id) {
    Swal.fire({
        title: 'Jual Barang?',
        text: "Apakah Anda yakin ingin menjual/melelang barang yang sudah lewat jatuh temponya ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E11D48',
        cancelButtonColor: '#64748B',
        confirmButtonText: 'Ya, Jual Barang',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-jual-' + id).submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const dateInput = document.querySelector('input[name="tanggal"]');
    let timer;
    
    function fetchFilteredData() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const url = new URL(window.location.href);
            url.searchParams.set('search', searchInput.value);
            url.searchParams.set('tanggal', dateInput.value);
            
            window.history.pushState({}, '', url);
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const currentTableBody = document.querySelector('.table-responsive tbody');
                const newTableBody = doc.querySelector('.table-responsive tbody');
                if (currentTableBody && newTableBody) {
                    currentTableBody.innerHTML = newTableBody.innerHTML;
                }
                
                const currentPagination = document.querySelector('nav[role="navigation"]')?.parentElement;
                const newPagination = doc.querySelector('nav[role="navigation"]')?.parentElement;
                if (currentPagination && newPagination) {
                    currentPagination.innerHTML = newPagination.innerHTML;
                } else if (currentPagination && !newPagination) {
                    currentPagination.innerHTML = '';
                }
            });
        }, 300);
    }

    if (searchInput) {
        searchInput.addEventListener('input', fetchFilteredData);
    }

    if (dateInput) {
        dateInput.addEventListener('change', fetchFilteredData);
    }
});
</script>
@endpush
