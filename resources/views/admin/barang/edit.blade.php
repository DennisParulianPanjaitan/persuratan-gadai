@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Edit Barang"
			breadcrumb="Dashboard > Master Data > Barang > Edit"
		/>

		<x-card>
			<div class="table-toolbar">
				<div>
					<div class="table-toolbar__title">Form Edit Barang</div>
					<div class="table-toolbar__subtitle">Ubah data barang yang sudah terdaftar di sistem.</div>
				</div>
			</div>

			<form method="POST" action="{{ route('admin.barang.update', $barang) }}" enctype="multipart/form-data">
				@csrf
				@method('PUT')

				<div class="form-grid">

					{{-- Jenis Barang --}}
					<x-form-group label="Jenis Barang" name="id_jenis_barang" :required="true">
						<select name="id_jenis_barang" id="id_jenis_barang"
						        class="form-select @error('id_jenis_barang') is-invalid @enderror">
							<option value="">-- Pilih Jenis Barang --</option>
							@foreach ($jenisBarangList as $jenis)
								<option value="{{ $jenis->id_jenis_barang }}" @selected(old('id_jenis_barang', $barang->id_jenis_barang) == $jenis->id_jenis_barang)>
									{{ $jenis->nama_jenis }}
								</option>
							@endforeach
						</select>
					</x-form-group>

					{{-- Nama Barang --}}
					<x-form-group label="Nama Barang" name="nama_barang" :required="true">
						<input type="text" name="nama_barang" id="nama_barang"
						       class="form-input @error('nama_barang') is-invalid @enderror"
						       value="{{ old('nama_barang', $barang->nama_barang) }}"
						       placeholder="Contoh: Cincin Emas 5gr">
					</x-form-group>

					{{-- Harga Beli --}}
					<x-form-group label="Harga Beli (Rp)" name="harga_beli" :required="true">
						<input type="number" name="harga_beli" id="harga_beli"
						       class="form-input @error('harga_beli') is-invalid @enderror"
						       value="{{ old('harga_beli', $barang->harga_beli) }}"
						       placeholder="Contoh: 5000000"
						       min="0" step="1">
					</x-form-group>

					{{-- Berat --}}
					<x-form-group label="Berat (gram)" name="berat" hint="Masukkan berat dalam satuan gram.">
						<input type="number" name="berat" id="berat"
						       class="form-input @error('berat') is-invalid @enderror"
						       value="{{ old('berat', $barang->berat) }}"
						       placeholder="Contoh: 5"
						       min="0" step="0.01">
					</x-form-group>

					{{-- Kondisi --}}
					<x-form-group label="Kondisi" name="kondisi" :required="true">
						<select name="kondisi" id="kondisi"
						        class="form-select @error('kondisi') is-invalid @enderror">
							<option value="">-- Pilih Kondisi --</option>
							<option value="Baik" @selected(old('kondisi', $barang->kondisi) == 'Baik')>Baik</option>
							<option value="Cukup Baik" @selected(old('kondisi', $barang->kondisi) == 'Cukup Baik')>Cukup Baik</option>
							<option value="Rusak Ringan" @selected(old('kondisi', $barang->kondisi) == 'Rusak Ringan')>Rusak Ringan</option>
							<option value="Rusak Berat" @selected(old('kondisi', $barang->kondisi) == 'Rusak Berat')>Rusak Berat</option>
						</select>
					</x-form-group>

					{{-- Foto Barang --}}
					<x-form-group label="Foto Barang" name="foto_barang" hint="Format: JPG, PNG, WEBP. Maks: 2MB. (Biarkan kosong jika tidak ingin mengubah foto)">
						<div class="form-file-wrapper">
							<input type="file" name="foto_barang" id="foto_barang"
							       accept="image/jpeg,image/png,image/webp"
							       onchange="previewImage(event)">
							<div class="form-file-wrapper__icon"><i class="bi bi-cloud-arrow-up"></i></div>
							<div class="form-file-wrapper__text">Klik atau seret foto baru ke sini</div>
							<div class="form-file-wrapper__hint">JPG, PNG, WEBP — Maks 2MB</div>
						</div>
						@php
							$currentFoto = $barang->foto_barang 
								? (preg_match('/^https?:\/\//', $barang->foto_barang) ? $barang->foto_barang : asset('storage/' . ltrim($barang->foto_barang, '/'))) 
								: '';
						@endphp
						<img id="fotoPreview" class="form-preview-img" style="{{ $currentFoto ? 'display:block;' : 'display:none;' }}" src="{{ $currentFoto }}" alt="Preview">
					</x-form-group>

					{{-- Keterangan --}}
					<div class="form-group form-group--full">
						<x-form-group label="Keterangan" name="keterangan" hint="Opsional. Catatan tambahan tentang barang.">
							<textarea name="keterangan" id="keterangan"
							          class="form-textarea @error('keterangan') is-invalid @enderror"
							          placeholder="Masukkan catatan atau keterangan tambahan...">{{ old('keterangan', $barang->keterangan) }}</textarea>
						</x-form-group>
					</div>

				</div>

				<div class="form-actions">
					<x-button href="{{ route('admin.barang.index') }}" variant="secondary">Batal</x-button>
					<x-button type="submit" variant="primary">Simpan Perubahan</x-button>
				</div>

			</form>
		</x-card>
	</div>
@endsection

@push('scripts')
<script>
function previewImage(event) {
	const file = event.target.files[0];
	const preview = document.getElementById('fotoPreview');

	if (file) {
		const reader = new FileReader();
		reader.onload = function(e) {
			preview.src = e.target.result;
			preview.style.display = 'block';
		};
		reader.readAsDataURL(file);
	}
}
</script>
	@if ($errors->any())
		<script>
			Swal.fire({
				icon: 'error',
				title: 'Gagal Menyimpan!',
				text: '{{ $errors->first() }}',
				confirmButtonText: 'Mengerti'
			});
		</script>
	@endif
@endpush
