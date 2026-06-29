@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Edit Jenis Barang"
			breadcrumb="Dashboard > Master Data > Jenis Barang > Edit"
		/>

		<x-card>
			<div class="table-toolbar">
				<div>
					<div class="table-toolbar__title">Form Edit Jenis Barang</div>
					<div class="table-toolbar__subtitle">Perbarui data jenis barang yang sudah ada.</div>
				</div>
			</div>

			<form method="POST" action="{{ route('admin.jenis_barang.update', $jenisBarang->id_jenis_barang) }}">
				@csrf
				@method('PUT')

				<div class="form-grid">

					{{-- Nama Jenis --}}
					<x-form-group label="Nama Jenis Barang" name="nama_jenis" :required="true">
						<input type="text" name="nama_jenis" id="nama_jenis"
						       class="form-input @error('nama_jenis') is-invalid @enderror"
						       value="{{ old('nama_jenis', $jenisBarang->nama_jenis) }}"
						       placeholder="Contoh: Elektronik">
					</x-form-group>

					{{-- Deskripsi --}}
					<div class="form-group form-group--full">
						<x-form-group label="Deskripsi" name="deskripsi" hint="Opsional. Keterangan dari jenis barang ini.">
							<textarea name="deskripsi" id="deskripsi"
							          class="form-textarea @error('deskripsi') is-invalid @enderror"
							          placeholder="Masukkan deskripsi jenis barang...">{{ old('deskripsi', $jenisBarang->deskripsi) }}</textarea>
						</x-form-group>
					</div>

				</div>

				<div class="form-actions">
					<x-button href="{{ route('admin.jenis_barang.index') }}" variant="secondary">Batal</x-button>
					<x-button type="submit" variant="primary">Simpan Perubahan</x-button>
				</div>

			</form>
		</x-card>
	</div>
@endsection
