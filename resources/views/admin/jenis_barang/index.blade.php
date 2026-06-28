@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Jenis Barang"
			breadcrumb="Dashboard > Master Data > Jenis Barang"
			buttonText="+ Tambah Jenis Barang"
			buttonUrl="#"
		/>

		<x-card>
			<form method="GET" action="{{ url()->current() }}">
				<div class="table-toolbar">
					<div>
						<div class="table-toolbar__title">Filter Data Jenis Barang</div>
						<div class="table-toolbar__subtitle">Gunakan filter untuk menyaring data jenis barang dari database.</div>
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
						<x-search name="search" value="{{ request('search') }}" placeholder="Cari jenis barang..." />
					</div>
				</div>
			</form>

			<x-table :headers="['Nama Jenis', 'Deskripsi', 'Aksi']">
				@forelse ($jenisBarangList as $jenisBarang)
					<tr>
						<td style="font-weight:600;color:#0F172A;">{{ $jenisBarang->nama_jenis }}</td>
						<td>{{ $jenisBarang->deskripsi ?: '-' }}</td>
						<td>
							<div style="display:flex;flex-wrap:wrap;gap:8px;">
								<x-button href="#" variant="secondary">Lihat</x-button>
								<x-button href="#" variant="primary">Edit</x-button>
								<x-button href="#" variant="danger">Hapus</x-button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="3" style="text-align:center;padding:28px 18px;color:#64748B;">
							Data jenis barang belum tersedia.
						</td>
					</tr>
				@endforelse
			</x-table>

			<div class="pagination-bar">
				<div class="pagination-bar__info">
					Menampilkan <strong>{{ $jenisBarangList->count() }}</strong> data dari database
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
