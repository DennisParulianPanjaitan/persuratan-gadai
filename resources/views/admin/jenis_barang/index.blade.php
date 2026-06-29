@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Jenis Barang"
			breadcrumb="Dashboard > Master Data > Jenis Barang"
			buttonText="+ Tambah Jenis Barang"
			buttonUrl="{{ route('admin.jenis_barang.create') }}"
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
								<x-button href="{{ route('admin.jenis_barang.edit', $jenisBarang->id_jenis_barang) }}" variant="primary">Edit</x-button>

								<form action="{{ route('admin.jenis_barang.destroy', $jenisBarang->id_jenis_barang) }}" method="POST" class="js-swal-confirm" data-title="Hapus Jenis Barang?" data-text="Anda yakin ingin menghapus {{ $jenisBarang->nama_jenis }}? Data ini tidak dapat dikembalikan.">
									@csrf
									@method('DELETE')
									<x-button type="submit" variant="danger">Hapus</x-button>
								</form>
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

			<x-pagination :paginator="$jenisBarangList" />
		</x-card>
	</div>
@endsection
