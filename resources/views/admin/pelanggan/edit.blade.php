@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Edit Pelanggan"
			breadcrumb="Dashboard > Master Data > Pelanggan > Edit"
		/>

		<x-card>
			<div class="table-toolbar">
				<div>
					<div class="table-toolbar__title">Form Edit Pelanggan</div>
					<div class="table-toolbar__subtitle">Ubah data pelanggan yang sudah terdaftar di sistem.</div>
				</div>
			</div>

			<form method="POST" action="{{ route('admin.pelanggan.update', $pelanggan->id_pelanggan) }}">
				@csrf
				@method('PUT')

				<div class="form-grid">

					{{-- ID User (Read Only atau Optional edit, disini biarkan opsional) --}}
					<x-form-group label="ID User (Terkait Akun Login)" name="id_user" hint="Opsional. Isi dengan ID dari tabel users jika pelanggan ini punya akun.">
						<input type="number" name="id_user" id="id_user"
						       class="form-input @error('id_user') is-invalid @enderror"
						       value="{{ old('id_user', $pelanggan->id_user) }}"
						       placeholder="Contoh: 10">
					</x-form-group>

					{{-- Nama Pelanggan --}}
					<x-form-group label="Nama Lengkap" name="nama" :required="true">
						<input type="text" name="nama" id="nama"
						       class="form-input @error('nama') is-invalid @enderror"
						       value="{{ old('nama', $pelanggan->nama) }}"
						       placeholder="Contoh: Budi Santoso">
					</x-form-group>

					{{-- No HP --}}
					<x-form-group label="No. Handphone" name="no_hp" :required="true">
						<input type="text" name="no_hp" id="no_hp"
						       class="form-input @error('no_hp') is-invalid @enderror"
						       value="{{ old('no_hp', $pelanggan->no_hp) }}"
						       placeholder="Contoh: 08123456789">
					</x-form-group>

					{{-- Email --}}
					<x-form-group label="Email" name="email" hint="Opsional">
						<input type="email" name="email" id="email"
						       class="form-input @error('email') is-invalid @enderror"
						       value="{{ old('email', $pelanggan->email) }}"
						       placeholder="Contoh: budi@gmail.com">
					</x-form-group>

					{{-- Alamat --}}
					<x-form-group label="Alamat Lengkap" name="alamat" :required="true">
						<textarea name="alamat" id="alamat"
						          class="form-textarea @error('alamat') is-invalid @enderror"
						          placeholder="Masukkan alamat lengkap pelanggan...">{{ old('alamat', $pelanggan->alamat) }}</textarea>
					</x-form-group>

					{{-- Keterangan --}}
					<div class="form-group form-group--full">
						<x-form-group label="Keterangan" name="keterangan" hint="Opsional. Catatan tambahan mengenai pelanggan ini.">
							<textarea name="keterangan" id="keterangan"
							          class="form-textarea @error('keterangan') is-invalid @enderror"
							          placeholder="Masukkan catatan jika ada...">{{ old('keterangan', $pelanggan->keterangan) }}</textarea>
						</x-form-group>
					</div>

				</div>

				<div class="form-actions">
					<x-button href="{{ route('admin.pelanggan.index') }}" variant="secondary">Batal</x-button>
					<x-button type="submit" variant="primary">Simpan Perubahan</x-button>
				</div>

			</form>
		</x-card>
	</div>
@endsection

@push('scripts')
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
