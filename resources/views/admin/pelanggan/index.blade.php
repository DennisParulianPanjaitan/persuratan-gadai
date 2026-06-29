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
						<x-search name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." />
					</div>
				</div>
			</form>

			<x-table :headers="['Nama', 'No HP', 'Alamat', 'Email', 'Keterangan', 'Aksi']">
				@forelse ($pelangganList as $pelanggan)
					<tr>
						<td style="font-weight:600;color:#0F172A;">{{ $pelanggan->nama }}</td>
						<td>{{ $pelanggan->no_hp ?: '-' }}</td>
						<td>{{ $pelanggan->alamat ?: '-' }}</td>
						<td>{{ $pelanggan->email ?: '-' }}</td>
						<td>{{ $pelanggan->keterangan ?: '-' }}</td>
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
						<td colspan="6" style="text-align:center;padding:28px 18px;color:#64748B;">
							Data pelanggan belum tersedia.
						</td>
					</tr>
				@endforelse
			</x-table>

			<x-pagination :paginator="$pelangganList" />
		</x-card>
	</div>
@endsection
