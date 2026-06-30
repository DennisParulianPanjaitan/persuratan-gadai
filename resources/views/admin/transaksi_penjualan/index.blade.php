@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Transaksi Penjualan (Lelang)"
			breadcrumb="Dashboard > Transaksi Penjualan"
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
						<div class="table-toolbar__title">Barang Siap Dijual</div>
						<div class="table-toolbar__subtitle">Barang gadai gagal tebus yang siap dilelang.</div>
					</div>
					<div class="table-toolbar__actions">
						<x-button type="submit" variant="secondary">Filter</x-button>
						<x-button href="{{ url()->current() }}" variant="secondary">Reset</x-button>
					</div>
				</div>

				<div class="filter-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
					<label class="filter-field">
						<span class="filter-field__label">Tanggal Jual / Update</span>
						<input type="date" name="tanggal" value="{{ request('tanggal') }}" class="filter-input">
					</label>

					<div class="filter-field filter-field--search">
						<span class="filter-field__label">Pencarian</span>
						<x-search name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi, pelanggan, atau barang..." />
					</div>
				</div>
			</form>

			<x-table :headers="['Kode Gadai', 'Pelanggan', 'Barang', 'Uang Pinjaman', 'Aksi']">
				@forelse ($siapJualList as $trx)
					<tr>
						<td>
                            <div style="font-weight:600;color:#0F172A;">{{ $trx->kode_transaksi }}</div>
                            <div style="font-size:12px;color:#64748B;">Jatuh tempo: {{ \Carbon\Carbon::parse($trx->tanggal_jatuh_tempo)->format('d M Y') }}</div>
                        </td>
						<td>{{ $trx->pelanggan->nama ?? '-' }}</td>
						<td>{{ $trx->barang->nama_barang ?? '-' }}</td>
						<td>{{ 'Rp ' . number_format($trx->uang_pinjaman, 0, ',', '.') }}</td>
						<td>
							<div style="display:flex;flex-wrap:wrap;gap:8px;">
								<x-button href="{{ route('admin.barang.show', $trx->barang) }}" variant="secondary">Lihat</x-button>
								<button type="button" class="button button--danger" onclick="showJualAlert('{{ $trx->id_transaksi_gadai }}', '{{ $trx->kode_transaksi }}', {{ $trx->uang_pinjaman }}, '{{ addslashes($trx->barang->nama_barang) }}')">Proses Jual</button>
                                
                                <form id="form-jual-{{ $trx->id_transaksi_gadai }}" method="POST" action="{{ route('admin.transaksi_penjualan.store') }}" style="display: none;">
                                    @csrf
                                    <input type="hidden" name="id_transaksi_gadai" value="{{ $trx->id_transaksi_gadai }}">
                                    <input type="hidden" name="harga_jual" id="input-harga-{{ $trx->id_transaksi_gadai }}">
                                </form>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="5" style="text-align:center;padding:24px;color:#64748B;">Tidak ada barang yang siap dijual.</td>
					</tr>
				@endforelse
			</x-table>
			
			<x-pagination :paginator="$siapJualList" />
            
            <div style="margin-top: 40px;"></div>

            <div class="table-toolbar">
				<div>
					<div class="table-toolbar__title">Riwayat Sudah Terjual</div>
					<div class="table-toolbar__subtitle">Daftar barang lelang yang sudah sukses terjual.</div>
				</div>
			</div>

			<x-table :headers="['Tanggal Jual', 'Barang', 'Modal (Pinjaman)', 'Harga Jual', 'Laba/Rugi']">
				@forelse ($sudahTerjualList as $jual)
					<tr>
						<td>{{ \Carbon\Carbon::parse($jual->tanggal_jual)->format('d M Y') }}</td>
						<td>
                            <div style="font-weight:600;color:#0F172A;">{{ $jual->transaksiGadai->barang->nama_barang ?? '-' }}</div>
                            <div style="font-size:12px;color:#64748B;">Ex: {{ $jual->transaksiGadai->pelanggan->nama ?? '-' }}</div>
                        </td>
						<td>{{ 'Rp ' . number_format($jual->transaksiGadai->uang_pinjaman ?? 0, 0, ',', '.') }}</td>
						<td>
                            <span style="font-weight:600; color:#059669;">{{ 'Rp ' . number_format($jual->harga_jual, 0, ',', '.') }}</span>
                        </td>
						<td>
                            @if($jual->laba_rugi > 0)
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    + Rp {{ number_format($jual->laba_rugi, 0, ',', '.') }}
                                </span>
                            @elseif($jual->laba_rugi < 0)
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-red-100 text-red-700">
                                    - Rp {{ number_format(abs($jual->laba_rugi), 0, ',', '.') }}
                                </span>
                            @else
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700">
                                    Rp 0 (BEP)
                                </span>
                            @endif
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="5" style="text-align:center;padding:24px;color:#64748B;">Belum ada riwayat penjualan.</td>
					</tr>
				@endforelse
			</x-table>
			
			<x-pagination :paginator="$sudahTerjualList" />
		</x-card>
	</div>
@endsection

@push('styles')
<style>
	.swal-jual-grid {
		display: grid;
		grid-template-columns: 140px 1fr;
		gap: 8px;
		text-align: left;
		margin-top: 15px;
		font-size: 14px;
		color: #334155;
	}
	.swal-jual-grid > div {
		padding: 4px 0;
	}
	.swal-jual-label {
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
function showJualAlert(id, kode, uangPinjaman, namaBarang) {
    // Rumus 150% (1.5x dari modal pinjaman awal)
    let hargaDefault = Math.round(uangPinjaman * 1.5);
    
    let formatRp = (angka) => 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);

    Swal.fire({
        title: 'Proses Jual/Lelang',
        html: `
            <div style="font-weight: 700; margin-bottom: 5px; color: #0F172A; font-size: 18px;">${namaBarang}</div>
            <div style="font-size: 13px; color: #64748B;">Eks. Transaksi: ${kode}</div>
            
            <div class="swal-jual-grid">
                <div class="swal-jual-label">Modal Pinjaman:</div>
                <div style="color: #E11D48; font-weight: 600;">${formatRp(uangPinjaman)}</div>
                
                <div class="swal-jual-label">Rekomendasi (150%):</div>
                <div style="color: #059669; font-weight: 600;">${formatRp(hargaDefault)}</div>
            </div>
            
            <hr style="border:0; border-top: 1px solid #E2E8F0; margin: 15px 0;">
            
            <div style="text-align: left;">
                <label style="font-weight: 700; font-size: 14px; color: #059669;">Harga Laku (Rp)</label>
                <input id="swal-harga" class="swal-custom-input" type="number" value="${hargaDefault}">
                <div style="font-size: 12px; color: #64748B; margin-top: 4px;">*Angka rekomendasi sudah diset 150%. Ubah jika harga kesepakatan final berbeda.</div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Jual Sekarang',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#E11D48',
        preConfirm: () => {
            const harga = document.getElementById('swal-harga').value;
            if (!harga || harga <= 0) {
                Swal.showValidationMessage('Harga jual harus lebih dari Rp 0!');
                return false;
            }
            return harga;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('input-harga-' + id).value = result.value;
            document.getElementById('form-jual-' + id).submit();
        }
    });
}
</script>
@endpush
