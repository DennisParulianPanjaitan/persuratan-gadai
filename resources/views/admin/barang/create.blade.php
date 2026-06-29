@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Tambah Barang"
			breadcrumb="Dashboard > Master Data > Barang > Tambah"
		/>

		<x-card>
			<div class="table-toolbar">
				<div>
					<div class="table-toolbar__title">Form Tambah Barang</div>
					<div class="table-toolbar__subtitle">Isi data barang baru yang akan didaftarkan ke sistem.</div>
				</div>
			</div>

			<form method="POST" action="{{ route('admin.barang.store') }}" enctype="multipart/form-data">
				@csrf

				<div class="form-grid">

					{{-- Jenis Barang --}}
					<x-form-group label="Jenis Barang" name="id_jenis_barang" :required="true">
						<select name="id_jenis_barang" id="id_jenis_barang"
						        class="form-select @error('id_jenis_barang') is-invalid @enderror">
							<option value="">-- Pilih Jenis Barang --</option>
							@foreach ($jenisBarangList as $jenis)
								<option value="{{ $jenis->id_jenis_barang }}" @selected(old('id_jenis_barang') == $jenis->id_jenis_barang)>
									{{ $jenis->nama_jenis }}
								</option>
							@endforeach
						</select>
					</x-form-group>

					{{-- Nama Barang --}}
					<x-form-group label="Nama Barang" name="nama_barang" :required="true">
						<input type="text" name="nama_barang" id="nama_barang"
						       class="form-input @error('nama_barang') is-invalid @enderror"
						       value="{{ old('nama_barang') }}"
						       placeholder="Contoh: Cincin Emas 5gr">
					</x-form-group>

					{{-- Harga Beli --}}
					<x-form-group label="Harga Beli (Rp)" name="harga_beli" :required="true">
						<input type="number" name="harga_beli" id="harga_beli"
						       class="form-input @error('harga_beli') is-invalid @enderror"
						       value="{{ old('harga_beli') }}"
						       placeholder="Contoh: 5000000"
						       min="0" step="1">
					</x-form-group>

					{{-- Berat --}}
					<x-form-group label="Berat (gram)" name="berat" hint="Masukkan berat dalam satuan gram.">
						<input type="number" name="berat" id="berat"
						       class="form-input @error('berat') is-invalid @enderror"
						       value="{{ old('berat') }}"
						       placeholder="Contoh: 5"
						       min="0" step="0.01">
					</x-form-group>

					{{-- Kondisi --}}
					<x-form-group label="Kondisi" name="kondisi" :required="true">
						<select name="kondisi" id="kondisi"
						        class="form-select @error('kondisi') is-invalid @enderror">
							<option value="">-- Pilih Kondisi --</option>
							<option value="Baik" @selected(old('kondisi') == 'Baik')>Baik</option>
							<option value="Cukup Baik" @selected(old('kondisi') == 'Cukup Baik')>Cukup Baik</option>
							<option value="Rusak Ringan" @selected(old('kondisi') == 'Rusak Ringan')>Rusak Ringan</option>
							<option value="Rusak Berat" @selected(old('kondisi') == 'Rusak Berat')>Rusak Berat</option>
						</select>
					</x-form-group>

					{{-- Foto Barang --}}
					<x-form-group label="Foto Barang" name="foto_barang" hint="Format: JPG, PNG, WEBP. Maks: 2MB.">
						<div class="form-file-wrapper">
							<input type="file" name="foto_barang" id="foto_barang"
							       accept="image/jpeg,image/png,image/webp"
							       onchange="previewImage(event)">
							<div class="form-file-wrapper__icon"><i class="bi bi-cloud-arrow-up"></i></div>
							<div class="form-file-wrapper__text">Klik atau seret foto ke sini</div>
							<div class="form-file-wrapper__hint">JPG, PNG, WEBP — Maks 2MB</div>
						</div>
						<img id="fotoPreview" class="form-preview-img" style="display:none;" alt="Preview">
					</x-form-group>

					{{-- Keterangan --}}
					<div class="form-group form-group--full">
						<x-form-group label="Keterangan" name="keterangan" hint="Opsional. Catatan tambahan tentang barang.">
							<textarea name="keterangan" id="keterangan"
							          class="form-textarea @error('keterangan') is-invalid @enderror"
							          placeholder="Masukkan catatan atau keterangan tambahan...">{{ old('keterangan') }}</textarea>
						</x-form-group>
					</div>

				</div>

				<div class="form-actions">
					<x-button href="{{ route('admin.barang.index') }}" variant="secondary">Batal</x-button>
					<x-button type="submit" variant="primary">Simpan Barang</x-button>
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
	} else {
		preview.style.display = 'none';
		preview.src = '';
	}
}
</script>
@endpush
