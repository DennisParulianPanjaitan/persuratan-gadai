@extends('layouts.admin')

@section('content')
	<div class="page-shell">
		<x-page-header
			title="Laporan Arus Kas"
			breadcrumb="Dashboard > Laporan"
		/>

		<x-card>
			<form method="GET" action="{{ route('admin.laporan') }}" class="table-toolbar" style="align-items: flex-end; padding: 24px 24px 0 24px;">
				<div style="display: flex; gap: 15px; align-items: flex-end;">
					<label class="filter-field">
						<span class="filter-field__label">Dari Tanggal</span>
						<input type="date" name="start_date" value="{{ $startDate }}" class="filter-input" required>
					</label>

					<label class="filter-field">
						<span class="filter-field__label">Sampai Tanggal</span>
						<input type="date" name="end_date" value="{{ $endDate }}" class="filter-input" required>
					</label>
                    
                    <x-button type="submit" variant="secondary">Filter Laporan</x-button>
				</div>

				<div class="table-toolbar__actions">
					<x-button href="{{ route('admin.laporan.cetak') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" variant="primary" target="_blank">
                        <i class="bi bi-printer" style="margin-right: 6px;"></i> Cetak Laporan
                    </x-button>
				</div>
			</form>

            <div style="margin: 30px 0; padding: 0 24px;">
                <div style="font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 20px;">Ringkasan Keuangan ({{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }})</div>
                
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px;">
                        <div style="font-size: 14px; color: #64748B; font-weight: 600; margin-bottom: 8px;">Total Pengeluaran (Gadai Baru)</div>
                        <div style="font-size: 24px; font-weight: 700; color: #E11D48;">Rp {{ number_format($data['summary']['total_pengeluaran'], 0, ',', '.') }}</div>
                    </div>
                    
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px;">
                        <div style="font-size: 14px; color: #64748B; font-weight: 600; margin-bottom: 8px;">Total Pemasukan (Tebus, Bunga, Lelang)</div>
                        <div style="font-size: 24px; font-weight: 700; color: #059669;">Rp {{ number_format($data['summary']['total_pemasukan'], 0, ',', '.') }}</div>
                    </div>
                    
                    <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 20px;">
                        <div style="font-size: 14px; color: #64748B; font-weight: 600; margin-bottom: 8px;">Arus Kas Bersih (Laba/Rugi)</div>
                        @if($data['summary']['arus_kas'] >= 0)
                            <div style="font-size: 24px; font-weight: 700; color: #0ea5e9;">+ Rp {{ number_format($data['summary']['arus_kas'], 0, ',', '.') }}</div>
                        @else
                            <div style="font-size: 24px; font-weight: 700; color: #E11D48;">- Rp {{ number_format(abs($data['summary']['arus_kas']), 0, ',', '.') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="tabs-container" style="padding: 0 24px;">
                <button class="tab-button active" onclick="showTab('gadai')">Uang Keluar (Gadai Baru)</button>
                <button class="tab-button" onclick="showTab('tebus')">Uang Masuk (Penebusan)</button>
                <button class="tab-button" onclick="showTab('perpanjangan')">Uang Masuk (Bunga/Perpanjangan)</button>
                <button class="tab-button" onclick="showTab('penjualan')">Uang Masuk (Lelang)</button>
            </div>

            <!-- TAB GADAI -->
            <div id="tab-gadai" class="tab-content active" style="margin-top: 20px;">
                <x-table :headers="['Tanggal', 'Kode', 'Pelanggan', 'Pengeluaran (Pinjaman)']">
                    @forelse ($data['gadaiBaru'] as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_gadai)->format('d M Y') }}</td>
                            <td style="font-weight:600;">{{ $row->kode_transaksi }}</td>
                            <td>{{ $row->pelanggan->nama ?? '-' }}</td>
                            <td style="color:#E11D48; font-weight:600;">- Rp {{ number_format($row->uang_pinjaman, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;padding:24px;">Tidak ada transaksi.</td></tr>
                    @endforelse
                </x-table>
            </div>

            <!-- TAB TEBUS -->
            <div id="tab-tebus" class="tab-content" style="margin-top: 20px; display: none;">
                <x-table :headers="['Tanggal', 'Kode', 'Pelanggan', 'Pemasukan (Tebus + Bunga)']">
                    @forelse ($data['gadaiTebus'] as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_ditebus)->format('d M Y') }}</td>
                            <td style="font-weight:600;">{{ $row->kode_transaksi }}</td>
                            <td>{{ $row->pelanggan->nama ?? '-' }}</td>
                            <td style="color:#059669; font-weight:600;">+ Rp {{ number_format($row->total_ditebus, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;padding:24px;">Tidak ada transaksi.</td></tr>
                    @endforelse
                </x-table>
            </div>

            <!-- TAB PERPANJANGAN -->
            <div id="tab-perpanjangan" class="tab-content" style="margin-top: 20px; display: none;">
                <x-table :headers="['Tanggal', 'Kode', 'Pelanggan', 'Bulan Tambahan', 'Pemasukan (Bunga)']">
                    @forelse ($data['perpanjangan'] as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_perpanjangan)->format('d M Y') }}</td>
                            <td style="font-weight:600;">{{ $row->transaksiGadai->kode_transaksi ?? '-' }}</td>
                            <td>{{ $row->transaksiGadai->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $row->tambahan_bulan }} Bulan</td>
                            <td style="color:#059669; font-weight:600;">+ Rp {{ number_format($row->biaya_perpanjangan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;padding:24px;">Tidak ada transaksi.</td></tr>
                    @endforelse
                </x-table>
            </div>

            <!-- TAB PENJUALAN -->
            <div id="tab-penjualan" class="tab-content" style="margin-top: 20px; display: none;">
                <x-table :headers="['Tanggal', 'Kode', 'Pelanggan', 'Harga Jual / Lelang']">
                    @forelse ($data['penjualan'] as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->tanggal_jual)->format('d M Y') }}</td>
                            <td style="font-weight:600;">{{ $row->transaksiGadai->kode_transaksi ?? '-' }}</td>
                            <td>{{ $row->transaksiGadai->pelanggan->nama ?? '-' }}</td>
                            <td style="color:#059669; font-weight:600;">+ Rp {{ number_format($row->harga_jual, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;padding:24px;">Tidak ada transaksi.</td></tr>
                    @endforelse
                </x-table>
            </div>

		</x-card>
	</div>
@endsection

@push('styles')
<style>
    .tabs-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        border-bottom: 2px solid #E2E8F0;
        margin-bottom: 20px;
    }
    .tab-button {
        padding: 12px 24px;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        font-family: inherit;
        font-weight: 600;
        font-size: 14px;
        color: #64748B;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: -2px;
    }
    .tab-button:hover {
        color: #0F172A;
    }
    .tab-button.active {
        color: #4F46E5;
        border-bottom-color: #4F46E5;
    }
</style>
@endpush

@push('scripts')
<script>
    function showTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.tab-button').forEach(el => el.classList.remove('active'));
        
        document.getElementById('tab-' + tabId).style.display = 'block';
        event.target.classList.add('active');
    }
</script>
@endpush
