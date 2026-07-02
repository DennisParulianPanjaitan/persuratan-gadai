@extends('layouts.admin')

@section('content')
	@php
		$fotoBarang = null;

		if ($barang->foto_barang) {
			$fotoBarang = preg_match('/^https?:\/\//', $barang->foto_barang)
				? $barang->foto_barang
				: asset('storage/' . ltrim($barang->foto_barang, '/'));
		}

		$statusValue = strtolower($barang->status_verifikasi);

		$statusClass = match ($statusValue) {
			'terverifikasi' => 'barang-status barang-status--success',
			'pending' => 'barang-status barang-status--warning',
			default => 'barang-status barang-status--danger',
		};

		$statusLabel = match ($statusValue) {
			'terverifikasi' => 'Terverifikasi',
			'pending' => 'Menunggu Verifikasi',
			'ditolak' => 'Ditolak',
			default => $barang->status_verifikasi,
		};
	@endphp

	<div class="page-shell barang-detail-page">
		<x-page-header
			title="Detail Pengajuan Gadai"
			breadcrumb="Dashboard > Pengajuan Gadai > Detail"
			buttonText="Kembali"
			buttonUrl="{{ route('admin.pengajuan_gadai.index') }}"
		/>

		<div class="barang-detail-grid">
			<x-card>
				<div class="barang-detail-figure">
					@if ($fotoBarang)
						<img src="{{ $fotoBarang }}" alt="Foto {{ $barang->nama_barang }}" class="barang-detail-image">
					@else
						<div class="barang-detail-image barang-detail-image--empty">
							Tidak ada foto
						</div>
					@endif

					<div class="barang-detail-figure__meta">
						<div class="barang-detail-figure__title">{{ $barang->nama_barang }}</div>
						<div class="barang-detail-figure__subtitle">{{ $barang->jenisBarang->nama_jenis ?? '-' }}</div>
					</div>
				</div>

                @if($statusValue === 'pending')
                    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0; display: flex; gap: 12px; flex-direction: column;">
                        <form method="POST"
                              action="{{ route('admin.pengajuan_gadai.terima', $barang->id_barang) }}"
                              class="js-swal-confirm-price"
                              data-title="Terima Barang Gadai"
                              data-confirm="Ya, Terima"
                              data-cancel="Batal"
                              data-harga-beli="{{ $barang->harga_beli }}"
                              data-id-barang="{{ $barang->id_barang }}"
                              data-nama-barang="{{ $barang->nama_barang }}"
                              style="width: 100%;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="harga_gadai" class="js-harga-gadai-input">
                            <button type="submit" class="button button--primary" style="width: 100%; justify-content: center;">Terima Pengajuan</button>
                        </form>
                        
                        <form method="POST" 
                              action="{{ route('admin.pengajuan_gadai.tolak', $barang->id_barang) }}" 
                              class="js-swal-tolak-pengajuan" 
                              data-title="Tolak barang ini?" 
                              data-confirm="Tolak" 
                              data-cancel="Batal"
                              style="width: 100%;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="alasan_penolakan" class="js-alasan-penolakan-input">
                            <button type="submit" class="button button--danger" style="width: 100%; justify-content: center;">Tolak</button>
                        </form>
                    </div>
                @endif
			</x-card>

            <div style="display: flex; flex-direction: column; gap: 24px;">
                <x-card>
                    <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-person-badge"></i> Data Pengaju / Pelanggan
                    </h3>
                    <div class="barang-detail-list">
                        <div class="barang-detail-item">
                            <span class="barang-detail-item__label">Nama Lengkap</span>
                            <span class="barang-detail-item__value">{{ $barang->pelanggan->nama ?? '-' }}</span>
                        </div>
                        <div class="barang-detail-item">
                            <span class="barang-detail-item__label">No KTP / NIK</span>
                            <span class="barang-detail-item__value">{{ $barang->pelanggan->no_ktp ?? '-' }}</span>
                        </div>
                        <div class="barang-detail-item">
                            <span class="barang-detail-item__label">No Handphone</span>
                            <span class="barang-detail-item__value">{{ $barang->pelanggan->no_hp ?? '-' }}</span>
                        </div>
                        <div class="barang-detail-item barang-detail-item--full">
                            <span class="barang-detail-item__label">Alamat</span>
                            <span class="barang-detail-item__value">{{ $barang->pelanggan->alamat ?? '-' }}</span>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-box-seam"></i> Data Barang
                    </h3>
                    <div class="barang-detail-list">
                        <div class="barang-detail-item">
                            <span class="barang-detail-item__label">ID Barang</span>
                            <span class="barang-detail-item__value">{{ $barang->id_barang }}</span>
                        </div>

                        <div class="barang-detail-item">
                            <span class="barang-detail-item__label">Jenis Barang</span>
                            <span class="barang-detail-item__value">{{ $barang->jenisBarang->nama_jenis ?? '-' }}</span>
                        </div>

                        <div class="barang-detail-item">
                            <span class="barang-detail-item__label">Harga Beli</span>
                            <span class="barang-detail-item__value">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</span>
                        </div>

                        <div class="barang-detail-item">
                            <span class="barang-detail-item__label">Kondisi</span>
                            <span class="barang-detail-item__value">{{ $barang->kondisi ?: '-' }}</span>
                        </div>

                        <div class="barang-detail-item">
                            <span class="barang-detail-item__label">Berat</span>
                            <span class="barang-detail-item__value">{{ $barang->berat ?: '-' }}</span>
                        </div>

                        <div class="barang-detail-item">
                            <span class="barang-detail-item__label">Status Verifikasi</span>
                            <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>

                        <div class="barang-detail-item barang-detail-item--full">
                            <span class="barang-detail-item__label">Keterangan Tambahan</span>
                            <span class="barang-detail-item__value">{{ $barang->keterangan ?: '-' }}</span>
                        </div>
                    </div>
                </x-card>
            </div>
		</div>
	</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tolakForms = document.querySelectorAll('.js-swal-tolak-pengajuan');
        
        tolakForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const title = this.dataset.title;
                const confirmText = this.dataset.confirm;
                const cancelText = this.dataset.cancel;
                
                Swal.fire({
                    title: title,
                    text: "Silakan masukkan alasan penolakan:",
                    input: 'textarea',
                    inputPlaceholder: 'Contoh: Barang cacat, Tidak memenuhi syarat...',
                    inputAttributes: {
                        'aria-label': 'Alasan penolakan'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Alasan penolakan tidak boleh kosong!'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.querySelector('.js-alasan-penolakan-input').value = result.value;
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
