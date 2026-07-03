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
						<x-button type="button" variant="primary" onclick="openQrScanner()" style="background: #10b981; border-color: #10b981; color: white; display: flex; align-items: center; gap: 8px;">
							<i class="bi bi-qr-code-scan"></i> Scan QR Pelanggan
						</x-button>
					</div>
				</div>

				<!-- Modal QR Scanner -->
				<div id="qrModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
					<div style="background: #fff; width: 90%; max-width: 500px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
						<div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
							<h3 style="margin: 0; font-size: 16px; color: #0f172a; font-weight: 600;"><i class="bi bi-camera" style="margin-right: 8px;"></i> Arahkan QR Code ke Kamera</h3>
							<button type="button" onclick="closeQrScanner()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b;">&times;</button>
						</div>
						<div style="padding: 20px;">
							<div id="reader" style="width: 100%;"></div>
							<p style="text-align: center; font-size: 13px; color: #64748b; margin-top: 15px;">Sistem akan otomatis memindai QR Code Pelanggan dan membuka halaman serah terima barang.</p>
						</div>
					</div>
				</div>

				<div class="filter-grid" style="grid-template-columns: max-content 400px; gap: 16px;">
					<label class="filter-field">
						<span class="filter-field__label">Tanggal</span>
						<input type="date" name="tanggal" value="{{ request('tanggal') }}" class="filter-input" style="padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; background: #fff; min-width: 200px; width: 100%;">
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
						<td>
							@if ($barang->foto_barang)
								@php
									$fotoBarang = preg_match('/^https?:\/\//', $barang->foto_barang)
										? $barang->foto_barang
										: asset('storage/' . ltrim($barang->foto_barang, '/'));
								@endphp
								<img src="{{ $fotoBarang }}" alt="Foto" style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #E2E8F0;">
							@else
								<span style="color:#94a3b8; font-style:italic;">Tidak ada</span>
							@endif
						</td>
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
								<x-button href="{{ route('admin.penyerahan_barang.show', $barang) }}" variant="primary">Proses Transaksi</x-button>
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

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
	let html5QrcodeScanner = null;

	function openQrScanner() {
		document.getElementById('qrModal').style.display = 'flex';
		
		if (!html5QrcodeScanner) {
			html5QrcodeScanner = new Html5QrcodeScanner(
				"reader", 
				{ fps: 10, qrbox: {width: 250, height: 250} }, 
				/* verbose= */ false
			);
		}
		
		html5QrcodeScanner.render(onScanSuccess, onScanFailure);
	}

	function closeQrScanner() {
		document.getElementById('qrModal').style.display = 'none';
		if (html5QrcodeScanner) {
			html5QrcodeScanner.clear().catch(error => {
				console.error("Failed to clear html5QrcodeScanner. ", error);
			});
		}
	}

	function onScanSuccess(decodedText, decodedResult) {
		// Stop scanning
		closeQrScanner();
		
		// Customer QR usually contains URL like: http://localhost:8000/surat-gadai/12
		// We want to extract the ID and redirect to /admin/penyerahan-barang/12
		try {
			let url = new URL(decodedText);
			let pathParts = url.pathname.split('/');
			let id = pathParts[pathParts.length - 1]; // Get the last part of the path
			
			if (id && !isNaN(id)) {
				window.location.href = "{{ url('/admin/penyerahan-barang') }}/" + id;
				return;
			}
		} catch (e) {
			// If it's not a URL, maybe it's just the ID or BARANG-ID
			console.log("Not a valid URL, trying to parse as text.");
		}

		// Fallback: put text in search and submit
		let searchInput = document.querySelector('input[name="search"]');
		if (searchInput) {
			searchInput.value = decodedText;
			searchInput.form.submit();
		}
	}

	function onScanFailure(error) {
		// handle scan failure, usually better to ignore and keep scanning.
	}

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        const dateInput = document.querySelector('input[name="tanggal"]');
        let timer;
        
        function fetchFilteredData() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                const url = new URL(window.location.href);
                url.searchParams.set('search', searchInput.value);
                url.searchParams.set('tanggal', dateInput.value);
                
                window.history.pushState({}, '', url);
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const currentTableBody = document.querySelector('.table-responsive tbody');
                    const newTableBody = doc.querySelector('.table-responsive tbody');
                    if (currentTableBody && newTableBody) {
                        currentTableBody.innerHTML = newTableBody.innerHTML;
                    }
                    
                    const currentPagination = document.querySelector('nav[role="navigation"]')?.parentElement;
                    const newPagination = doc.querySelector('nav[role="navigation"]')?.parentElement;
                    if (currentPagination && newPagination) {
                        currentPagination.innerHTML = newPagination.innerHTML;
                    } else if (currentPagination && !newPagination) {
                        currentPagination.innerHTML = '';
                    }
                });
            }, 300);
        }

        if (searchInput) {
            searchInput.addEventListener('input', fetchFilteredData);
        }

        if (dateInput) {
            dateInput.addEventListener('change', fetchFilteredData);
        }
    });
</script>
@endpush
