@extends('layouts.admin')

@section('content')
	<div class="page-shell barang-page">
		<x-page-header
			title="Request Pembayaran Online"
			breadcrumb="Dashboard > Pembayaran"
			buttonText=""
			buttonUrl=""
		/>

		<x-card>
			@if (session('success'))
				<div id="flash-success" data-message="{{ session('success') }}"></div>
			@endif

			<form method="GET" action="{{ url()->current() }}" id="filter-form">
				<div class="table-toolbar">
					<div>
						<div class="table-toolbar__title">Daftar Pengajuan Pembayaran</div>
						<div class="table-toolbar__subtitle">Kelola dan verifikasi bukti pembayaran (tebus & perpanjangan) yang dikirim pelanggan.</div>
					</div>
					<div class="table-toolbar__actions">
					</div>
				</div>

				<div class="filter-grid" style="grid-template-columns: max-content 400px; gap: 16px;">
					<label class="filter-field">
						<span class="filter-field__label">Status</span>
						<select name="status" class="filter-input" style="padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; background: #fff; min-width: 260px; width: 100%;">
                            <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' || !request('status') ? 'selected' : '' }}>Menunggu Konfirmasi ({{ $counts['pending'] ?? 0 }})</option>
                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui ({{ $counts['disetujui'] ?? 0 }})</option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak ({{ $counts['ditolak'] ?? 0 }})</option>
                        </select>
					</label>

					<div class="filter-field filter-field--search">
						<span class="filter-field__label">Pencarian</span>
						<x-search name="search" value="{{ request('search') }}" placeholder="Cari kode trx / pelanggan..." autocomplete="off" />
					</div>
				</div>
			</form>

			<x-table :headers="['Tanggal', 'Kode Trx', 'Pelanggan', 'Jenis', 'Nominal', 'Status', 'Aksi']">
				@forelse ($pembayaranList as $p)
					<tr>
						<td>
                            <div style="font-weight:600;color:#0F172A;">{{ \Carbon\Carbon::parse($p->created_at)->translatedFormat('d M Y') }}</div>
                            <div style="font-size:12px;color:#64748B;">{{ \Carbon\Carbon::parse($p->created_at)->translatedFormat('H:i') }} WIB</div>
                        </td>
						<td>
                            <div style="font-weight:700;color:#334155;letter-spacing: 0.5px;">{{ $p->transaksiGadai->kode_transaksi }}</div>
                        </td>
						<td>
							<div style="font-weight:600;color:#0F172A;">{{ $p->transaksiGadai->pelanggan->user->nama ?? '-' }}</div>
							<div style="font-size:12px;color:#64748B;">{{ $p->transaksiGadai->pelanggan->no_telepon ?? '-' }}</div>
						</td>
						<td>
                            @if($p->jenis_pembayaran == 'tebus')
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <i class="bi bi-box-arrow-in-right" style="margin-right: 4px;"></i> Tebus
                                </span>
                            @else
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700">
                                    <i class="bi bi-arrow-repeat" style="margin-right: 4px;"></i> Perpanjangan
                                </span>
                            @endif
                        </td>
						<td>
							<div style="font-weight:700;color:#0F172A;">
								{{ 'Rp ' . number_format($p->nominal_bayar, 0, ',', '.') }}
							</div>
                            @if($p->jenis_pembayaran == 'perpanjangan')
                                <div style="font-size:12px;color:#64748B;">{{ $p->jumlah_bulan }} Bulan</div>
                            @endif
						</td>
						<td>
                            @if($p->status == 'menunggu_konfirmasi')
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-700">
                                    Menunggu
                                </span>
                            @elseif($p->status == 'disetujui')
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    Disetujui
                                </span>
                            @else
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold bg-red-100 text-red-700">
                                    Ditolak
                                </span>
                            @endif
						</td>
						<td>
							<div style="display:flex;flex-wrap:wrap;gap:8px;">
                                @if($p->status == 'menunggu_konfirmasi')
								    <x-button href="{{ route('admin.pembayaran.show', $p->id_pembayaran) }}" variant="primary">Proses Transaksi</x-button>
                                @else
								    <x-button href="{{ route('admin.pembayaran.show', $p->id_pembayaran) }}" variant="secondary">Detail</x-button>
                                @endif
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="7" style="text-align:center;padding:28px 18px;color:#64748B;">
							Tidak ada pengajuan pembayaran.
						</td>
					</tr>
				@endforelse
			</x-table>

			<x-pagination :paginator="$pembayaranList" />
		</x-card>
	</div>

    @push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const searchInput = document.querySelector('input[name="search"]');
            const statusSelect = document.querySelector('select[name="status"]');
			let timer;
			
			function fetchFilteredData() {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', searchInput.value);
                    url.searchParams.set('status', statusSelect.value);
                    
                    // Update browser URL without reloading
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
                        
                        // Replace tbody
                        const currentTableBody = document.querySelector('.table-responsive tbody');
                        const newTableBody = doc.querySelector('.table-responsive tbody');
                        if (currentTableBody && newTableBody) {
                            currentTableBody.innerHTML = newTableBody.innerHTML;
                        }
                        
                        // Replace pagination if any
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

            if (statusSelect) {
                statusSelect.addEventListener('change', fetchFilteredData);
            }
		});
	</script>
	@endpush
@endsection
