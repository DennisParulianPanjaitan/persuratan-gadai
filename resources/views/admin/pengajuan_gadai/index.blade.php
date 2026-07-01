@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Pengajuan Gadai"
			breadcrumb="Dashboard > Pengajuan Gadai"
		/>

		<x-card>
			@if (session('success'))
				<div id="flash-success" data-message="{{ session('success') }}"></div>
			@endif

			<form method="GET" action="{{ url()->current() }}">
				<div class="table-toolbar">
					<div>
						<div class="table-toolbar__title">Barang Pending</div>
						<div class="table-toolbar__subtitle">Daftar barang dengan status verifikasi pending untuk diproses menjadi pengajuan gadai.</div>
					</div>

					<div class="table-toolbar__actions">
						<x-button type="submit" variant="secondary">Filter</x-button>
						<x-button href="{{ url()->current() }}" variant="secondary">Reset</x-button>
					</div>
				</div>

				<div class="filter-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
					<label class="filter-field">
						<span class="filter-field__label">Tanggal</span>
						<input type="date" name="tanggal" value="{{ request('tanggal') }}" class="filter-input">
					</label>

					<div class="filter-field filter-field--search">
						<span class="filter-field__label">Pencarian</span>
						<x-search name="search" value="{{ request('search') }}" placeholder="Cari barang pending..." />
					</div>
				</div>
			</form>

			<x-table :headers="['Foto', 'Barang & Pengaju', 'Jenis', 'Harga', 'Status', 'Aksi']">
				@forelse ($barangList as $barang)
					<tr>
						<td>
							@if ($barang->foto_barang)
								@php
									$fotoBarang = preg_match('/^https?:\/\//', $barang->foto_barang)
										? $barang->foto_barang
										: asset('storage/' . ltrim($barang->foto_barang, '/'));
								@endphp
								<img src="{{ $fotoBarang }}" alt="Foto" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #E2E8F0;">
							@else
								<span style="color:#94a3b8; font-style:italic;">Tidak ada</span>
							@endif
						</td>
						<td>
							<div style="font-weight:600;color:#0F172A;font-size:15px;">{{ $barang->nama_barang }}</div>
							<div style="font-size:13px;color:#64748B; margin-top:2px;">
                                <i class="bi bi-person-fill"></i> {{ $barang->pelanggan ? $barang->pelanggan->nama : 'Offline (Toko)' }}
                            </div>
							<div style="font-size:12px;color:#94a3b8; margin-top:2px;">{{ $barang->keterangan ?: '-' }}</div>
						</td>
						<td>{{ $barang->jenisBarang->nama_jenis ?? '-' }}</td>
						<td>{{ 'Rp ' . number_format($barang->harga_beli, 0, ',', '.') }}</td>
						<td>
							<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-700">
								Pending
							</span>
						</td>
						<td>
							<div style="display:flex;flex-wrap:wrap;gap:8px;">
								<x-button href="{{ route('admin.barang.show', $barang) }}" variant="secondary">Lihat</x-button>
								<form method="POST"
								      action="{{ route('admin.pengajuan_gadai.terima', $barang->id_barang) }}"
								      class="js-swal-confirm-price"
								      data-title="Terima Barang Gadai"
								      data-confirm="Ya, Terima"
								      data-cancel="Batal"
								      data-harga-beli="{{ $barang->harga_beli }}"
								      data-id-barang="{{ $barang->id_barang }}"
								      data-nama-barang="{{ $barang->nama_barang }}">
									@csrf
									@method('PATCH')
									<input type="hidden" name="harga_gadai" class="js-harga-gadai-input">
									<button type="submit" class="button button--primary">Terima</button>
								</form>
								<form method="POST" action="{{ route('admin.pengajuan_gadai.tolak', $barang->id_barang) }}" class="js-swal-tolak-pengajuan" data-title="Tolak barang ini?" data-confirm="Tolak" data-cancel="Batal">
									@csrf
									@method('PATCH')
									<input type="hidden" name="alasan_penolakan" class="js-alasan-penolakan-input">
									<button type="submit" class="button button--danger">Tolak</button>
								</form>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="6" style="text-align:center;padding:28px 18px;color:#64748B;">
							Tidak ada barang dengan status pending.
						</td>
					</tr>
				@endforelse
			</x-table>

			<x-pagination :paginator="$barangList" />
		</x-card>
	</div>

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
@endsection
