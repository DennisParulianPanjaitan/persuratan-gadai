@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Barang"
			breadcrumb="Dashboard > Master Data > Barang"
			buttonText="+ Tambah Barang"
			buttonUrl="#"
		/>

		<x-card>
			<form method="GET" action="{{ url()->current() }}">
				<div class="table-toolbar">
					<div>
						<div class="table-toolbar__title">Filter Data Barang</div>
						<div class="table-toolbar__subtitle">Gunakan filter untuk menyaring data dari database.</div>
					</div>

					<div class="table-toolbar__actions">
						<x-button type="submit" variant="secondary">Filter</x-button>
						<x-button href="{{ url()->current() }}" variant="secondary">Reset</x-button>
					</div>
				</div>

				<div class="filter-grid">
					<label class="filter-field">
						<span class="filter-field__label">Tanggal</span>
						<input type="date" name="tanggal" value="{{ request('tanggal') }}" class="filter-input">
					</label>

					<label class="filter-field">
						<span class="filter-field__label">Jenis Barang</span>
						<select name="jenis_barang" class="filter-input">
							<option value="">Semua Jenis</option>
							@foreach ($jenisBarangList as $jenisBarang)
								<option value="{{ $jenisBarang->id_jenis_barang }}" @selected(request('jenis_barang') == $jenisBarang->id_jenis_barang)>
									{{ $jenisBarang->nama_jenis }}
								</option>
							@endforeach
						</select>
					</label>

					<label class="filter-field">
						<span class="filter-field__label">Status</span>
						<select name="status" class="filter-input">
							<option value="">Semua Status</option>
								<option value="pending" @selected(request('status') === 'pending')>Pending</option>
								<option value="terverifikasi" @selected(request('status') === 'terverifikasi')>Terverifikasi</option>
								<option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
						</select>
					</label>

					<div class="filter-field filter-field--search">
						<span class="filter-field__label">Pencarian</span>
						<x-search name="search" value="{{ request('search') }}" placeholder="Cari barang..." />
					</div>
				</div>
			</form>

			<x-table :headers="['Foto', 'Nama Barang', 'Jenis', 'Harga', 'Status', 'Aksi']">
				@forelse ($barangList as $barang)
					<tr>
						<td>{{ $barang->foto_barang ?: '-' }}</td>
						<td>
							@if ($barang->foto_barang)
								@php
									$fotoBarang = preg_match('/^https?:\/\//', $barang->foto_barang)
										? $barang->foto_barang
										: asset('storage/' . ltrim($barang->foto_barang, '/'));
								@endphp
								<img src="{{ $fotoBarang }}" alt="Foto {{ $barang->nama_barang }}" style="width:56px;height:56px;object-fit:cover;border-radius:14px;border:1px solid #E2E8F0;display:block;">
							@else
								<span style="color:#64748B;">-</span>
							@endif
						</td>
						<td>
							<div style="font-weight:600;color:#0F172A;">{{ $barang->nama_barang }}</div>
							<div style="font-size:12px;color:#64748B;">{{ $barang->keterangan ?: '-' }}</div>
						</td>
						<td>{{ $barang->jenisBarang->nama_jenis ?? '-' }}</td>
						<td>{{ 'Rp ' . number_format($barang->harga_beli, 0, ',', '.') }}</td>
						<td>
							@php
								$statusBarang = strtolower($barang->status_verifikasi);
								$statusLabel = match ($statusBarang) {
									'terverifikasi' => 'Terverifikasi',
									'pending' => 'Pending',
									'ditolak' => 'Ditolak',
									default => $barang->status_verifikasi,
								};
							@endphp
							<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusBarang === 'terverifikasi' ? 'bg-emerald-100 text-emerald-700' : ($statusBarang === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
								{{ $statusLabel }}
							</span>
						</td>
						<td>
							<div style="display:flex;flex-wrap:wrap;gap:8px;">
								<x-button href="{{ route('admin.barang.show', $barang) }}" variant="secondary">Lihat</x-button>
								<x-button href="#" variant="primary">Edit</x-button>
								<x-button href="#" variant="danger">Hapus</x-button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="6" style="text-align:center;padding:28px 18px;color:#64748B;">
							Data barang belum tersedia.
						</td>
					</tr>
				@endforelse
			</x-table>

			<div class="pagination-bar">
				<div class="pagination-bar__info">
					Menampilkan <strong>{{ $barangList->count() }}</strong> data dari database
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
