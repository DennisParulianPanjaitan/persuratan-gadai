@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Pengajuan Gadai"
			breadcrumb="Dashboard > Pengajuan Gadai"
			buttonText="+ Transaksi Baru"
			buttonUrl="#"
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

			<x-table :headers="['Foto', 'Nama Barang', 'Jenis', 'Harga', 'Status', 'Aksi']">
				@forelse ($barangList as $barang)
					<tr>
						<td>{{ $barang->foto_barang ?: '-' }}</td>
						<td>
							<div style="font-weight:600;color:#0F172A;">{{ $barang->nama_barang }}</div>
							<div style="font-size:12px;color:#64748B;">{{ $barang->keterangan ?: '-' }}</div>
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
								<x-button href="#" variant="secondary">Lihat</x-button>
								<form method="POST" action="{{ route('admin.pengajuan-gadai.terima', $barang->id_barang) }}" class="js-swal-confirm" data-title="Terima barang ini?" data-text="Status verifikasi akan berubah menjadi Terverifikasi." data-confirm="Ya, terima" data-cancel="Batal">
									@csrf
									@method('PATCH')
									<button type="submit" class="button button--primary">Terima</button>
								</form>
								<form method="POST" action="{{ route('admin.pengajuan-gadai.tolak', $barang->id_barang) }}" class="js-swal-confirm" data-title="Tolak barang ini?" data-text="Status verifikasi akan berubah menjadi Ditolak." data-confirm="Ya, tolak" data-cancel="Batal">
									@csrf
									@method('PATCH')
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

			<div class="pagination-bar">
				<div class="pagination-bar__info">
					Menampilkan <strong>{{ $barangList->count() }}</strong> barang pending
				</div>

				<div class="pagination-bar__nav">
					<button class="pagination-btn" type="button">Prev</button>
					<button class="pagination-btn is-active" type="button">1</button>
					<button class="pagination-btn" type="button">Next</button>
				</div>
			</div>
		</x-card>
	</div>
@endsection
