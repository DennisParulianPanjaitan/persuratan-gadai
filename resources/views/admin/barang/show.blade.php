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
			'pending' => 'Pending',
			'ditolak' => 'Ditolak',
			default => $barang->status_verifikasi,
		};
	@endphp

	<div class="page-shell barang-detail-page">
		<x-page-header
			title="Detail Barang"
			breadcrumb="Dashboard > Master Data > Barang > Detail"
			buttonText="Kembali"
			buttonUrl="{{ route('admin.barang.index') }}"
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

				@if(!in_array($statusValue, ['pending', 'ditolak']))
                    <div class="barang-detail-qr">
                        <div class="barang-detail-qr__box">
                            {!! QrCode::size(190)->margin(1)->generate(route('public.surat_gadai', $barang)) !!}
                        </div>
                        <div class="barang-detail-qr__text">
                            QR ini berisi link Bukti Gadai digital untuk discan oleh Pelanggan.
                        </div>
                        <div class="barang-detail-qr__url">{{ route('public.surat_gadai', $barang) }}</div>
                    </div>
                @endif
			</x-card>

			<div style="display: flex; flex-direction: column; gap: 24px;">
                <x-card>
                    <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-person-badge"></i> Data Pemilik Barang
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
                            <span class="barang-detail-item__label">Keterangan</span>
                            <span class="barang-detail-item__value">{{ $barang->keterangan ?: '-' }}</span>
                        </div>
                    </div>
                </x-card>
            </div>
		</div>
	</div>
@endsection{{-- admin barang show --}}
