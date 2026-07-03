{{-- admin pelanggan index --}}
@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Pelanggan"
			breadcrumb="Dashboard > Master Data > Pelanggan"
			buttonText="+ Tambah Pelanggan"
			buttonUrl="{{ route('admin.pelanggan.create') }}"
		/>

		<x-card>
			<form method="GET" action="{{ url()->current() }}">
				<div class="table-toolbar">
					<div>
						<div class="table-toolbar__title">Filter Data Pelanggan</div>
						<div class="table-toolbar__subtitle">Gunakan filter untuk menyaring data pelanggan dari database.</div>
					</div>

					<div class="table-toolbar__actions">
						<!-- <x-button type="submit" variant="secondary">Filter</x-button>
						<x-button href="{{ url()->current() }}" variant="secondary">Reset</x-button> -->
					</div>
				</div>

				<div class="filter-grid" style="max-width: 2000px;">
					<div class="filter-field filter-field--search">
						<span class="filter-field__label">Pencarian</span>
						<x-search name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." autocomplete="off" />
					</div>
				</div>
			</form>

			<x-table :headers="['ID User', 'Nama', 'No HP', 'Alamat', 'Email', 'Keterangan', 'Aksi']">
				@forelse ($pelangganList as $pelanggan)
					<tr>
						<td>
							@if($pelanggan->id_user)
								<span style="display:inline-block;padding:2px 8px;background:#E2E8F0;border-radius:4px;font-size:12px;font-weight:600;color:#334155;">User #{{ $pelanggan->id_user }}</span>
							@else
								<span style="color:#94A3B8;font-style:italic;font-size:13px;">(tidak terdaftar)</span>
							@endif
						</td>
						<td style="font-weight:600;color:#0F172A;">{{ $pelanggan->nama }}</td>
						<td>{{ $pelanggan->no_hp ?: '-' }}</td>
						<td>{{ $pelanggan->alamat ?: '-' }}</td>
						<td>{{ $pelanggan->email ?: '-' }}</td>
						<td>{{ $pelanggan->keterangan ?: '-' }}</td>
						<td>
							<div style="display:flex;flex-wrap:wrap;gap:8px;">
								<x-button href="{{ route('admin.pelanggan.edit', $pelanggan->id_pelanggan) }}" variant="primary">Edit</x-button>

								<form action="{{ route('admin.pelanggan.destroy', $pelanggan->id_pelanggan) }}" method="POST" class="js-swal-confirm" data-title="Hapus Pelanggan?" data-text="Anda yakin ingin menghapus pelanggan {{ $pelanggan->nama }}? Data ini tidak dapat dikembalikan.">
									@csrf
									@method('DELETE')
									<x-button type="submit" variant="danger">Hapus</x-button>
								</form>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="7" style="text-align:center;padding:28px 18px;color:#64748B;">
							Data pelanggan belum tersedia.
						</td>
					</tr>
				@endforelse
			</x-table>

			<x-pagination :paginator="$pelangganList" />
		</x-card>
	</div>

	@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const searchInput = document.querySelector('input[name="search"]');
			let timer;
			
			if (searchInput) {
				searchInput.addEventListener('input', function() {
					clearTimeout(timer);
					timer = setTimeout(() => {
						const url = new URL(window.location.href);
						url.searchParams.set('search', searchInput.value);
						
						// Update browser URL without reloading
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
							
							// Replace tbody
							const currentTableBody = document.querySelector('.table-responsive tbody');
							const newTableBody = doc.querySelector('.table-responsive tbody');
							if (currentTableBody && newTableBody) {
								currentTableBody.innerHTML = newTableBody.innerHTML;
							}
							
							// Replace pagination if any
							const currentPagination = document.querySelector('nav[role="navigation"]')?.parentElement;
							const newPagination = doc.querySelector('nav[role="navigation"]')?.parentElement;
							if (currentPagination && newPagination) {
							    currentPagination.innerHTML = newPagination.innerHTML;
							}
						});
					}, 300);
				});
			}
		});
	</script>
	@endpush
@endsection
