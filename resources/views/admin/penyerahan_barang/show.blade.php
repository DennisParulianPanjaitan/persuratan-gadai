@extends('layouts.admin')

@push('styles')
<style>
	.proses-grid {
		display: grid;
		grid-template-columns: 300px 1fr;
		gap: 24px;
		margin-bottom: 30px;
	}
	.action-buttons-wrapper {
		display: flex;
		flex-wrap: wrap;
		gap: 12px;
		align-items: flex-end;
	}
	.form-aksi-container {
		display: flex; 
		gap: 12px; 
		align-items: flex-end;
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
	.swal-custom-input:focus {
		outline: none;
		border-color: #059669;
		box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
	}
	@media (max-width: 768px) {
		.proses-grid {
			grid-template-columns: 1fr;
		}
		.action-buttons-wrapper {
			flex-direction: column;
			align-items: stretch;
		}
		.form-aksi-container {
			flex-direction: column;
			align-items: stretch;
		}
		.form-aksi-container > div,
		.form-aksi-container select, 
		.btn-terima,
		.btn-tolak {
			width: 100% !important;
		}
	}
</style>
@endpush

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Proses Transaksi Gadai"
			breadcrumb="Dashboard > Penyerahan Barang > Proses Transaksi"
		/>

		<x-card>
			<div class="table-toolbar">
				<div>
					<div class="table-toolbar__title">Detail Barang Fisik</div>
					<div class="table-toolbar__subtitle">Cocokkan detail barang fisik yang dibawa pelanggan dengan data pengajuan online ini.</div>
				</div>
			</div>

			<div class="proses-grid">
				<!-- Foto Barang -->
				<div>
					@php
						$currentFoto = $barang->foto_barang 
							? (preg_match('/^https?:\/\//', $barang->foto_barang) ? $barang->foto_barang : asset('storage/' . ltrim($barang->foto_barang, '/'))) 
							: null;
					@endphp
					@if($currentFoto)
						<img src="{{ $currentFoto }}" alt="Foto {{ $barang->nama_barang }}" style="width: 100%; height: 300px; object-fit: cover; border-radius: 16px; border: 1px solid #E2E8F0;">
					@else
						<div style="width: 100%; height: 300px; background-color: #F8FAFC; border-radius: 16px; border: 1px dashed #CBD5E1; display: flex; align-items: center; justify-content: center; color: #94A3B8;">
							Tidak Ada Foto
						</div>
					@endif
				</div>

				<!-- Detail Informasi -->
				<div>
					<div style="font-size: 24px; font-weight: 700; color: #0F172A; margin-bottom: 8px;">
						{{ $barang->nama_barang }}
					</div>
					
					<div style="display: flex; gap: 12px; margin-bottom: 24px;">
						<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700">
							Harga ACC: Rp {{ number_format($barang->harga_gadai_sementara, 0, ',', '.') }}
						</span>
						<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700">
							Jenis: {{ $barang->jenisBarang->nama_jenis ?? '-' }}
						</span>
						@if($barang->berat)
							<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-slate-100 text-slate-700">
								Berat: {{ $barang->berat }} gram
							</span>
						@endif
					</div>

					<div style="margin-bottom: 24px; background-color: #F8FAFC; border-radius: 12px; padding: 16px; border: 1px solid #F1F5F9;">
						<div style="font-size: 13px; color: #64748B; margin-bottom: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Informasi Pelanggan (Pemilik)</div>
						<div style="color: #334155; line-height: 1.6;">
							@if($barang->pelanggan)
								Nama: <b>{{ $barang->pelanggan->nama }}</b><br>
								No. HP: <b>{{ $barang->pelanggan->no_hp }}</b><br>
								Alamat: <b>{{ $barang->pelanggan->alamat ?? '-' }}</b>
							@else
								<span style="color: #EF4444; font-weight: 600;">Data pelanggan tidak ditemukan. (Barang ini tidak memiliki pemilik valid).</span>
							@endif
						</div>
					</div>

					<div style="margin-bottom: 24px; background-color: #F8FAFC; border-radius: 12px; padding: 16px; border: 1px solid #F1F5F9;">
						<div style="font-size: 13px; color: #64748B; margin-bottom: 4px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Keterangan/Kondisi</div>
						<div style="color: #334155; line-height: 1.6;">
							Kondisi: <b>{{ $barang->kondisi ?? 'Tidak disebutkan' }}</b><br>
							{{ $barang->keterangan ?? 'Tidak ada catatan tambahan.' }}
						</div>
					</div>

					<!-- Form Aksi -->
					<div style="border-top: 1px solid #E2E8F0; padding-top: 24px;">
						<div style="font-size: 16px; font-weight: 700; color: #0F172A; margin-bottom: 16px;">Tentukan Tindakan</div>
						
						<div class="action-buttons-wrapper">
							<!-- Form Tolak -->
							<form method="POST" action="{{ route('admin.penyerahan_barang.tolak', $barang) }}" class="js-swal-confirm" data-title="Tolak Transaksi?" data-text="Barang fisik tidak sesuai? Status akan diubah menjadi Ditolak dan dibatalkan." data-confirm="Ya, Batalkan" data-cancel="Kembali">
								@csrf
								@method('PATCH')
								<x-button type="submit" variant="danger" class="btn-tolak">Tolak Barang Fisik</x-button>
							</form>

							<!-- Form Terima -->
							<form method="POST" action="{{ route('admin.penyerahan_barang.terima', $barang) }}" id="form-terima">
								@csrf
								@method('PATCH')
								
								<div class="form-aksi-container">
									<input type="hidden" name="uang_pinjaman" id="input_uang_pinjaman">
									<input type="hidden" name="tanggal_jatuh_tempo" id="input_tanggal_jatuh_tempo">

									<button type="button" class="button button--primary btn-terima" onclick="showGadaiAlert()">Terima & Proses Transaksi</button>
								</div>

							@error('uang_pinjaman')
								<div style="color: #E11D48; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
							@enderror
							@error('tanggal_jatuh_tempo')
								<div style="color: #E11D48; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
							@enderror
							</form>
						</div>
					</div>

				</div>
			</div>
		</x-card>
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
        title: 'Detail Transaksi Gadai',
        html: `
            <div style="text-align: left; margin-top: 10px;">
                <label style="font-weight: 600; font-size: 14px; color: #334155;">Uang Pinjaman Gadai (Rp)</label>
                <input id="swal-uang" class="swal-custom-input" type="number" value="{{ $barang->harga_gadai_sementara }}" min="1">
            </div>
            <div style="text-align: left; margin-top: 15px;">
                <label style="font-weight: 600; font-size: 14px; color: #334155;">Tanggal Jatuh Tempo (Default 4 Bulan)</label>
                <input id="swal-tanggal" class="swal-custom-input" type="date" value="${dateString}">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Simpan Transaksi',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#059669',
        preConfirm: () => {
            const uang = document.getElementById('swal-uang').value;
            const tanggal = document.getElementById('swal-tanggal').value;
            
            if (!uang || !tanggal) {
                Swal.showValidationMessage('Uang Pinjaman dan Tanggal Jatuh Tempo wajib diisi!');
                return false;
            }
            if (uang <= 0) {
                Swal.showValidationMessage('Uang Pinjaman harus lebih dari Rp 0!');
                return false;
            }
            
            return { uang, tanggal };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Pindahkan nilai dari SweetAlert ke form hidden
            document.getElementById('input_uang_pinjaman').value = result.value.uang;
            document.getElementById('input_tanggal_jatuh_tempo').value = result.value.tanggal;
            
            // Submit form
            document.getElementById('form-terima').submit();
        }
    });
}
</script>
@endpush