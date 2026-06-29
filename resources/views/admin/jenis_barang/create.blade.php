@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Tambah Jenis Barang"
			breadcrumb="Dashboard > Master Data > Jenis Barang > Tambah"
		/>

		<x-card>
			<div class="table-toolbar">
				<div>
					<div class="table-toolbar__title">Form Tambah Jenis Barang</div>
					<div class="table-toolbar__subtitle">Isi kategori/jenis barang baru.</div>
				</div>
			</div>

			<form method="POST" action="{{ route('admin.jenis_barang.store') }}">
				@csrf

				<div class="form-grid">

					{{-- Nama Jenis --}}
					<x-form-group label="Nama Jenis Barang" name="nama_jenis" :required="true">
						<input type="text" name="nama_jenis" id="nama_jenis"
						       class="form-input @error('nama_jenis') is-invalid @enderror"
						       value="{{ old('nama_jenis') }}"
						       placeholder="Contoh: Elektronik">
					</x-form-group>

					{{-- Deskripsi --}}
					<div class="form-group form-group--full">
						<x-form-group label="Deskripsi" name="deskripsi" hint="Opsional. Keterangan dari jenis barang ini.">
							<textarea name="deskripsi" id="deskripsi"
							          class="form-textarea @error('deskripsi') is-invalid @enderror"
							          placeholder="Masukkan deskripsi jenis barang...">{{ old('deskripsi') }}</textarea>
						</x-form-group>
					</div>

				</div>

				<div class="form-actions">
					<x-button href="{{ route('admin.jenis_barang.index') }}" variant="secondary">Batal</x-button>
					<x-button type="submit" variant="primary">Simpan Jenis Barang</x-button>
				</div>

			</form>
		</x-card>
	</div>
@endsection
