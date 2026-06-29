@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Penyerahan Barang & Transaksi"
			breadcrumb="Dashboard > Penyerahan Barang"
			buttonText=""
			buttonUrl=""
		/>

		<x-card>
			@if (session('success'))
				<div id="flash-success" data-message="{{ session('success') }}"></div>
			@endif

			<form method="GET" action="{{ url()->current() }}">
				<div class="table-toolbar">
					<div>
						<div class="table-toolbar__title">Menunggu Penyerahan Fisik</div>
						<div class="table-toolbar__subtitle">Daftar barang yang pengajuannya telah disetujui (ACC) dan menunggu pelanggan menyerahkan barang fisik.</div>
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
						<x-search name="search" value="{{ request('search') }}" placeholder="Cari barang acc..." />
					</div>
				</div>
			</form>

			<x-table :headers="['Foto', 'Nama Barang', 'Jenis', 'Harga Beli', 'Harga ACC', 'Status', 'Aksi']">
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
							<div style="font-weight:600;color:#059669;">
								{{ 'Rp ' . number_format($barang->harga_gadai_sementara, 0, ',', '.') }}
							</div>
						</td>
						<td>
							<span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700">
								Menunggu Fisik
							</span>
						</td>
						<td>
							<div style="display:flex;flex-wrap:wrap;gap:8px;">
								<x-button href="#" variant="primary">Proses Transaksi</x-button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="7" style="text-align:center;padding:28px 18px;color:#64748B;">
							Tidak ada barang yang sedang menunggu penyerahan fisik.
						</td>
					</tr>
				@endforelse
			</x-table>

			<x-pagination :paginator="$barangList" />
		</x-card>
	</div>
@endsection
